<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-3 pt-4">
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

        @if($showResult)
            <div class="rounded-lg border p-4 {{ $importedCount > 0 ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : 'bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800' }}">
                <div class="space-y-1">
                    @if($importedCount > 0)
                        <p class="text-sm text-green-700 dark:text-green-400">
                            Импортировано: <strong>{{ $importedCount }}</strong>
                        </p>
                    @endif
                    @if($skippedCount > 0)
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            Пропущено (дубликаты / пустые): <strong>{{ $skippedCount }}</strong>
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
