// ===================================================================
// SERVICE WORKER ДЛЯ NEWS24.AZ
// Кеширование статики для быстрого повторного посещения
// ===================================================================

const CACHE_VERSION = 'news24-v1';
const CACHE_STATIC = `${CACHE_VERSION}-static`;
const CACHE_DYNAMIC = `${CACHE_VERSION}-dynamic`;

// Файлы для предварительного кеширования
const PRECACHE_URLS = [
    '/',
    '/css/style.min.css',
    '/js/script.min.js',
    '/images/newslogo3.svg',
    '/offline.html', // Создать эту страницу отдельно
];

// Установка Service Worker
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker...', event);

    event.waitUntil(
        caches.open(CACHE_STATIC)
            .then((cache) => {
                console.log('[SW] Precaching static assets');
                return cache.addAll(PRECACHE_URLS);
            })
    );

    self.skipWaiting();
});

// Активация Service Worker
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker...', event);

    event.waitUntil(
        caches.keys()
            .then((keyList) => {
                return Promise.all(keyList.map((key) => {
                    if (key !== CACHE_STATIC && key !== CACHE_DYNAMIC) {
                        console.log('[SW] Removing old cache:', key);
                        return caches.delete(key);
                    }
                }));
            })
    );

    return self.clients.claim();
});

// Перехват fetch запросов
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Не кешировать API запросы и админку
    if (url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/api') ||
        request.method !== 'GET') {
        return;
    }

    // Стратегия для статических файлов: Cache First
    if (request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font') {

        event.respondWith(
            caches.match(request)
                .then((response) => {
                    if (response) {
                        console.log('[SW] Cache hit:', request.url);
                        return response;
                    }

                    return fetch(request).then((response) => {
                        return caches.open(CACHE_STATIC)
                            .then((cache) => {
                                cache.put(request, response.clone());
                                return response;
                            });
                    });
                })
                .catch(() => {
                    console.log('[SW] Fetch failed for:', request.url);
                })
        );
    }

    // Стратегия для HTML страниц: Network First, fallback to Cache
    else if (request.destination === 'document') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    return caches.open(CACHE_DYNAMIC)
                        .then((cache) => {
                            cache.put(request, response.clone());
                            return response;
                        });
                })
                .catch(() => {
                    return caches.match(request)
                        .then((response) => {
                            return response || caches.match('/offline.html');
                        });
                })
        );
    }
});

// Обработка сообщений от главной страницы
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_URLS') {
        caches.open(CACHE_DYNAMIC)
            .then((cache) => {
                cache.addAll(event.data.urls);
            });
    }
});
