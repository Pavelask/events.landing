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

    function playMultipassSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const notes = [523.25, 659.25, 783.99, 1046.50];
            notes.forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.15, ctx.currentTime + i * 0.12);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.12 + 0.3);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + i * 0.12);
                osc.stop(ctx.currentTime + i * 0.12 + 0.3);
            });
        } catch (e) {}
    }

    function showEasterEgg() {
        const egg = document.getElementById('easter-egg');
        const backdrop = document.getElementById('easter-egg-backdrop');
        const img = document.getElementById('easter-egg-img');
        const badge = document.getElementById('easter-egg-badge');
        if (!egg || !backdrop || !img || !badge) return;

        playMultipassSound();

        egg.style.display = 'flex';
        egg.style.pointerEvents = 'auto';

        const tl = gsap.timeline({
            onComplete: () => {
                gsap.to(backdrop, { background: 'rgba(0,0,0,0)', duration: 0.4 });
                gsap.to([img, badge], {
                    scale: 0.5, opacity: 0, duration: 0.4, stagger: 0.05,
                    onComplete: () => {
                        egg.style.display = 'none';
                        egg.style.pointerEvents = 'none';
                        gsap.set([img, badge], { clearProps: 'all' });
                    }
                });
            }
        });

        tl.to(backdrop, { background: 'rgba(0,0,0,0.6)', duration: 0.3 })
          .to(img, { opacity: 1, scale: 1, rotation: 0, duration: 0.6, ease: 'back.out(1.7)' }, '-=0.1')
          .to(badge, { opacity: 1, y: 0, duration: 0.5, ease: 'back.out(1.7)' }, '-=0.3')
          .to({}, { duration: 2.5 });
    }

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
                showEasterEgg();
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
