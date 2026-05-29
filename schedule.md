<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Расписание · IX Всероссийский слёт молодёжи</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><text y='28' font-size='28'>📅</text></svg>">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
  <style>
    @theme {
      --color-od-bg: #ffffff;
      --color-od-surface: #f5f5f7;
      --color-od-fg: #1d1d1f;
      --color-od-muted: #6e6e73;
      --color-od-border: #d2d2d7;
      --color-od-accent: #0071e3;
      --color-od-accent-hover: #0077ed;
      --color-od-green: #34c759;
      --color-od-green-soft: #e8f8ed;
      --color-od-past: #aeaeb2;
      --color-od-warm: #fbfbfd;
    }

    @keyframes pulse-dot {
      0%, 100% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--color-od-green) 60%, transparent); }
      50% { box-shadow: 0 0 0 10px color-mix(in srgb, var(--color-od-green) 0%, transparent); }
    }
    .pulse-green { animation: pulse-dot 2s infinite; }

    @keyframes pulse-badge {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.6; }
    }
    .pulse-badge { animation: pulse-badge 2s infinite; }

    .timeline-line {
      position: absolute;
      left: 23px;
      top: 0;
      bottom: 0;
      width: 2px;
    }

    .scrollbar-thin::-webkit-scrollbar { height: 4px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: var(--color-od-border); border-radius: 2px; }

    .cal-dropdown {
      position: absolute;
      right: 0;
      top: 100%;
      min-width: 200px;
      z-index: 50;
      background: #fff;
      border: 1px solid var(--color-od-border);
      border-radius: 10px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      padding: 6px;
      margin-top: 4px;
    }
    .cal-dropdown a {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 12px; border-radius: 6px;
      font-size: 14px; color: var(--color-od-fg);
      transition: background 0.12s;
    }
      .cal-dropdown a:hover { background: var(--color-od-surface); }
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="bg-[var(--color-od-bg)] text-[var(--color-od-fg)] font-sans antialiased">

