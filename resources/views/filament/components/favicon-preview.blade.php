@php
    $record = $getRecord();
    $hasFavicon = $record && $record->id && file_exists(storage_path("app/public/events/favicons/{$record->id}-32.png"));
@endphp

<div class="flex items-center justify-center gap-4 mt-2">
    @if($hasFavicon)
        <div class="flex items-center justify-center gap-6">
            <img src="{{ asset('storage/events/favicons/' . $record->id . '-32.png') }}" alt="Favicon 32x32" class="rounded border border-gray-200 dark:border-gray-700">
            <img src="{{ asset('storage/events/favicons/' . $record->id . '-180.png') }}" alt="Apple Touch Icon 180x180" class="rounded border border-gray-200 dark:border-gray-700" style="width: 64px; height: 64px;">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400">
                <div>32×32 favicon</div>
                <div>180×180 apple-touch-icon</div>
            </div>
        </div>
    @else
        <div class="text-sm text-gray-400 dark:text-gray-500 italic">
            Иконка не сгенерирована
        </div>
    @endif
</div>
