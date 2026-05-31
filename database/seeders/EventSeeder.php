<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventDay;
use App\Models\EventFaq;
use App\Models\EventGuest;
use App\Models\EventSpeaker;
use App\Models\ScheduleEvent;
use App\Models\Speaker;
use App\Models\Guest;
use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём мероприятие
        $event = Event::create([
            'title' => 'IX Всероссийский слёт молодёжи',
            'slug' => 'ix-vserossiiskii-slet-molodezi',
            'description' => '<p>Крупнейшее ежегодное мероприятие для молодых профессионалов строительной отрасли. Программа включает пленарные сессии, мастер-классы, нетворкинг и выставочную зону.</p><p>В 2026 году слёт пройдёт в гибридном формате с возможностью участия как очно, так и дистанционно.</p>',
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-06',
            'daily_start_time' => '09:00',
            'daily_end_time' => '18:00',
            'timezone' => 'Europe/Moscow',
            'status' => 'published',
            'venue_name' => 'Центральный выставочный комплекс «Экспоцентр»',
            'venue_address' => 'Москва, Краснопресненская наб., 14',
            'venue_lat' => 55.753215,
            'venue_lng' => 37.542565,
            'venue_how_to_get' => 'Метро: Выставочная, Деловой центр. Автобусы: м2, м27, м29, 12ц. Парковка: подземный паркинг на 2000 мест.',
            'registration_type' => 'yandex',
            'is_registration_open' => true,
            'contact_email' => 'info@landing.test',
            'contact_phone' => '+7 (495) 123-45-67',
        ]);

        // Привязываем спикеров
        $speakers = Speaker::all();
        foreach ($speakers as $index => $speaker) {
            EventSpeaker::create([
                'event_id' => $event->id,
                'speaker_id' => $speaker->id,
                'is_keynote' => $index === 0,
                'sort_order' => $index,
            ]);
        }

        // Привязываем гостей
        $guests = Guest::all();
        foreach ($guests as $index => $guest) {
            EventGuest::create([
                'event_id' => $event->id,
                'guest_id' => $guest->id,
                'sort_order' => $index,
            ]);
        }

        // Привязываем FAQ
        $faqs = Faq::all();
        foreach ($faqs as $index => $faq) {
            EventFaq::create([
                'event_id' => $event->id,
                'faq_id' => $faq->id,
                'sort_order' => $index,
            ]);
        }

        // Создаём дни расписания (понедельник-суббота)
        $dayLabels = ['День открытия', 'Инновации', 'Практика', 'Нетворкинг', 'Будущее', 'Закрытие'];
        $eventTitles = [
            ['Регистрация участников', 'Церемония открытия', 'Пленарная сессия', 'Кофе-брейк', 'Мастер-класс: Лидерство', 'Панельная дискуссия'],
            ['Утренняя йога', 'Цифровая трансформация', 'Кейс: Smart City', 'Обед', 'Воркшоп: BIM', 'Круглый стол'],
            ['Технологии строительства', 'Экскурсия на объект', 'Презентация проектов', 'Кофе-брейк', 'Питч-сессия стартапов', 'Награждение'],
            ['Молодёжный форум', 'Скоростные свидания (нетворкинг)', 'Ланч с экспертами', 'Интерактивная зона', 'Тематические треки', 'Вечерний приём'],
            ['Тренды 2030', 'AI в строительстве', 'Экологичные материалы', 'Обед', 'Хакатон: финал', 'Закрытая сессия'],
            ['Итоговая конференция', 'Подписание соглашений', 'Финальный кофе-брейк', 'Церемония закрытия', 'Фуршет', 'Фото на память'],
        ];

        $icons = ['📝', '🎤', '💡', '☕', '🛠️', '🎯'];
        $colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#a855f7'];

        for ($dayIndex = 0; $dayIndex < 6; $dayIndex++) {
            $date = Carbon::parse('2026-06-01')->addDays($dayIndex);

            $eventDay = EventDay::create([
                'event_id' => $event->id,
                'date' => $date,
                'label' => $dayLabels[$dayIndex],
                'description' => 'Программа ' . $date->translatedFormat('j F'),
                'sort_order' => $dayIndex,
                'is_active' => true,
            ]);

            // 6 событий в день
            $times = [
                ['09:00', '10:00'],
                ['10:30', '11:30'],
                ['12:00', '13:00'],
                ['13:30', '14:30'],
                ['15:00', '16:30'],
                ['17:00', '18:00'],
            ];

            foreach ($times as $eventIndex => $time) {
                ScheduleEvent::create([
                    'event_day_id' => $eventDay->id,
                    'speaker_id' => ($dayIndex + $eventIndex) % $speakers->count() + 1,
                    'start_time' => $time[0],
                    'end_time' => $time[1],
                    'title' => $eventTitles[$dayIndex][$eventIndex],
                    'description' => 'Описание события: ' . $eventTitles[$dayIndex][$eventIndex] . '. Приглашаем всех участников!',
                    'icon' => $icons[$eventIndex],
                    'color' => $colors[$eventIndex],
                    'location' => 'Зал ' . ($eventIndex + 1),
                    'is_break' => in_array($eventIndex, [0, 3]),
                    'sort_order' => $eventIndex,
                ]);
            }
        }
    }
}
