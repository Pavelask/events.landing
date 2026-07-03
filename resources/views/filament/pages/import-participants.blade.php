<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Мероприятие --}}
        <x-filament::section heading="Мероприятие">
            <select wire:model="eventId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @foreach(\App\Models\Event::pluck('title', 'id') as $id => $title)
                    <option value="{{ $id }}">{{ $title }}</option>
                @endforeach
            </select>
        </x-filament::section>

        {{-- Загрузка файла --}}
        <x-filament::section heading="CSV файл">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <input
                        type="file"
                        wire:model="csvFile"
                        accept=".csv"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                    >
                </div>

                <div class="text-xs text-gray-400">
                    <p>Формат: <code class="bg-gray-100 px-1 rounded dark:bg-gray-600">ID</code>, <code class="bg-gray-100 px-1 rounded dark:bg-gray-600">Фамилия, Имя, Отчество</code>, <code class="bg-gray-100 px-1 rounded dark:bg-gray-600">Номер телефона</code>, <code class="bg-gray-100 px-1 rounded dark:bg-gray-600">Адрес электронной почты</code></p>
                </div>
            </div>
        </x-filament::section>

        {{-- Кнопка импорта --}}
        <div class="flex items-center gap-3">
            <div wire:loading.remove wire:target="importCsv">
                <x-filament::button wire:click="importCsv" icon="heroicon-o-arrow-up-tray" size="lg">
                    Импортировать участников
                </x-filament::button>
            </div>

            <div wire:loading wire:target="importCsv" class="flex items-center gap-2 text-gray-500">
                <x-heroicon-o-arrow-path class="h-5 w-5 animate-spin" />
                <span>Импорт...</span>
            </div>
        </div>

        {{-- Результат --}}
        @if($importedCount > 0 || $skippedCount > 0)
            <x-filament::section heading="Результат импорта">
                <div class="space-y-1">
                    <p class="text-sm text-success-600 dark:text-success-400">
                        <x-heroicon-o-check-circle class="inline h-4 w-4" />
                        Импортировано: <strong>{{ $importedCount }}</strong>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-information-circle class="inline h-4 w-4" />
                        Пропущено (дубликаты / пустые): <strong>{{ $skippedCount }}</strong>
                    </p>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
