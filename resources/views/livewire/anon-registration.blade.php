<div class="min-h-screen" style="background-color: var(--color-background);">
    <div class="max-w-2xl mx-auto px-6 py-12">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6 transition-colors font-medium" style="color: var(--color-primary);">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            На главную
        </a>

        <h1 class="text-2xl font-bold mb-8" style="color: var(--color-text);">Регистрация на {{ $event->title }}</h1>

        @if ($successMessage)
            <div class="mb-6 p-6 rounded-xl text-center" style="background-color: #d1fae5; border: 1px solid var(--color-success); color: #065f46;">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-success);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-lg font-semibold">{{ $successMessage }}</p>
            </div>
                @endif

                <button type="submit"
                    class="w-full py-4 px-6 rounded-xl font-semibold text-white transition-colors mt-6"
                    style="background-color: var(--color-primary); border-radius: var(--radius-btn);"
                    onmouseover="this.style.backgroundColor='var(--color-primary-hover)'"
                    onmouseout="this.style.backgroundColor='var(--color-primary)'"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Зарегистрироваться</span>
                    <span wire:loading>Отправка...</span>
                </button>
            </form>
        @endif
    </div>
</div>
