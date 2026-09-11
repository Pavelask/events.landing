<?php

use App\Models\Event;
use Illuminate\Support\Facades\Cache;

if (!function_exists('resolveActiveEvent')) {
    /**
     * Возвращает «активное» событие для главной страницы.
     *
     * Пункт 3 оптимизаций: до этого функция выполняла до 4 запросов
     * (published active, upcoming, recentlyCompleted, completed) с
     * тяжёлым eager-load на КАЖДЫЙ запрос главной страницы. Теперь
     * результат кэшируется на 10 минут; инвалидация происходит в
     * EventObserver (saved/deleted), потому что главная строится на
     * данных Event. Также в eager-load добавлены documents, которые
     * используются сразу под расписанием (пункт 3).
     */
    function resolveActiveEvent(): ?Event
    {
        $with = ['heroSlides', 'faqs', 'speakers', 'keynoteSpeakers', 'days.events.speaker', 'documents'];

        return Cache::remember('resolve_active_event', 600, function () use ($with) {
            return Event::published()->with($with)->active()->first()
                ?? Event::published()->with($with)->upcoming()->orderBy('start_date')->first()
                ?? Event::published()->with($with)->recentlyCompleted()->orderByDesc('end_date')->first()
                ?? Event::completed()->with($with)->orderByDesc('end_date')->first();
        });
    }
}

if (!function_exists('resolveEventContent')) {
    /**
     * Кэширует контентные выборки события (слайды, спикеры, гости),
     * чтобы не выполнять запросы на каждый рендер главной.
     * Инвалидация — в App\Observers\EventSlideObserver / EventSpeakerObserver / EventGuestObserver.
     */
    function resolveEventContent(Event $event): array
    {
        return Cache::remember('event_content_' . $event->id, 600, function () use ($event) {
            return [
                'slides' => $event->heroSlides()->where('is_active', true)->get(),
                'speakers' => $event->eventSpeakers()
                    ->where('is_visible', true)
                    ->with('speaker')
                    ->orderBy('sort_order')
                    ->get(),
                'guests' => $event->eventGuests()
                    ->where('is_visible', true)
                    ->with('guest')
                    ->orderBy('sort_order')
                    ->get(),
            ];
        });
    }
}

if (!function_exists('resolveTestimonials')) {
    /**
     * Кэширует видимые отзывы события.
     * Инвалидация — в App\Observers\EventTestimonialObserver.
     */
    function resolveTestimonials(Event $event): \Illuminate\Support\Collection
    {
        return Cache::remember('event_testimonials_' . $event->id, 600, function () use ($event) {
            return $event->eventTestimonials()
                ->where('is_visible', true)
                ->with(['testimonial' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->orderBy('sort_order')
                ->get()
                ->pluck('testimonial')
                ->filter();
        });
    }
}

if (!function_exists('clean_html')) {
    /**
     * Sanitize HTML: strip dangerous tags/attributes, keep safe formatting.
     * Allowed: p, br, strong, em, b, i, u, a, ul, ol, li, h2-h6, blockquote, pre, code, img, span, div.
     * Allowed attributes: href, src, alt, title, class, style (inline only), target, rel.
     */
    function clean_html(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }
        

        $allowedTags = [
            'p' => ['style'],
            'br' => [],
            'strong' => [],
            'b' => [],
            'em' => [],
            'i' => [],
            'u' => [],
            'a' => ['href', 'title', 'target', 'rel'],
            'ul' => [],
            'ol' => [],
            'li' => [],
            'h2' => ['style'],
            'h3' => ['style'],
            'h4' => [],
            'h5' => [],
            'h6' => [],
            'blockquote' => [],
            'pre' => [],
            'code' => [],
            'img' => ['src', 'alt', 'title', 'width', 'height'],
            'span' => ['class'],
            'div' => ['class'],
        ];

        $html = strip_tags($html, '<' . implode('><', array_keys($allowedTags)) . '>');

        $html = preg_replace_callback('/<([a-z][a-z0-9]*)(?:\s+([^>]*?))?>/i', function ($match) use ($allowedTags) {
            $tag = strtolower($match[1]);
            $attrs = $match[2] ?? '';

            if (!isset($allowedTags[$tag])) {
                return '';
            }

            $allowed = $allowedTags[$tag];
            $cleaned = preg_replace_callback('/(\w+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|(\S+)))?/', function ($m) use ($allowed) {
                $attr = strtolower($m[1]);
                if (!in_array($attr, $allowed)) {
                    return '';
                }
                $val = $m[2] ?? $m[3] ?? $m[4] ?? $attr;
                if ($attr === 'href' || $attr === 'src') {
                    if (preg_match('/^\s*(javascript|data|vbscript):/i', $val)) {
                        return '';
                    }
                }
                return $attr . '="' . htmlspecialchars($val, ENT_QUOTES, 'UTF-8') . '"';
            }, $attrs);

            return '<' . $tag . ($cleaned ? ' ' . $cleaned : '') . '>';
        }, $html);

        return $html;
    }
}