<div x-data="scheduleApp()" x-init="init()" class="min-h-screen">

  <!-- ─── Заголовок события ─── -->
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
    <p class="text-xs sm:text-sm font-semibold uppercase tracking-widest text-[var(--color-od-accent)] text-center mb-3">Расписание мероприятия</p>
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
      <div class="flex-1">
        <h1 class="text-2xl sm:text-3xl font-semibold leading-tight tracking-tight">IX Всероссийский слёт молодёжи ВЭП</h1>
        <p class="text-sm sm:text-base text-[var(--color-od-muted)] mt-1.5 max-w-xl leading-relaxed">
          «Пятый элемент: В поисках смыслов» — программа для активной молодёжи энергетической отрасли
        </p>
        <div class="flex items-center gap-1.5 mt-2 text-sm text-[var(--color-od-muted)]">
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="10" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.4 8 12 8 12s8-6.6 8-12a8 8 0 0 0-8-8z"/></svg>
          <span><span class="font-medium text-[var(--color-od-fg)]">Cosmos Stay Le Rond Sochi</span>, Сочи, п.г.т. Дагомыс, ул. Ленинградская, 7А</span>
        </div>
      </div>
      <!-- Кнопка Добавить всё в календарь -->
      <div class="shrink-0 relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
        <button @click="open = !open"
          class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg border border-[var(--color-od-border)] text-[var(--color-od-fg)] hover:bg-[var(--color-od-surface)] transition-colors cursor-pointer whitespace-nowrap">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Добавить в календарь
        </button>
        <div x-show="open" x-cloak class="cal-dropdown" @click="open = false">
          <a href="#" @click.prevent="exportAllToCalendar('current')">Текущий день</a>
          <a href="#" @click.prevent="exportAllToCalendar('all')">Все дни</a>
        </div>
      </div>
    </div>
  </div>

  <!-- ─── Демо-режим: переключатель для просмотра всех состояний ─── -->
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-1">
    <div class="flex items-center gap-3 py-2 px-4 rounded-lg bg-[var(--color-od-warm)] border border-[var(--color-od-border)] text-xs text-[var(--color-od-muted)]">
      <span>Режим просмотра:</span>
      <template x-for="(d, i) in days" :key="i">
        <button @click="setDemoDay(i)"
          :class="demoDay === i ? 'bg-[var(--color-od-accent)] text-white font-medium' : 'hover:bg-white/60'"
          class="px-2.5 py-1 rounded-full text-xs transition-colors cursor-pointer"
          x-text="'День ' + (i + 1)"></button>
      </template>
      <button @click="setDemoDay(-1)"
        :class="demoDay === -1 ? 'bg-[var(--color-od-accent)] text-white font-medium' : 'hover:bg-white/60'"
        class="px-2.5 py-1 rounded-full text-xs transition-colors cursor-pointer">Реальное время</button>
      <span class="text-[10px] text-[var(--color-od-past)]">(для предпросмотра состояний)</span>
    </div>
  </div>

  <!-- ─── Горизонтальные табы ─── -->
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-0">
    <div class="flex gap-1 overflow-x-auto scrollbar-thin pb-2" role="tablist">
      <template x-for="(day, i) in days" :key="i">
        <button @click="currentDay = i; updateTimeline()" role="tab"
          :aria-selected="currentDay === i"
          :class="getDayTabClass(i)"
          class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all cursor-pointer shrink-0">
          <span x-text="'День ' + (i + 1)"></span>
          <span class="text-xs opacity-70" x-text="day.dateShort"></span>
          <template x-if="isDayPast(i)">
            <svg class="w-4 h-4 text-[var(--color-od-green)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          </template>
        </button>
      </template>
    </div>
  </div>

  <!-- ─── Таймлайн ─── -->
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="relative" x-ref="timelineContainer">
      <!-- Вертикальная линия градиент -->
      <div class="timeline-line rounded-full"
        :style="{
          background: timelineGradient()
        }"></div>

      <!-- События дня -->
      <template x-if="currentEvents.length === 0">
        <div class="py-16 text-center">
          <p class="text-[var(--color-od-muted)] text-sm">На этот день события не запланированы</p>
        </div>
      </template>

      <template x-for="(evt, idx) in currentEvents" :key="idx">
        <div class="flex items-start gap-5 pb-2 relative">
          <!-- Точка на линии -->
          <div class="relative shrink-0 z-10" style="margin-left: 14px; width: 20px;">
            <!-- Точка -->
            <div
              :class="getDotClass(evt)"
              class="w-[18px] h-[18px] rounded-full border-[3px] transition-all duration-300 relative"
              :style="getDotStyle(evt)"
            ></div>
          </div>

          <!-- Карточка события -->
          <div class="flex-1 min-w-0 pb-4"
            :class="getCardClass(evt)">
            <div class="px-4 sm:px-5 py-4 rounded-xl border transition-colors duration-300"
              :class="getCardStyle(evt)">

              <!-- Верхняя строка: время + бейдж "Идёт сейчас" + кнопка календарь -->
              <div class="flex items-center justify-between gap-2 mb-1.5">
                <div class="flex items-center gap-2.5 flex-wrap">
                  <span class="text-xs sm:text-sm font-mono font-medium tabular-nums text-[var(--color-od-muted)]" x-text="evt.time"></span>
                  <template x-if="isEventCurrent(evt)">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-[var(--color-od-green-soft)] text-[var(--color-od-green)] text-[11px] font-semibold pulse-badge">
                      <span class="w-1.5 h-1.5 rounded-full bg-[var(--color-od-green)]"></span>
                      Идёт сейчас
                    </span>
                  </template>
                </div>
                <!-- Кнопка календаря для события -->
                <div class="relative shrink-0" x-data="{ calOpen: false }" @click.outside="calOpen = false" @keydown.escape="calOpen = false">
                  <button @click="calOpen = !calOpen"
                    :class="isEventPast(evt) ? 'opacity-30 hover:opacity-60' : 'opacity-60 hover:opacity-100'"
                    class="p-1.5 rounded-lg transition-opacity cursor-pointer text-[var(--color-od-muted)] hover:text-[var(--color-od-accent)]" title="Добавить в календарь">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                  </button>
                  <div x-show="calOpen" x-cloak class="cal-dropdown" @click="calOpen = false">
                    <a :href="generateCalUrl(evt, 'apple')" target="_blank">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C4.79 17.12 5.25 11.14 8.7 9.2c1.18-.78 2.3-.72 3.14-.12 1.14.82 1.5.72 2.58 0 .76-.5 1.6-.72 2.38-.58 1.14.2 1.96.82 2.52 1.66-2.24 1.33-1.76 4.28.38 5.28-.28.72-.6 1.44-1.05 2.04l-.6.8zM12.03 9.12c-.14-1.72 1.04-3.2 2.66-3.32.16 1.7-1.06 3.2-2.66 3.32z"/></svg>
                      Apple Календарь
                    </a>
                    <a :href="generateCalUrl(evt, 'google')" target="_blank">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                      Google Календарь
                    </a>
                    <a :href="generateCalUrl(evt, 'outlook')" target="_blank">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7.5 3C4.46 3 2 5.46 2 8.5S4.46 14 7.5 14 13 11.54 13 8.5 10.54 3 7.5 3zM7.5 11C6.12 11 5 9.88 5 8.5S6.12 6 7.5 6 10 7.12 10 8.5 8.88 11 7.5 11zM21 6h-8v4h8V6zM21 14h-8v4h8v-4zM13 20v-4H6v4h7z"/></svg>
                      Outlook
                    </a>
                    <a :href="generateCalUrl(evt, 'ics')" download>
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                      .ics файл
                    </a>
                    <a :href="generateCalUrl(evt, 'qr')" @click.prevent="showQr(evt)">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                      QR-код
                    </a>
                  </div>
                </div>
              </div>

              <!-- Название события -->
              <h3 class="text-sm sm:text-base font-semibold leading-snug" x-text="evt.title"></h3>

              <!-- Если есть иконка -->
              <template x-if="evt.icon">
                <span class="inline-block mt-1.5 text-lg" x-text="evt.icon"></span>
              </template>

              <!-- Спикер -->
              <template x-if="evt.speaker">
                <p class="text-xs sm:text-sm text-[var(--color-od-muted)] mt-1 flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <span x-text="evt.speaker"></span>
                </p>
              </template>

              <!-- Место -->
              <template x-if="evt.location">
                <p class="text-xs sm:text-sm text-[var(--color-od-muted)] mt-1 flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  <span x-text="evt.location"></span>
                </p>
              </template>

              <!-- Описание (детали) -->
              <template x-if="evt.detail">
                <p class="text-xs text-[var(--color-od-muted)] mt-1.5 leading-relaxed italic" x-text="evt.detail"></p>
              </template>
            </div>
          </div>
        </div>
      </template>

      <!-- Конечная точка линии -->
      <div class="flex items-start gap-5 pb-2 relative" x-show="currentEvents.length > 0">
        <div class="relative shrink-0 z-10" style="margin-left: 14px; width: 20px;">
          <div class="w-[10px] h-[10px] rounded-full bg-[var(--color-od-border)] border-2 border-white mt-1"></div>
        </div>
        <div class="flex-1 min-w-0 py-1"></div>
      </div>
    </div>
  </div>

