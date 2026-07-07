const CACHE_NAME = 'fifth-event-v7';

const OFFLINE_PAGE = '/offline';

const PRECACHE_URLS = [
    '/',
    '/offline',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_URLS).then(() => {
                return cache.put(OFFLINE_PAGE, new Response('<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Нет подключения</title></head><body>Offline</body></html>', {
                    headers: { 'Content-Type': 'text/html; charset=utf-8' }
                }));
            });
        }).then(() => {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (!event.request.url.startsWith('http')) {
        return;
    }

    const url = new URL(event.request.url);

    if (url.pathname === '/sw.js' || url.pathname === '/health') {
        return;
    }

    if (!url.hostname.includes('events.elprof.ru')) {
        return;
    }

    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                    return response;
                })
                .catch(async () => {
                    const cachedResponse = await caches.match(event.request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }

                    const homeCached = await caches.match('/');
                    if (homeCached) {
                        return homeCached;
                    }

                    return caches.match(OFFLINE_PAGE).then((offlineResponse) => {
                        if (offlineResponse) {
                            return offlineResponse;
                        }
                        return new Response(`<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Нет подключения</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f7f7f7;
            color: #222;
            padding: 2rem;
        }
        .container { text-align: center; max-width: 500px; }
        svg { width: 80px; height: 80px; color: #ff385c; margin-bottom: 1rem; }
        h1 { font-size: 1.5rem; margin-bottom: 1rem; }
        p { color: #666; margin-bottom: 2rem; line-height: 1.5; }
        button {
            background: #ff385c;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover { background: #e53e5c; }
    </style>
</head>
<body>
    <div class="container">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
        </svg>
        <h1>Нет подключения к интернету</h1>
        <p>Проверьте подключение к сети и попробуйте снова.</p>
        <button onclick="location.reload()">Обновить страницу</button>
    </div>
</body>
</html>`, {
                            headers: { 'Content-Type': 'text/html; charset=utf-8' },
                        });
                    });
                })
        );
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(event.request).then((response) => {
                    if (response.ok) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return response;
                }).catch(() => {
                    return null;
                });
            })
    );
});
