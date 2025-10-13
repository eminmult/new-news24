# Инструкция по развертыванию Olay News на Production сервере

## Архивы для переноса

Вся система разбита на 3 архива:
1. **new1_code.tar.gz** (26MB) - Код приложения Laravel
2. **new1_media.tar.gz** (27GB) - Медиафайлы (storage/app/public)
3. **new1_db_backup.sql** (82MB) - Дамп базы данных MySQL

---

## ВАЖНО: Требования к production серверу

### Необходимые сервисы:
- **Docker** и **Docker Compose**
- **MySQL 8.0** (или контейнер mysql:8.0)
- **PHP 8.3+** с расширениями: gd, imagick, redis, mysql
- **Nginx**
- **Redis** (для очередей и кеша)
- **Supervisor** (для Laravel queue worker)

### Порты:
- 80/443 - Nginx (основной сайт)
- 3306 - MySQL (внутренний, если используется контейнер)
- 6379 - Redis (внутренний, если используется контейнер)

---

## Шаг 1: Подготовка сервера

```bash
# Создаем директорию для проекта
mkdir -p /var/www/olay-news
cd /var/www/olay-news

# Загружаем и распаковываем код
tar -xzf new1_code.tar.gz

# Устанавливаем правильные права
chown -R www-data:www-data /var/www/olay-news
chmod -R 755 /var/www/olay-news
chmod -R 775 /var/www/olay-news/storage
chmod -R 775 /var/www/olay-news/bootstrap/cache
```

---

## Шаг 2: Установка зависимостей

```bash
cd /var/www/olay-news

# Composer зависимости
composer install --optimize-autoloader --no-dev

# NPM зависимости (если нужны)
npm install
npm run build
```

---

## Шаг 3: Конфигурация окружения

### 3.1 Создать .env файл

```bash
cp .env.example .env
php artisan key:generate
```

### 3.2 Настроить .env

```env
APP_NAME="Olay News"
APP_ENV=production
APP_KEY=base64:... # Сгенерируется автоматически
APP_DEBUG=false
APP_URL=https://olay.az
FRONTEND_URL=https://olay.az

# База данных
DB_CONNECTION=mysql
DB_HOST=mysql  # или localhost
DB_PORT=3306
DB_DATABASE=olay_production
DB_USERNAME=olay_user
DB_PASSWORD=СГЕНЕРИРОВАТЬ_НАДЕЖНЫЙ_ПАРОЛЬ

# Redis
REDIS_HOST=redis  # или localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

# Очереди
QUEUE_CONNECTION=redis

# Кеш
CACHE_DRIVER=redis
SESSION_DRIVER=redis

# Почта (настроить если нужно)
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@olay.az"
MAIL_FROM_NAME="${APP_NAME}"

# Filesystem
FILESYSTEM_DISK=public

# Filament
FILAMENT_FILESYSTEM_DISK=public
```

---

## Шаг 4: Восстановление базы данных

```bash
# Создать базу данных
mysql -u root -p -e "CREATE DATABASE olay_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p -e "CREATE USER 'olay_user'@'localhost' IDENTIFIED BY 'ПАРОЛЬ';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON olay_production.* TO 'olay_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"

# Импортировать дамп
mysql -u olay_user -p olay_production < new1_db_backup.sql

# Или если используется Docker:
docker exec -i mysql_container mysql -u root -pPAROL olay_production < new1_db_backup.sql
```

---

## Шаг 5: Восстановление медиафайлов

```bash
# Распаковать медиа архив
cd /var/www/olay-news/storage/app
tar -xzf /path/to/new1_media.tar.gz

# Проверить права
chown -R www-data:www-data /var/www/olay-news/storage/app/public
chmod -R 755 /var/www/olay-news/storage/app/public

# Создать symlink для storage
cd /var/www/olay-news
php artisan storage:link
```

---

## Шаг 6: Оптимизация Laravel

```bash
cd /var/www/olay-news

# Очистить кеши
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Создать production кеши
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components

# Оптимизировать autoload
composer dump-autoload --optimize --classmap-authoritative
```

---

## Шаг 7: Настройка Nginx

