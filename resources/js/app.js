import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Navigation, Pagination, Parallax } from 'swiper/modules';
import Plyr from 'plyr';
import 'plyr/dist/plyr.css';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

// Экспорт библиотек в window ДО регистрации и использования
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// Регистрация плагина ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

// Регистрация модулей Swiper
Swiper.use([Navigation, Pagination, Parallax]);

// Инициализация Alpine.js - отключено, Livewire управляет Alpine
window.Alpine = Alpine;
// Alpine.start(); // Livewire запустит Alpine автоматически

// Экспорт библиотек для использования в Blade-шаблонах
window.Swiper = Swiper;
window.Plyr = Plyr;

// Альтернатива: используем window scroll
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('main-navbar');
    if (navbar) {
        if (window.scrollY > 80) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    }
}, { passive: true });

// Инициализация Swiper
function initSwiper() {
    if (document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            loop: true,                          // Бесконечная прокрутка
            speed: 900,                           // Скорость перехода между слайдами (мс)
            parallax: true,                       // Эффект параллакса для фоновых изображений
            autoplay: {                            // Автоматическая смена слайдов
                delay: 3000,                       // Интервал 3 секунды
                disableOnInteraction: false,       // Продолжать автоплей после взаимодействия
            },
            pagination: {                         // Точки пагинации с превью
                el: '.swiper-pagination',
                clickable: true,                  // Клик по точке переключает слайд
                type: 'bullets',                   // Тип пагинации — точки-превью
            },
            navigation: {                         // Стрелки навигации
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: {                              // Превью слайдов (миниатюры)
                swiper: null,                      // Инициализируется отдельно, если есть .hero-thumbs
            },
        });

        // Превью-слайдер (миниатюры под основным)
        const thumbsEl = document.querySelector('.hero-thumbs');
        if (thumbsEl) {
            const thumbsSwiper = new Swiper('.hero-thumbs', {
                loop: true,
                spaceBetween: 10,                  // Отступ между миниатюрами
                slidesPerView: 4,                  // Количество видимых миниатюр
                watchSlidesProgress: true,         // Отслеживание прогресса для синхронизации
                freeMode: true,                    // Свободная прокрутка миниатюр
            });

            // Привязываем основной слайдер к превью
            document.querySelector('.hero-swiper').swiper.controller.control = thumbsSwiper;
            thumbsSwiper.controller.control = document.querySelector('.hero-swiper').swiper;
        }
    }
}

// Анимация элементов при скролле и таймер обратного отсчета
document.addEventListener('DOMContentLoaded', () => {
    initSwiper();

    const animatedElements = gsap.utils.toArray('.about-event,.speaker-card,.keynote-card,.timeline-item,.faq-item,.archive-banner');

    animatedElements.forEach(el => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: 'top 85%'
            },
            y: 40,
            opacity: 0,
            duration: 0.8
        });
    });

    // Анимация галереи - заголовок
    const galleryTitle = document.querySelector('.gallery-title');
    if (galleryTitle) {
        gsap.from(galleryTitle, {
            scrollTrigger: {
                trigger: galleryTitle,
                start: 'top 90%'
            },
            y: 30,
            opacity: 0,
            duration: 0.6
        });
    }

    // Анимация галереи - элементы (stagger effect)
    const galleryItems = gsap.utils.toArray('.gallery-item');
    if (galleryItems.length > 0) {
        gsap.from(galleryItems, {
            scrollTrigger: {
                trigger: '.gallery-masonry',
                start: 'top 85%'
            },
            y: 60,
            opacity: 0,
            scale: 0.95,
            duration: 0.7,
            stagger: 0.08,
            ease: 'power2.out'
        });

        // GSAP hover эффект - увеличение при наведении
        galleryItems.forEach(item => {
            const img = item.querySelector('.gallery-image');
            if (!img) return;

            item.addEventListener('mouseenter', () => {
                gsap.to(img, {
                    scale: 1.08,
                    duration: 0.35,
                    ease: 'power2.out',
                    transformOrigin: 'center center'
                });
            });

            item.addEventListener('mouseleave', () => {
                gsap.to(img, {
                    scale: 1,
                    duration: 0.35,
                    ease: 'power2.out',
                    transformOrigin: 'center center'
                });
            });
        });
    }

    // Анимация секции Медиа-контент
    const mediaSection = document.querySelector('.media-section');
    if (mediaSection) {
        const mediaPhoto = mediaSection.querySelector('.media-photo');
        const mediaText = mediaSection.querySelector('.media-text');

        if (mediaPhoto) {
            gsap.from(mediaPhoto, {
                scrollTrigger: {
                    trigger: mediaSection,
                    start: 'top 80%'
                },
                x: -120,
                opacity: 0,
                duration: 1,
                ease: 'power3.out'
            });
        }

        if (mediaText) {
            gsap.from(mediaText, {
                scrollTrigger: {
                    trigger: mediaSection,
                    start: 'top 80%'
                },
                x: 120,
                opacity: 0,
                duration: 1,
                ease: 'power3.out',
                delay: 0.2
            });
        }
    }

    // Таймер обратного отсчета с GSAP анимацией
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        const startDate = new Date(countdownEl.dataset.start).getTime();
        const labels = ['days', 'hours', 'minutes', 'seconds'];

        const pad = (n) => String(n).padStart(2, '0');

        const updateCounter = (element, value) => {
            if (!element || element.textContent === value) return;

            // GSAP анимация обновления числа
            if (window.gsap) {
                window.gsap.fromTo(element,
                    { y: -12, opacity: 0.4 },
                    { y: 0, opacity: 1, duration: 0.25 }
                );
            }
            element.textContent = value;
        };

        const updateCountdown = () => {
            const now = Date.now();
            const distance = Math.max(0, startDate - now);

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const timeValues = {
                days: pad(days),
                hours: pad(hours),
                minutes: pad(minutes),
                seconds: pad(seconds)
            };

            labels.forEach(label => {
                const element = document.getElementById(`countdown-${label}`);
                updateCounter(element, timeValues[label]);
            });
        };

        // Первичное обновление
        updateCountdown();

        // Обновление каждую секунду
        setInterval(updateCountdown, 1000);
    }
});

