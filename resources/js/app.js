// Alpine.js управляется Livewire автоматически

// ─────────────────────────────────────────────────────────────
// Ленивая загрузка тяжёлых библиотек (Swiper ~90КБ, GSAP ~110КБ)
// Раньше обе грузились на КАЖДОЙ странице сразу при старте,
// даже там, где не используются (регистрация, политики...) — это
// ~200КБ ненужного JS. Теперь библиотеки подтягиваются только
// если на странице реально есть их потребители.
// ─────────────────────────────────────────────────────────────

const needsSwiper = () =>
    document.querySelector('.hero-swiper, .lightbox-swiper') !== null;

const needsGsap = () =>
    document.querySelector(
        '#countdown, .about-event, .speaker-card, .keynote-card, .archive-banner, .schedule-card, .faq-item, .media-section, #gallery'
    ) !== null;

// Общий Promise загрузки библиотек. home.js подключается отдельной
// точкой входа и может стартовать раньше, чем импорты разрешатся,
// поэтому потребители ждут готовности через `await window.__heroLibs`.
window.__heroLibs = (async () => {
    const libs = { gsap: null, Swiper: null };

    if (needsGsap()) {
        const { default: gsap } = await import('gsap');
        const { default: ScrollTrigger } = await import('gsap/ScrollTrigger');
        gsap.registerPlugin(ScrollTrigger);
        window.gsap = gsap;          // для инлайн-скриптов Blade
        window.ScrollTrigger = ScrollTrigger;
        libs.gsap = gsap;
    }

    if (needsSwiper()) {
        const { default: Swiper } = await import('swiper');
        const { Navigation, Pagination, Parallax, Autoplay } = await import('swiper/modules');
        Swiper.use([Navigation, Pagination, Parallax, Autoplay]);
        window.Swiper = Swiper;      // для Lightbox галереи (home.blade.php)
        libs.Swiper = Swiper;
    }

    return libs;
})();

// Plyr — видео в секции «О мероприятии». Импортируем только на
// страницах, где есть плеер (element с x-ref="player").
window.Plyr = null;
document.addEventListener('DOMContentLoaded', () => {
    if (!document.querySelector('[x-ref="player"]')) return;
    import('plyr').then(({ default: PlyrClass }) => {
        import('plyr/dist/plyr.css');
        window.Plyr = PlyrClass;
    });
});

// ─────────────────────────────────────────────────────────────
// Navbar: чистая CSS-реакция на скролл (пункт 6 оптимизаций)
// Раньше на КАЖДОЕ событие scroll создавались новые GSAP-твины
// (gsap.to) без kill() — это утечка памяти и лишняя работа.
// Теперь скролл обрабатывается через requestAnimationFrame и лишь
// переключает класс .navbar-scrolled; плавность делают CSS
// transition из home.css. GSAP для навбара больше не нужен.
// ─────────────────────────────────────────────────────────────
const navbar = document.getElementById('main-navbar');
let navbarTick = null;

function updateNavbar() {
    navbarTick = null;
    if (!navbar) return;
    navbar.classList.toggle('navbar-scrolled', window.scrollY > 30);
}

window.addEventListener('scroll', () => {
    if (navbarTick === null) {
        navbarTick = requestAnimationFrame(updateNavbar);
    }
}, { passive: true });

updateNavbar(); // корректное состояние при загрузке (якорные переходы/обновление)