Создать файл `/etc/nginx/sites-available/olay-news`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name olay.az www.olay.az;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name olay.az www.olay.az;

    root /var/www/olay-news/public;
    index index.php index.html;

    # SSL сертификаты
    ssl_certificate /etc/letsencrypt/live/olay.az/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/olay.az/privkey.pem;

    # Лимиты для загрузки файлов
    client_max_body_size 100M;
    client_body_timeout 300s;

    # Логи
    access_log /var/log/nginx/olay-access.log;
    error_log /var/log/nginx/olay-error.log;

    # Gzip
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml;

    # Основная локация
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    # Статика
    location ~* \.(jpg|jpeg|gif|png|webp|css|js|woff|woff2|ttf|svg|ico)$ {
        expires 1y;
        access_log off;
        add_header Cache-Control "public, immutable";
    }

    # Запретить доступ к скрытым файлам
    location ~ /\. {
        deny all;
    }
}
```

Активировать конфигурацию:

```bash
ln -s /etc/nginx/sites-available/olay-news /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

---

## Шаг 8: Настройка SSL (Let's Encrypt)

```bash
# Установить certbot
apt install certbot python3-certbot-nginx

# Получить сертификат
certbot --nginx -d olay.az -d www.olay.az

# Автообновление (уже настроено в certbot)
```

---

## Шаг 9: Настройка Queue Worker (Supervisor)

Создать файл `/etc/supervisor/conf.d/olay-worker.conf`:

```ini
[program:olay-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/olay-news/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/olay-news/storage/logs/worker.log
stopwaitsecs=3600
```

Запустить воркер:

```bash
supervisorctl reread
supervisorctl update
supervisorctl start olay-worker:*
```

---

## Шаг 10: Настройка Cron (Планировщик Laravel)

Добавить в crontab:

```bash
crontab -e -u www-data
```

Добавить строку:

```
* * * * * cd /var/www/olay-news && php artisan schedule:run >> /dev/null 2>&1
```

---

## Шаг 11: Финальная проверка

```bash
# Проверить права
ls -la /var/www/olay-news/storage
ls -la /var/www/olay-news/bootstrap/cache

# Проверить подключение к БД
php artisan tinker
>>> \DB::connection()->getPdo();

# Проверить очереди
php artisan queue:work --once

# Проверить статус воркеров
supervisorctl status olay-worker:*
```

---

## Шаг 12: Доступ к админ-панели

URL: https://olay.az/admin

Данные для входа берутся из таблицы `users` в базе данных.

---

## Важные пути и файлы

- **Код**: `/var/www/olay-news`
- **Медиа**: `/var/www/olay-news/storage/app/public`
- **Логи Laravel**: `/var/www/olay-news/storage/logs/laravel.log`
- **Логи Nginx**: `/var/log/nginx/olay-*.log`
- **Конфиг Nginx**: `/etc/nginx/sites-available/olay-news`
- **Supervisor**: `/etc/supervisor/conf.d/olay-worker.conf`

---

## Мониторинг и обслуживание

### Логи

```bash
# Laravel логи
tail -f /var/www/olay-news/storage/logs/laravel.log

# Nginx логи
tail -f /var/log/nginx/olay-access.log
tail -f /var/log/nginx/olay-error.log

# Worker логи
tail -f /var/www/olay-news/storage/logs/worker.log
```

### Очистка

```bash
# Очистить старые логи (раз в неделю)
cd /var/www/olay-news
php artisan log:clear

# Очистить кеш
php artisan cache:clear
php artisan view:clear
```

### Бэкапы

```bash
# Бэкап БД (ежедневно)
mysqldump -u olay_user -p olay_production > /backups/olay_$(date +%Y%m%d).sql

# Бэкап медиа (еженедельно)
tar -czf /backups/media_$(date +%Y%m%d).tar.gz /var/www/olay-news/storage/app/public
```

---

## Troubleshooting

### Ошибки 500

1. Проверить логи: `tail -f storage/logs/laravel.log`
2. Проверить права: `chown -R www-data:www-data storage bootstrap/cache`
3. Очистить кеши: `php artisan optimize:clear`

### Медиафайлы не загружаются

1. Проверить symlink: `php artisan storage:link`
2. Проверить права: `chmod -R 775 storage/app/public`
3. Проверить настройки в .env: `FILESYSTEM_DISK=public`

### Очереди не работают

1. Проверить Redis: `redis-cli ping`
2. Проверить воркер: `supervisorctl status olay-worker:*`
3. Перезапустить: `supervisorctl restart olay-worker:*`

---

## Контакты

При возникновении проблем обращаться к Claude для помощи в восстановлении.

**Версия:** 1.0
**Дата:** 2025-10-13
