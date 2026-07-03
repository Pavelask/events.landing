<x-filament-panels::page>
    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Мероприятие</label>
            <select wire:model="eventId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                @foreach(\App\Models\Event::pluck('title', 'id') as $id => $title)
                    <option value="{{ $id }}">{{ $title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CSV файл</label>
            <input type="file" wire:model="csvFile" accept=".csv" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            @error('csvFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-sm text-gray-500">
            <p><strong>Формат CSV:</strong></p>
            <p>Обязательные колонки: <code>ID</code>, <code>Фамилия, Имя, Отчество</code>, <code>Номер телефона</code>, <code>Адрес электронной почты</code></p>
        </div>

        <div wire:loading.remove wire:target="importCsv">
            <x-filament::button wire:click="importCsv" icon="heroicon-o-arrow-up-tray">
                Импортировать
            </x-filament::button>
        </div>

        <div wire:loading wire:target="importCsv" class="text-gray-500">
            Загрузка...
        </div>

        @if($importedCount > 0 || $skippedCount > 0)
            <div class="rounded-lg bg-green-50 border border-green-200 p-4">
                <p class="text-green-800">Импортировано: {{ $importedCount }}</p>
                <p class="text-green-700">Пропущено (дубликаты/пустые): {{ $skippedCount }}</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