// ─────────────────────────────────────────────────────────────
// Hero-слайдер (Swiper)
// ─────────────────────────────────────────────────────────────
function initSwiper() {
    const heroSwiperEl = document.querySelector('.hero-swiper');
    if (!heroSwiperEl || !window.Swiper) return;

    new window.Swiper('.hero-swiper', {
        loop: true,
        speed: 900,
        parallax: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
            enabled: true,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'bullets',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
}

// ─────────────────────────────────────────────────────────────
// Таймер обратного отсчёта (пункт 7 оптимизаций)
// Раньше каждую секунду создавались 4 GSAP-твина (по одному на
// каждый блок). Теперь анимация смены цифры — это чистый CSS
// (keyframes countdownFlip из home.css), без участия GSAP.
// ─────────────────────────────────────────────────────────────
function initCountdown() {
    const countdownEl = document.getElementById('countdown');
    if (!countdownEl) return;

    const startDate = new Date(countdownEl.dataset.start).getTime();
    const labels = ['days', 'hours', 'minutes', 'seconds'];
    const pad = (n) => String(n).padStart(2, '0');

    const updateCounter = (element, value) => {
        if (!element || element.textContent === value) return;
        element.classList.remove('countdown-flip');
        void element.getBoundingClientRect(); // рестарт CSS-анимации
        element.textContent = value;
        element.classList.add('countdown-flip');
    };

    const updateCountdown = () => {
        const distance = Math.max(0, startDate - Date.now());
        const totalSeconds = Math.floor(distance / 1000);

        const values = {
            days: pad(Math.floor(totalSeconds / 86400)),
            hours: pad(Math.floor((totalSeconds % 86400) / 3600)),
            minutes: pad(Math.floor((totalSeconds % 3600) / 60)),
            seconds: pad(totalSeconds % 60),
        };

        labels.forEach((label) => updateCounter(document.getElementById(`countdown-${label}`), values[label]));
    };

    updateCountdown();
    setInterval(updateCountdown, 1000);
}

// ─────────────────────────────────────────────────────────────
// Анимации появления при скролле (GSAP + ScrollTrigger)
// Выполняются только после того, как GSAP реально загрузился
// (если библиотека не нужна — элементы просто видны сразу).
// ─────────────────────────────────────────────────────────────
function initScrollAnimations() {
    if (!window.gsap) return;

    const animatedElements = window.gsap.utils.toArray(
        '.about-event,.speaker-card,.keynote-card,.archive-banner,.schedule-card,.faq-item'
    );

    animatedElements.forEach((el) => {
        window.gsap.from(el, {
            scrollTrigger: { trigger: el, start: 'top 85%' },
            y: 40,
            opacity: 0,
            duration: 0.8,
        });
    });

    const galleryTitle = document.querySelector('.gallery-title');
    if (galleryTitle) {
        window.gsap.from(galleryTitle, {
            scrollTrigger: { trigger: galleryTitle, start: 'top 90%' },
            y: 30,
            opacity: 0,
            duration: 0.6,
        });
    }

    const galleryItems = window.gsap.utils.toArray('#gallery .gallery-item');
    if (galleryItems.length) {
        window.gsap.from(galleryItems, {
            scrollTrigger: { trigger: '#gallery', start: 'top 85%' },
            y: 60,
            opacity: 0,
            scale: 0.95,
            duration: 0.7,
            stagger: 0.08,
            ease: 'power2.out',
        });
    }

    const mediaSection = document.querySelector('.media-section');
    if (mediaSection) {
        const mediaPhoto = mediaSection.querySelector('.media-photo');
        const mediaText = mediaSection.querySelector('.media-text');

        if (mediaPhoto) {
            window.gsap.from(mediaPhoto, {
                scrollTrigger: { trigger: mediaSection, start: 'top 80%' },
                x: -120,
                opacity: 0,
                duration: 1,
                ease: 'power3.out',
            });
        }

        if (mediaText) {
            window.gsap.from(mediaText, {
                scrollTrigger: { trigger: mediaSection, start: 'top 80%' },
                x: 120,
                opacity: 0,
                duration: 1,
                ease: 'power3.out',
                delay: 0.2,
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    await window.__heroLibs; // ждём лениво-загруженные библиотеки

    initSwiper();

    if (window.Livewire) {
        // Повторная инициализация Swiper после Livewire-обновления
        window.Livewire.on('refreshSwiper', () => setTimeout(() => initSwiper(), 100));
    }

    initCountdown();
    initScrollAnimations();
});