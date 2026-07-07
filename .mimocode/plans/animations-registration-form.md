# Анимации для формы регистрации

## Цель
Сделать форму регистрации более привлекательной с помощью плавных анимаций.

## Изменения в `resources/views/livewire/anon-registration.blade.php`

### 1. Загрузка страницы
- Плавное появление формы (`opacity: 0 → 1`, `transform: translateY(20px → 0)`)
- Длительность: 500ms

### 2. Поля формы
- Staggered animation — каждое поле появляется с задержкой 50ms
- Эффект `transform: translateX(-10px → 0)` + `opacity: 0 → 1`
- При фокусе: плавное изменение border-color и box-shadow

### 3. Кнопка
- Hover: `scale(1.02)`, `box-shadow` увеличивается
- Loading: пульсация (`opacity` мигает)
- Transition: 200ms ease

### 4. Сообщения
- Success: `slideDown` + `fadeIn` (0.3s)
- Error: `slideDown` + `fadeIn` (0.3s)

### 5. Ошибки валидации
- Красный border появляется плавно (transition 0.2s)
- Сообщение об ошибке появляется с `opacity: 0 → 1`

### Технологии
- Alpine.js `x-init` + CSS transitions
- CSS `@keyframes` для staggered анимаций
- Tailwind CSS для стилей

## Файлы
- `resources/views/livewire/anon-registration.blade.php` — основные изменения
