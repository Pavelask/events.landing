// Полоса загрузки страницы
(function () {
    const progressBar = document.getElementById('page-progress-bar');
    if (!progressBar) return;

    window.addEventListener('load', function () {
        progressBar.classList.add('loading');
        setTimeout(function () {
            progressBar.classList.add('complete');
            progressBar.classList.remove('loading');
        }, 600);
    });

    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (!link || link.hasAttribute('data-no-progress')) return;

        const href = link.getAttribute('href');
        if (!href) return;

        // Пропускаем хеш-ссылки, якоря и javascript: — они не перезагружают страницу
        if (href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank') return;

        // Пропускаем ссылки на ту же страницу (только хеш изменился)
        const url = new URL(href, window.location.origin);
        if (url.pathname === window.location.pathname && url.origin === window.location.origin) return;

        progressBar.classList.remove('complete');
        progressBar.classList.add('loading');
    });

    window.addEventListener('beforeunload', function () {
        progressBar.classList.remove('complete');
    });
})();

// Пасхалка Лилу Даллас — 5 кликов по картинке
(function () {
    let posterClicks = 0;
    let lastClickTime = 0;

    document.addEventListener('DOMContentLoaded', function () {
        const poster = document.querySelector('#about img[alt]');
        if (!poster) return;

        poster.addEventListener('click', function () {
            const now = Date.now();
            if (now - lastClickTime > 2000) {
                posterClicks = 0;
            }
            lastClickTime = now;
            posterClicks++;

            if (posterClicks >= 5) {
                posterClicks = 0;
                const egg = document.getElementById('easter-egg');
                if (egg) {
                    egg.style.display = 'flex';
                    setTimeout(() => (egg.style.display = 'none'), 2000);
                }
            }
        });
    });
})();

// Регистрация Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

// Офлайн-уведомление
(function () {
    const offlineNotification = document.getElementById('offline-notification');
    if (!offlineNotification) return;

    window.addEventListener('online', () => {
        offlineNotification.classList.add('hidden');
    });

    window.addEventListener('offline', () => {
        offlineNotification.classList.remove('hidden');
        setTimeout(() => {
            if (!offlineNotification.classList.contains('hidden')) {
                window.location.href = '/offline';
            }
        }, 2000);
    });

    if (!navigator.onLine) {
        offlineNotification.classList.remove('hidden');
    }
})();

// Инкремент счётчика просмотров галереи
(function () {
    let galleryViewIncremented = false;
    document.addEventListener('click', function (e) {
        const galleryImage = e.target.closest('.gallery-image');
        if (galleryImage && !galleryViewIncremented) {
            galleryViewIncremented = true;
            fetch('/api/gallery-view', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    event_slug: document.querySelector('meta[name="event-slug"]')?.content || '',
                }),
            }).catch(() => {});
        }
    });
})();