</div>

<script>
function scheduleApp() {
  return {
    // ─── Данные ───
    days: [
      {
        label: 'День заезда',
        dateFull: '28 сентября 2026',
        dateShort: '28 сен',
        date: new Date(2026, 8, 28),
        events: [
          { time: '09:00–19:00', title: 'Регистрация участников. Подготовка и выдача набора участника и программы', icon: '📋', speaker: '', location: 'Зал «Меркурий»', detail: '' },
          { time: 'c 14:00', title: 'Заезд участников', icon: '🚗', speaker: '', location: 'Ресепшен отеля', detail: '' },
          { time: '15:00–16:00', title: 'Подготовка к презентации делегаций федеральных округов. Совещание с наставниками групп', icon: '📝', speaker: '', location: 'Залы «Сатурн», «Венера», «Юпитер», Патио 1, Патио 2', detail: 'Домашняя заготовка' },
          { time: '18:00–19:00', title: 'Ужин', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '150 участников' },
          { time: '19:00–21:00', title: 'Открытие слета. Вечер знакомств. Презентация делегаций профсоюзной молодёжи федеральных округов', icon: '🎤', speaker: '', location: 'Зал «Юпитер»', detail: '150 участников' }
        ]
      },
      {
        label: 'Образовательный день',
        dateFull: '29 сентября 2026',
        dateShort: '29 сен',
        date: new Date(2026, 8, 29),
        events: [
          { time: '07:00–07:30', title: 'Зарядка (по индивидуальному плану)', icon: '🏃', speaker: '', location: 'Территория отеля', detail: '' },
          { time: '08:00–09:30', title: 'Завтрак', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '09:30–13:00', title: 'Образовательная площадка №1: «Основы эффективных переговоров»', icon: '📚', speaker: 'Свищева Елена – юрист, медиатор', location: 'Зал «Юпитер» левый сектор', detail: 'Команда №1 (ProВОДА)' },
          { time: '09:30–13:00', title: 'Образовательная площадка №2: «Цифровая трансформация и ИИ»', icon: '💻', speaker: 'Маторкин Владислав – ген. директор CLONAR', location: 'Зал «Юпитер» центральный сектор', detail: 'Команда №2 (ProДВИЖЕНИЕ)' },
          { time: '09:30–13:00', title: 'Образовательная площадка №3: «Техносферная безопасность»', icon: '🛡️', speaker: 'Дмитрий Васильев – Центр «ПРОФИ»', location: 'Зал «Юпитер» правый сектор', detail: 'Команда №3 (ProГРЭС)' },
          { time: '09:30–13:00', title: 'Образовательная площадка №4: «Психология успеха»', icon: '🧠', speaker: 'Аида Гамидова – директор АНО «МЦ НЛП»', location: 'Зал «Сатурн»', detail: 'Команда №4 (ProЖАРКА)' },
          { time: '13:00–14:30', title: 'Обед', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '14:30–18:00', title: 'Образовательная площадка №1 (Команда №2 — ProДВИЖЕНИЕ)', icon: '📚', speaker: '', location: 'Зал «Юпитер» левый сектор', detail: 'Смена площадок через ½ дня' },
          { time: '14:30–18:00', title: 'Образовательная площадка №2 (Команда №3 — ProГРЭС)', icon: '💻', speaker: '', location: 'Зал «Юпитер» центральный сектор', detail: 'Смена площадок через ½ дня' },
          { time: '14:30–18:00', title: 'Образовательная площадка №3 (Команда №4 — ProЖАРКА)', icon: '🛡️', speaker: '', location: 'Зал «Юпитер» правый сектор', detail: 'Смена площадок через ½ дня' },
          { time: '14:30–18:00', title: 'Образовательная площадка №4 (Команда №1 — ProВОДА)', icon: '🧠', speaker: '', location: 'Зал «Сатурн»', detail: 'Смена площадок через ½ дня' },
          { time: '18:00–19:00', title: 'Время для работы над индивидуальными заданиями', icon: '✏️', speaker: '', location: 'Территория отеля', detail: '' },
          { time: '19:00–21:00', title: 'Ужин', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' }
        ]
      },
      {
        label: 'Образовательный день',
        dateFull: '30 сентября 2026',
        dateShort: '30 сен',
        date: new Date(2026, 8, 30),
        events: [
          { time: '07:00–07:30', title: 'Зарядка (по индивидуальному плану)', icon: '🏃', speaker: '', location: 'Территория отеля', detail: '' },
          { time: '08:00–09:30', title: 'Завтрак', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '09:30–13:00', title: 'Образовательная площадка №1 (Команда №3 — ProГРЭС)', icon: '📚', speaker: '', location: 'Зал «Юпитер» левый сектор', detail: 'Смена площадок через ½ дня' },
          { time: '09:30–13:00', title: 'Образовательная площадка №2 (Команда №4 — ProЖАРКА)', icon: '💻', speaker: '', location: 'Зал «Юпитер» центральный сектор', detail: 'Смена площадок через ½ дня' },
          { time: '09:30–13:00', title: 'Образовательная площадка №3 (Команда №1 — ProВОДА)', icon: '🛡️', speaker: '', location: 'Зал «Юпитер» правый сектор', detail: 'Смена площадок через ½ дня' },
          { time: '09:30–13:00', title: 'Образовательная площадка №4 (Команда №2 — ProДВИЖЕНИЕ)', icon: '🧠', speaker: '', location: 'Зал «Сатурн»', detail: 'Смена площадок через ½ дня' },
          { time: '13:00–14:30', title: 'Обед', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '14:30–18:00', title: 'Образовательная площадка №1 (Команда №4 — ProЖАРКА)', icon: '📚', speaker: '', location: 'Зал «Юпитер» левый сектор', detail: 'Смена площадок через ½ дня' },
          { time: '14:30–18:00', title: 'Образовательная площадка №2 (Команда №1 — ProВОДА)', icon: '💻', speaker: '', location: 'Зал «Юпитер» центральный сектор', detail: 'Смена площадок через ½ дня' },
          { time: '14:30–18:00', title: 'Образовательная площадка №3 (Команда №2 — ProДВИЖЕНИЕ)', icon: '🛡️', speaker: '', location: 'Зал «Юпитер» правый сектор', detail: 'Смена площадок через ½ дня' },
          { time: '14:30–18:00', title: 'Образовательная площадка №4 (Команда №3 — ProГРЭС)', icon: '🧠', speaker: '', location: 'Зал «Сатурн»', detail: 'Смена площадок через ½ дня' },
          { time: '18:00–19:00', title: 'Время на подготовку индивидуальных заданий в группах', icon: '✏️', speaker: '', location: 'Территория отеля', detail: '' },
          { time: '19:00–21:00', title: 'Ужин', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' }
        ]
      },
      {
        label: 'Спортивно-творческий',
        dateFull: '1 октября 2026',
        dateShort: '1 окт',
        date: new Date(2026, 9, 1),
        events: [
          { time: '07:00–07:30', title: 'Зарядка (по индивидуальному плану)', icon: '🏃', speaker: '', location: 'Территория отеля', detail: '' },
          { time: '08:00–09:30', title: 'Завтрак', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '09:30–11:00', title: 'Панельная дискуссия «Престиж-встреча: Социальное партнерство вчера, сегодня, завтра»', icon: '🎙️', speaker: '', location: 'Зал «Юпитер»', detail: '150 участников' },
          { time: '11:15–13:00', title: 'Творческая площадка. Представление визиток участников. Презентации заданий команд', icon: '🎭', speaker: '', location: 'Зал «Юпитер»', detail: '150 участников' },
          { time: '13:00–14:00', title: 'Обед', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '14:15', title: 'Убытие участников на территорию спортивного комплекса', icon: '🚌', speaker: '', location: '', detail: '' },
          { time: '14:30–18:00', title: 'Спортивные мероприятия: мини-футбол, волейбол, плавание (эстафета)', icon: '⚽', speaker: '', location: 'Спорт. база отеля «Дагомыс»', detail: '150 участников' },
          { time: '19:00–23:00', title: 'Подведение итогов. Награждение победителей. Закрытие IX ВСМ ВЭП. Товарищеский ужин', icon: '🏆', speaker: '', location: 'Зал «Юпитер»', detail: '150 участников' }
        ]
      },
      {
        label: 'Экскурсионный',
        dateFull: '2 октября 2026',
        dateShort: '2 окт',
        date: new Date(2026, 9, 2),
        events: [
          { time: '07:00–07:30', title: 'Зарядка (по индивидуальному плану)', icon: '🏃', speaker: '', location: 'Территория отеля', detail: '' },
          { time: '08:00–09:30', title: 'Завтрак', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '10:00–14:00', title: 'Организованный отъезд участников на экскурсию', icon: '🚌', speaker: '', location: '', detail: '150 участников' },
          { time: '14:00–15:00', title: 'Обед', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: '15:00–18:00', title: 'Свободное время', icon: '☕', speaker: '', location: '', detail: '' },
          { time: '18:00', title: 'Ужин', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' }
        ]
      },
      {
        label: 'Отъезд',
        dateFull: '3 октября 2026',
        dateShort: '3 окт',
        date: new Date(2026, 9, 3),
        events: [
          { time: '08:00–10:00', title: 'Завтрак', icon: '🍽️', speaker: '', location: 'Ресторан отеля', detail: '' },
          { time: 'До 12:00', title: 'Отъезд участников', icon: '🚗', speaker: '', location: '', detail: '' }
        ]
      }
    ],

    currentDay: 0,
    demoDay: -1,
    now: new Date(),

    // ─── Геттеры ───
    get currentEvents() {
      return this.days[this.currentDay]?.events || [];
    },

    // ─── Инициализация ───
    init() {
      // Определяем текущий день по реальному времени
      this.updateNow();
      this.selectCurrentDay();
    },

    updateNow() {
      this.now = new Date();
    },

    selectCurrentDay() {
      const today = new Date(this.now.getFullYear(), this.now.getMonth(), this.now.getDate());
      for (let i = this.days.length - 1; i >= 0; i--) {
        if (this.days[i].date <= today) {
          // Если мы находимся в период события, выбираем последний доступный день
          // Проверяем, что разница не более 7 дней (событие длится 6 дней)
          const diffDays = Math.floor((today - this.days[i].date) / (1000 * 60 * 60 * 24));
          if (i === this.days.length - 1 || diffDays < 10) {
            this.currentDay = i;
            return;
          }
        }
      }
    },

    updateTimeline() {
      // Триггер перерисовки
    },

    setDemoDay(index) {
      this.demoDay = index;
      if (index === -1) {
        this.updateNow();
        this.selectCurrentDay();
      } else {
        const day = this.days[index];
      const demoTimes = [20, 11, 15, 14, 17, 10]; // час дня для каждого дня демо
      const hour = demoTimes[index] ?? 14;
      this.now = new Date(day.date.getFullYear(), day.date.getMonth(), day.date.getDate(), hour, 0, 0);
      this.currentDay = index;
        // Пересчитываем события
        this.updateTimeline();
      }
    },

    // ─── Статусы дней ───
    isDayPast(dayIndex) {
      const dayEnd = new Date(this.days[dayIndex].date);
      dayEnd.setHours(23, 59, 59, 999);
      return this.now > dayEnd;
    },

    isDayCurrent(dayIndex) {
      const dayStart = new Date(this.days[dayIndex].date);
      dayStart.setHours(0, 0, 0, 0);
      const dayEnd = new Date(this.days[dayIndex].date);
      dayEnd.setHours(23, 59, 59, 999);
      return this.now >= dayStart && this.now <= dayEnd;
    },

    // ─── Статусы событий ───
    _parseTime(str, day) {
      if (!str) return null;
      str = str.replace(/^c\s*/i, '').replace(/^до\s*/i, '').trim();
      if (!str) return null;
      const parts = str.split(':');
      const h = parseInt(parts[0], 10);
      const m = parts[1] ? parseInt(parts[1], 10) : 0;
      if (isNaN(h)) return null;
      const d = new Date(day);
      d.setHours(h, m, 0, 0);
      return d;
    },

    getEventEnd(evt) {
      const day = this.days[this.currentDay].date;
      const parts = evt.time.split('–');
      const endStr = parts.length > 1 ? parts[1].trim() : null;

      const end = this._parseTime(endStr, day);
      if (end) return end;

      const start = this._parseTime(parts[0].trim(), day);
      if (start) return new Date(start.getTime() + 60 * 60 * 1000);
      
      return null;
    },

    getEventStart(evt) {
      const day = this.days[this.currentDay].date;
      const startStr = evt.time.split('–')[0].trim();
      return this._parseTime(startStr, day);
    },

    isEventPast(evt) {
      const end = this.getEventEnd(evt);
      if (!end) return false;
      return this.now > end;
    },

    isEventCurrent(evt) {
      const start = this.getEventStart(evt);
      const end = this.getEventEnd(evt);
      if (!start || !end) return false;
      return this.now >= start && this.now <= end;
    },

    isEventFuture(evt) {
      const start = this.getEventStart(evt);
      if (!start) return true;
      return this.now < start;
    },

    // ─── Стилизация ───
    getDayTabClass(dayIndex) {
      const isPast = this.isDayPast(dayIndex);
      const isCurrent = this.isDayCurrent(dayIndex);
      const isSelected = this.currentDay === dayIndex;

      if (isSelected) return 'bg-[var(--color-od-accent)] text-white shadow-sm';
      if (isPast) return 'text-[var(--color-od-past)] opacity-60 hover:opacity-80';
      return 'text-[var(--color-od-muted)] hover:bg-[var(--color-od-surface)] hover:text-[var(--color-od-fg)]';
    },

    getDotClass(evt) {
      if (this.isEventCurrent(evt)) return 'pulse-green';
      if (this.isEventPast(evt)) return 'opacity-40';
      return '';
    },

    getDotStyle(evt) {
      if (this.isEventCurrent(evt)) {
        return { background: 'var(--color-od-green)', borderColor: 'var(--color-od-green)' };
      }
      if (this.isEventPast(evt)) {
        return { background: 'var(--color-od-past)', borderColor: 'var(--color-od-past)' };
      }
      return { background: 'white', borderColor: 'var(--color-od-accent)' };
    },

    getCardClass(evt) {
      if (this.isEventCurrent(evt)) return '';
      return '';
    },

    getCardStyle(evt) {
      if (this.isEventCurrent(evt)) {
        return 'border-[var(--color-od-green)] bg-[var(--color-od-green-soft)] shadow-[0_0_0_1px_var(--color-od-green)]';
      }
      if (this.isEventPast(evt)) {
        return 'border-[var(--color-od-border)] bg-white opacity-[0.55]';
      }
      return 'border-[var(--color-od-border)] bg-white hover:shadow-sm';
    },

    // ─── Градиент линии ───
    timelineGradient() {
      const events = this.currentEvents;
      if (!events || events.length === 0) return `linear-gradient(to bottom, var(--color-od-border), var(--color-od-border))`;

      // Считаем соотношение прошлых/будущих событий
      let pastCount = 0;
      let currentIndex = -1;
      events.forEach((evt, i) => {
        if (this.isEventPast(evt)) pastCount++;
        if (this.isEventCurrent(evt)) currentIndex = i;
      });

      const total = events.length;
      
      if (pastCount === total) {
        // Все прошло
        return `linear-gradient(to bottom, var(--color-od-past), var(--color-od-past))`;
      }

      if (currentIndex >= 0) {
        // Есть текущее событие
        const pastPct = Math.round((currentIndex / total) * 100);
        const currentPct = Math.round(((currentIndex + 0.5) / total) * 100);
        return `linear-gradient(to bottom, var(--color-od-past) 0%, var(--color-od-past) ${pastPct}%, var(--color-od-green) ${pastPct}%, var(--color-od-green) ${currentPct}%, var(--color-od-accent) ${currentPct}%, var(--color-od-accent) 100%)`;
      }

      if (pastCount > 0) {
        // Некоторые прошли, некоторые будущие
        const pct = Math.round((pastCount / total) * 100);
        return `linear-gradient(to bottom, var(--color-od-past) 0%, var(--color-od-past) ${pct}%, var(--color-od-accent) ${pct}%, var(--color-od-accent) 100%)`;
      }

      // Все будущие
      return `linear-gradient(to bottom, var(--color-od-accent), var(--color-od-accent))`;
    },

    // ─── Календарь ───
    generateCalUrl(evt, type) {
      const day = this.days[this.currentDay];
      const d = day.date;
      const y = d.getFullYear();
      const m = String(d.getMonth() + 1).padStart(2, '0');
      const dd = String(d.getDate()).padStart(2, '0');
      const dateStr = `${y}${m}${dd}`;

      const parts = evt.time.split('–');
      const startRaw = (parts[0] || '').replace(/^c\s*/i, '').replace(/^до\s*/i, '').trim();
      const endRaw = parts[1] ? parts[1].replace(/^c\s*/i, '').replace(/^до\s*/i, '').trim() : '';

      const fmtTime = (raw) => {
        if (!raw) return '090000';
        const [h, mi] = raw.split(':').map(Number);
        if (isNaN(h)) return '090000';
        return `${String(h).padStart(2, '0')}${String(isNaN(mi) ? 0 : mi).padStart(2, '0')}00`;
      };

      const startTime = fmtTime(startRaw);
      const endTime = fmtTime(endRaw || startRaw);
      const title = encodeURIComponent(evt.title);
      const loc = evt.location ? encodeURIComponent(evt.location) : '';
      const desc = evt.speaker ? encodeURIComponent('Спикер: ' + evt.speaker) : '';

      if (type === 'google') {
        return `https://www.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${dateStr}T${startTime}Z/${dateStr}T${endTime}Z&details=${desc}&location=${loc}`;
      }
      return '#';
    },

    exportAllToCalendar(mode) {
      if (mode === 'current') {
        alert('Экспорт текущего дня в календарь — нажмите на иконку календаря рядом с нужным событием');
      } else {
        alert('Экспорт всех дней — генерация .ics файла со всеми событиями');
      }
    },

    showQr(evt) {
      alert('QR-код для события: ' + evt.title + '\n(в production будет сгенерирован QR-код со ссылкой на добавление в календарь)');
    }
  };
}
</script>

</body>
</html>
