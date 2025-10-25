<?php

// Прямое подключение к Redis через PhpRedis
$redis = new Redis();
$redis->connect('redis-new1', 6379);

// Тест 1: Установить ключ с TTL 31536000 (1 год)
echo "Тест 1: PhpRedis->setex() с TTL = 31536000\n";
$redis->setex('phpredis_test_year', 31536000, 'test_value');
$ttl = $redis->ttl('phpredis_test_year');
echo "  Реальный TTL: $ttl секунд\n";

// Тест 2: Установить ключ с TTL 7200 (2 часа)
echo "\nТест 2: PhpRedis->setex() с TTL = 7200\n";
$redis->setex('phpredis_test_hour', 7200, 'test_value');
$ttl = $redis->ttl('phpredis_test_hour');
echo "  Реальный TTL: $ttl секунд\n";

// Тест 3: Проверить max TTL через Laravel
echo "\nТест 3: Проверка через Laravel Cache\n";
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

Cache::put('laravel_test_year', 'value', 31536000);

// Найти ключ в Redis
$keys = $redis->keys('*laravel_test_year*');
if (!empty($keys)) {
    $key = $keys[0];
    $ttl = $redis->ttl($key);
    echo "  Laravel Cache TTL: $ttl секунд\n";
    echo "  Ключ: $key\n";
} else {
    echo "  Ключ не найден!\n";
}

echo "\nГотово!\n";
