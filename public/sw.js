const CACHE_NAME = 'fifth-event-v4';

// Установка Service Worker
self.addEventListener('install', (event) => {
    console.log('Service Worker: Установка');
    self.skipWaiting();
});

// Активация Service Worker
self.addEventListener('activate', (event) => {
    console.log('Service Worker: Активация');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Service Worker: Удаление старого кэша', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Перехват запросов
self.addEventListener('fetch', (event) => {
    // Игнорируем не-HTTP запросы
    if (!event.request.url.startsWith('http')) {
        return;
    }

    const url = new URL(event.request.url);
    
    // Игнорируем запросы к статическим ресурсам, которые не критичны
    const ignoredPaths = ['/favicon.ico', '/sw.js', '/health'];
    if (ignoredPaths.some(path => url.pathname === path)) {
        return;
    }

    // Игнорируем запросы к внешним ресурсам (шрифты, CDN и т.д.)
    if (!url.hostname.includes('events.elprof.ru')) {
        return;
    }

    // Для навигационных запросов (переходы между страницами)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    // Успешный ответ — кэшируем
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                    return response;
                })
                .catch(async () => {
                    // Нет сети — ищем в кэше
                    console.log('Service Worker: Нет сети, проверяем кэш');
                    
                    const cachedResponse = await caches.match(event.request);
                    if (cachedResponse) {
                        console.log('Service Worker: Возвращаем из кэша');
                        return cachedResponse;
                    }
                    
                    // Если ничего нет в кэше — показываем базовую оффлайн страницу
                    console.log('Service Worker: Показываем оффлайн заглушку');
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
                })
        );
        return;
    }

    // Для остальных запросов (статика, API) — Cache First
    event.respondWith(
        caches.match(event.request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(event.request).catch(() => {
                    // Пропускаем ошибки для статических ресурсов (иконки, шрифты и т.д.)
                    return null;
                });
            })
    );
});

