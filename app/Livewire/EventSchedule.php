<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventDay;
use App\Models\ScheduleEvent;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
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

        // Загружаем все дни с событиями и спикерами из кэша или БД
        $this->days = Cache::remember(
            $this->getDaysCacheKey(),
            now()->addMinutes(30),
            fn() => $this->event->days()
                ->with(['events.speaker'])
                ->orderBy('sort_order')
                ->get()
        );

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
        
        // Если не нашли — загружаем из кэша или БД
        if (!$this->selectedDay) {
            $this->selectedDay = Cache::remember(
                $this->getDayEventsCacheKey($this->selectedDayId),
                now()->addHour(),
                fn() => EventDay::with(['events.speaker'])
                    ->where('event_id', $this->event->id)
                    ->where('id', $this->selectedDayId)
                    ->first()
            );
        }
    }

    /**
     * Инвалидация кэша при обновлении расписания
     * Вызывается из админ-панели после изменений
     */
    public static function invalidateCache(int $eventId): void
    {
        $cache = Cache::getFacadeRoot();
        $prefix = config('cache.prefix');
        
        // Инвалидируем кэш для всех дней мероприятия
        $pattern = "{$prefix}event_{$eventId}_days";
        self::invalidateByPattern($cache, $pattern);
        
        // Инвалидируем кэш для всех дней и событий
        $dayPattern = "{$prefix}event_{$eventId}_day_";
        self::invalidateByPattern($cache, $dayPattern);
        
        // Инвалидируем кэш событий
        $eventPattern = "{$prefix}event_{$eventId}_events_";
        self::invalidateByPattern($cache, $eventPattern);
    }

    /**
     * Инвалидация кэша по паттерну (для Redis)
     */
    protected static function invalidateByPattern($cache, string $pattern): void
    {
        if (config('cache.default') === 'redis') {
            try {
                $redis = $cache->store('redis')->driver()->connection();
                $keys = $redis->keys($pattern . '*');
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            } catch (\Throwable $e) {
                \Log::error('Cache invalidation error: ' . $e->getMessage());
            }
        } else {
            // Fallback для других драйверов
            Cache::tags(['schedule'])->flush();
        }
    }

    protected function getDaysCacheKey(): string
    {
        return "event_{$this->event->id}_days";
    }

    protected function getDayEventsCacheKey(int $dayId): string
    {
        return "event_{$this->event->id}_day_{$dayId}_events";
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

