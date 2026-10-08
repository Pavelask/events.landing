@php
    $record = $getRecord();
    $url = trim((string) $record?->gallery_external_url);
    $slug = (string) $record?->slug;
    $qrUrl = ($url !== '' && $slug !== '') ? route('gallery.qr', $slug) : null;
@endphp

<div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
    @if ($qrUrl)
        <div class="flex flex-wrap items-center gap-6">
            <img
                src="{{ $qrUrl }}?v={{ rawurlencode($url) }}"
                alt="QR-код на внешнюю галерею"
                class="h-40 w-40 rounded border border-gray-200 bg-white p-1 dark:border-gray-700"
            >
            <div class="min-w-0 flex-1 text-sm">
                <div class="font-semibold text-gray-700 dark:text-gray-200">QR-код готов</div>
                <div class="mb-2 break-all text-xs text-gray-500 dark:text-gray-400">{{ $url }}</div>
                <div class="text-xs text-gray-400 dark:text-gray-500">
                    Распечатайте или вставьте на экран — QR всегда актуален для текущей ссылки.
                </div>
            </div>
        </div>
    @elseif ($url !== '' && $slug === '')
        <div class="text-sm text-gray-400 italic dark:text-gray-500">
            Сохраните мероприятие — после этого появится QR-код
        </div>
    @else
        <div class="text-sm text-gray-400 italic dark:text-gray-500">
            Укажите ссылку выше — здесь появится QR-код
        </div>
    @endif
</div>
