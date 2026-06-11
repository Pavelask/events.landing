// Alpine.js управляется Livewire автоматически
// Импорты библиотек
import Swiper from 'swiper';
import { Navigation, Pagination, Parallax, Autoplay } from 'swiper/modules';
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
Swiper.use([Navigation, Pagination, Parallax, Autoplay]);

// Alpine.js будет доступен глобально после запуска Livewire

// Экспорт библиотек для использования в Blade-шаблонах
window.Swiper = Swiper;
window.Plyr = Plyr;

// Альтернатива: используем window scroll с GSAP
let navbarScrollTimeout;
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('main-navbar');
    if (!navbar) return;
    
    const scrollY = window.scrollY;
    
    // Clear previous timeout
    clearTimeout(navbarScrollTimeout);
    
    if (scrollY > 30) {
        if (!navbar.classList.contains('navbar-scrolled')) {
            navbar.classList.add('navbar-scrolled');
            
            // GSAP анимация появления - фон и хедер
            gsap.to(navbar, { 
                backgroundColor: 'rgba(255,255,255,0.85)', 
                backdropFilter: 'blur(16px)',
                boxShadow: '0 2px 10px rgba(0,0,0,0.08)',
                opacity: 1,
                duration: 0.3, 
                ease: 'power2.out' 
            });
            
            // Логотип и текст - появляются
            const logoText = navbar.querySelector('.navbar-logo');
            if (logoText) {
                gsap.fromTo(logoText, 
                    { opacity: 0, y: -20 },
                    { opacity: 1, y: 0, duration: 0.3, ease: 'power2.out' }
                );
            }
            
            // Навигационные ссылки (desktop) - появляются
            const navLinks = navbar.querySelectorAll('.navbar-navlinks a');
            if (navLinks.length > 0) {
                gsap.fromTo(navLinks,
                    { opacity: 0, y: -15 },
                    { opacity: 1, y: 0, duration: 0.3, ease: 'power2.out', stagger: 0.05 }
                );
            }
            
            // Кнопка меню (mobile) - появляется
            const menuBtn = navbar.querySelector('.navbar-menu-btn');
            if (menuBtn) {
                gsap.fromTo(menuBtn,
                    { opacity: 0, scale: 0.7 },
                    { opacity: 1, scale: 1, duration: 0.3, ease: 'back.out(1.7)' }
                );
            }
            
            // Мобильное меню (выпадающее) - закрываем и скрываем
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu) {
                mobileMenu.style.display = 'none';
            }
        }
    } else {
        if (navbar.classList.contains('navbar-scrolled')) {
            navbarScrollTimeout = setTimeout(() => {
                navbar.classList.remove('navbar-scrolled');
                
                // GSAP анимация исчезновения - фон и хедер
                gsap.to(navbar, { 
                    backgroundColor: 'rgba(255,255,255,0)', 
                    backdropFilter: 'none',
                    boxShadow: 'none',
                    opacity: 0,
                    duration: 0.3, 
                    ease: 'power2.out' 
                });
                
                // Логотип и текст - исчезают
                const logoText = navbar.querySelector('.navbar-logo');
                if (logoText) {
                    gsap.to(logoText, { opacity: 0, y: -20, duration: 0.25, ease: 'power2.in' });
                }
                
                // Навигационные ссылки (desktop) - исчезают
                const navLinks = navbar.querySelectorAll('.navbar-navlinks a');
                if (navLinks.length > 0) {
                    gsap.to(navLinks, { opacity: 0, y: -15, duration: 0.25, ease: 'power2.in', stagger: 0.02 });
                }
                
                // Кнопка меню (mobile) - исчезает
                const menuBtn = navbar.querySelector('.navbar-menu-btn');
                if (menuBtn) {
                    gsap.to(menuBtn, { opacity: 0, scale: 0.7, duration: 0.25, ease: 'power2.in' });
                }
                
                // Мобильное меню (выпадающее) - гарантированно скрываем
                const mobileMenu = document.getElementById('mobileMenu');
                if (mobileMenu) {
                    mobileMenu.style.display = 'none';
                }
            }, 50);
        }
    }
}, { passive: true });

// Инициализация Swiper
function initSwiper() {
    const heroSwiperEl = document.querySelector('.hero-swiper');
    
    if (heroSwiperEl) {
        // console.log('Hero swiper found, initializing...');
        
        // Проверяем, что слайды есть
        const slides = heroSwiperEl.querySelectorAll('.swiper-slide');
        console.log('Slides count:', slides.length);
        
        if (slides.length < 2) {
            console.log('Less than 2 slides, autoplay not needed');
            return;
        }
        
        const swiper = new Swiper('.hero-swiper', {
            loop: false,
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
            on: {
                init: function() {
                    // console.log('Swiper initialized with', this.slides.length, 'slides');
                    // console.log('Autoplay enabled:', this.params.autoplay.enabled);
                }
            },
        });
        // console.log('Hero swiper initialized');
    }
}

// Анимация элементов при скролле и таймер обратного отсчета
document.addEventListener('DOMContentLoaded', () => {
    initSwiper();

    // Повторная инициализация Swiper после Livewire обновления
    if (window.Livewire) {
        window.Livewire.on('refreshSwiper', () => {
            console.log('Refreshing Swiper...');
            setTimeout(() => initSwiper(), 100);
        });
    }

    // GSAP инициализация хедера
    const navbar = document.getElementById('main-navbar');
    if (navbar) {
        // Начальное состояние - прозрачный фон и невидимый хедер
        gsap.set(navbar, { 
            backgroundColor: 'rgba(255,255,255,0)',
            backdropFilter: 'none',
            boxShadow: 'none',
            opacity: 0
        });
        
        // Логотип и текст - изначально невидимы
        const logoText = navbar.querySelector('.navbar-logo');
        if (logoText) {
            gsap.set(logoText, { opacity: 0, y: -20 });
        }
        
        // Навигационные ссылки (desktop) - изначально невидимы
        const navLinks = navbar.querySelectorAll('.navbar-navlinks a');
        if (navLinks.length > 0) {
            gsap.set(navLinks, { opacity: 0, y: -15 });
        }
        
        // Кнопка меню (mobile) - изначально невидима
        const menuBtn = navbar.querySelector('.navbar-menu-btn');
        if (menuBtn) {
            gsap.set(menuBtn, { opacity: 0, scale: 0.7 });
        }
        
        // Мобильное меню изначально скрыто
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) {
            mobileMenu.style.display = 'none';
        }
    }

    const animatedElements = gsap.utils.toArray('.about-event,.speaker-card,.keynote-card,.timeline-item,.faq-item,.archive-banner,.schedule-card');

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
    const gallerySection = document.querySelector('#gallery');
    const galleryItems = gsap.utils.toArray('.gallery-item');
    if (galleryItems.length > 0 && gallerySection) {
        gsap.from(galleryItems, {
            scrollTrigger: {
                trigger: '#gallery',
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

