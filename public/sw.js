const CACHE_NAME = 'fifth-event-v2';
const OFFLINE_URL = '/offline';

// Кэшируемые ресурсы при установке
const STATIC_ASSETS = [
    '/',
    '/offline',
    '/favicon.png',
    '/manifest.json',
];

// Установка Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Service Worker: Кэширование статических ресурсов');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                // Предзагружаем офлайн страницу
                return caches.open(CACHE_NAME).then((cache) => {
                    return cache.add('/offline').catch(err => {
                        console.log('Service Worker: Оффлайн страница уже в кэше или кэширована');
                    });
                });
            })
            .then(() => {
                // Кэсируем главную страницу для оффлайн доступа
                return caches.open(CACHE_NAME).then((cache) => {
                    return cache.add('/').catch(err => {
                        console.log('Service Worker: Главная страница уже в кэше');
                    });
                });
            })
            .catch((error) => {
                console.log('Service Worker: Ошибка кэширования', error);
            })
    );
    self.skipWaiting();
});

// Активация Service Worker
self.addEventListener('activate', (event) => {
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
    
    // Пропускаем запросы к health check
    if (url.pathname === '/health') {
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
                        if (event.request.method === 'GET') {
                            cache.put(event.request, responseClone);
                        }
                    });
                    return response;
                })
                .catch(() => {
                    // Нет сети — ищем в кэше
                    return caches.match(event.request).then((cachedResponse) => {
                        if (cachedResponse) {
                            console.log('Service Worker: Возвращаем из кэша', url.pathname);
                            return cachedResponse;
                        }
                        
                        // Если ничего нет в кэше — показываем офлайн страницу
                        return caches.match('/offline').then((offlineResponse) => {
                            if (offlineResponse) {
                                console.log('Service Worker: Возвращаем офлайн страницу');
                                return offlineResponse;
                            }
                            
                            // Базовая заглушка если офлайн страница тоже не в кэше
                            return caches.match('/').then((homeResponse) => {
                                if (homeResponse) {
                                    return homeResponse;
                                }
                                
                                return new Response('<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Нет подключения</title><style>body{font-family:system-ui,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#f7f7f7;color:#222;text-align:center;padding:2rem}h1{font-size:2rem;margin:0 0 1rem}p{color:#666;margin:0 0 2rem}button{background:#ff385c;color:#fff;border:none;border-radius:8px;padding:0.75rem 1.5rem;font-weight:600;cursor:pointer}</style></head><body><div><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:80px;height:80px;color:#ff385c;margin-bottom:1rem"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg><h1>Нет подключения к интернету</h1><p>Проверьте подключение к сети и попробуйте снова.</p><button onclick="location.reload()">Обновить страницу</button></div></body></html>', {
                                    headers: new Headers({
                                        'Content-Type': 'text/html; charset=utf-8',
                                    }),
                                });
                            });
                        });
                    });
                })
        );
        return;
    }

    // Для остальных запросов (статика, API) — Strategy: Cache First
    event.respondWith(
        caches.match(event.request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                return fetch(event.request)
                    .then((response) => {
                        // Копируем ответ для кэширования
                        const responseClone = response.clone();
                        
                        caches.open(CACHE_NAME).then((cache) => {
                            // Кэшируем только GET запросы
                            if (event.request.method === 'GET') {
                                cache.put(event.request, responseClone);
                            }
                        });

                        return response;
                    });
            })
    );
});

// Обработка сообщений от клиентов
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
