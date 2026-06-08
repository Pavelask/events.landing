<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventDay;
use App\Models\ScheduleEvent;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class EventSchedule extends Component
{
    public ?Event $event = null;

    public int $selectedDayId = 0;

    public Collection $days;

    public ?EventDay $selectedDay = null;

    protected int $cacheTTL = 3600; // 1 час по умолчанию

    public function mount(Event|string|null $event = null, ?string $eventSlug = null): void
    {
        $this->event = $this->resolveEvent($event, $eventSlug);
        
        if (!$this->event) {
            $this->days = collect();
            return;
        }

        // Загружаем дни из БД (без кэширования Collection)
        $this->days = $this->event->days()
            ->with(['events.speaker'])
            ->orderBy('sort_order')
            ->get();

        $today = Carbon::today()->toDateString();
        $todayDay = $this->days->first(fn ($day): bool => $day->date->toDateString() === $today);
        $this->selectedDayId = $todayDay?->id ?? $this->days->first()?->id ?? 0;
        
        $this->loadSelectedDay();
    }

    public function selectDay(int $dayId): void
    {
        $this->selectedDayId = $dayId;
        $this->loadSelectedDay();
    }

    public function loadSelectedDay(): void
    {
        if ($this->selectedDayId <= 0 || !$this->event) {
            $this->selectedDay = null;
            return;
        }

        // Сначала ищем в уже загруженных данных
        $this->selectedDay = $this->days->find($this->selectedDayId);
        
        // Если не нашли — загружаем из БД
        if (!$this->selectedDay) {
            $this->selectedDay = EventDay::with(['events.speaker'])
                ->where('event_id', $this->event->id)
                ->where('id', $this->selectedDayId)
                ->first();
        }
    }

    /**
     * Инвалидация кэша при обновлении расписания
     * Вызывается из админ-панели после изменений
     */
    public static function invalidateCache(int $eventId): void
    {
        // Очищаем все кэшированные данные для мероприятия
        Cache::forget("event_{$eventId}_days");
        
        // Для Redis можно использовать паттерн-очистку
        if (config('cache.default') === 'redis') {
            try {
                $redis = Cache::store('redis')->driver()->connection();
                $pattern = "event_{$eventId}_*";
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            } catch (\Throwable $e) {
                \Log::error('Cache invalidation error: ' . $e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.event-schedule');
    }

    private function resolveEvent(Event|string|null $event, ?string $eventSlug): ?Event
    {
        if ($event instanceof Event) {
            return $event;
        }

        $slug = $eventSlug ?? (is_string($event) ? $event : null);

        if ($slug) {
            return Event::where('slug', $slug)->firstOrFail();
        }

        return Event::active()->first() ?? Event::upcoming()->orderBy('start_date')->first();
    }
}

