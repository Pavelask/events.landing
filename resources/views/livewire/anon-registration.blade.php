<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }
    .animate-slide-down {
        animation: slideDown 0.3s ease-out forwards;
    }
    .animate-pulse-slow {
        animation: pulse 1.5s ease-in-out infinite;
    }
    .field-stagger { opacity: 0; animation: fadeInUp 0.4s ease-out forwards; }
    .field-stagger:nth-child(1) { animation-delay: 0.05s; }
    .field-stagger:nth-child(2) { animation-delay: 0.10s; }
    .field-stagger:nth-child(3) { animation-delay: 0.15s; }
    .field-stagger:nth-child(4) { animation-delay: 0.20s; }
    .field-stagger:nth-child(5) { animation-delay: 0.25s; }
    .field-stagger:nth-child(6) { animation-delay: 0.30s; }
    .field-stagger:nth-child(7) { animation-delay: 0.35s; }
    .field-stagger:nth-child(8) { animation-delay: 0.40s; }
    .field-stagger:nth-child(9) { animation-delay: 0.45s; }
    .field-stagger:nth-child(10) { animation-delay: 0.50s; }
    .field-stagger:nth-child(11) { animation-delay: 0.55s; }
    .field-stagger:nth-child(12) { animation-delay: 0.60s; }
    .field-stagger:nth-child(13) { animation-delay: 0.65s; }
    .field-stagger:nth-child(14) { animation-delay: 0.70s; }
    .field-stagger:nth-child(15) { animation-delay: 0.75s; }
    .field-stagger:nth-child(16) { animation-delay: 0.80s; }
    .field-stagger:nth-child(17) { animation-delay: 0.85s; }
    .field-stagger:nth-child(18) { animation-delay: 0.90s; }
    .field-stagger:nth-child(19) { animation-delay: 0.95s; }
    .field-stagger:nth-child(20) { animation-delay: 1.00s; }
    .btn-hover {
        transition: all 0.2s ease;
    }
    .btn-hover:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-hover:active {
        transform: scale(0.98);
    }
    .input-focus {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .input-focus:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.1);
    }
    .error-shake {
        animation: shake 0.3s ease-in-out;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>

<div class="min-h-screen" style="background-color: var(--color-background);">
    <div class="max-w-2xl mx-auto px-6 py-12">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6 transition-colors font-medium animate-fade-in-up" style="color: var(--color-primary); animation-delay: 0.1s;">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            На главную
        </a>

        <h1 class="text-2xl font-bold mb-8 animate-fade-in-up" style="color: var(--color-text); animation-delay: 0.15s;">Регистрация на {{ $event->title }}</h1>

        @if ($successMessage)
            <div class="mb-6 p-6 rounded-xl text-center animate-slide-down" style="background-color: #d1fae5; border: 1px solid var(--color-success); color: #065f46;">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-success);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-lg font-semibold">{{ $successMessage }}</p>
            </div>
        @endif

        @if ($errorMessage)
            <div class="mb-6 p-4 rounded-xl animate-slide-down" style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b;">
                {{ $errorMessage }}
            </div>
        @endif

        @if (!$submitted)
            <form wire:submit="submit" class="rounded-2xl p-8 animate-fade-in-up" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-card); animation-delay: 0.2s;">
                <input type="hidden" wire:model="formLoadedAt">

                <div style="position:absolute;left:-9999px" aria-hidden="true">
                    <input type="text" name="website" wire:model="honeypot" tabindex="-1" autocomplete="off">
                </div>

                @php
                    $nameErr = !empty($fieldErrors['formData.name']);
                    $emailErr = !empty($fieldErrors['formData.email']);
                @endphp

                <div class="mb-7 field-stagger">
                    <label for="name" class="block text-sm font-semibold mb-2" style="color: {{ $nameErr ? '#ef4444' : 'var(--color-text)' }};">Имя *</label>
                    <input type="text" id="name" wire:model="formData.name"
                        class="w-full px-4 py-3 rounded-xl input-focus @if($nameErr) error-shake @endif"
                        style="border: {{ $nameErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="Введите имя"
                        required>
                    @if($nameErr)
                        <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.name'] }}</p>
                    @endif
                </div>

                <div class="mb-7 field-stagger">
                    <label for="email" class="block text-sm font-semibold mb-2" style="color: {{ $emailErr ? '#ef4444' : 'var(--color-text)' }};">Email *</label>
                    <input type="email" id="email" wire:model="formData.email"
                        class="w-full px-4 py-3 rounded-xl input-focus @if($emailErr) error-shake @endif"
                        style="border: {{ $emailErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="email@example.com"
                        required>
                    @if($emailErr)
                        <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.email'] }}</p>
                    @endif
                </div>

                <div class="mb-7 field-stagger">
                    <label for="phone" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Телефон</label>
                    <input type="text" id="phone" wire:model="formData.phone"
                        class="w-full px-4 py-3 rounded-xl border input-focus"
                        style="border-color: var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="+7 (999) 123-45-67">
                </div>

                @foreach ($questions as $question)
                    @php $fieldErr = !empty($fieldErrors['formData.' . $question['slug']]); @endphp
                    <div class="mb-7 field-stagger">
                        <label class="block text-sm font-semibold mb-2" style="color: {{ $fieldErr ? '#ef4444' : 'var(--color-text)' }};">
                            {{ $question['label'] }}
                            @if ($question['required']) * @endif
                        </label>

                        @if ($question['type'] === 'text')
                            <input type="text" wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl input-focus @if($fieldErr) error-shake @endif"
                                style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                placeholder="Введите значение"
                                @if ($question['required']) required @endif>
                            @if($fieldErr)
                                <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'textarea')
                            <textarea wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl input-focus @if($fieldErr) error-shake @endif"
                                style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                rows="3"
                                placeholder="Введите текст"
                                @if ($question['required']) required @endif></textarea>
                            @if($fieldErr)
                                <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'select')
                            @if ($question['searchable'] ?? false)
                                <div x-data="{ search: '', open: false, selected: '' }" class="relative" style="z-index: 50;">
                                    <input type="hidden" wire:model="formData.{{ $question['slug'] }}">
                                    <div @click="open = !open" @click.outside="open = false"
                                        class="w-full px-4 py-3 rounded-xl cursor-pointer flex justify-between items-center input-focus transition-all"
                                        style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text);">
                                        <span x-text="selected || 'Выберите...'"></span>
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    @if($fieldErr)
                                        <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                                    @endif
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-1" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="absolute z-[100] w-full mt-1 rounded-xl shadow-lg border overflow-hidden"
                                        style="background-color: var(--color-surface); border-color: var(--color-border);">
                                        <div class="p-2">
                                            <input type="text" x-model="search" placeholder="Поиск..."
                                                class="w-full px-3 py-2 rounded-lg border text-sm input-focus"
                                                style="border-color: var(--color-border); background-color: var(--color-surface); color: var(--color-text);">
                                        </div>
                                        <div class="max-h-[200px] overflow-y-auto">
                                            @foreach ($question['options'] ?? [] as $option)
                                                <div class="px-4 py-2 cursor-pointer hover:bg-gray-100 text-sm transition-colors"
                                                    x-show="'{{ $option }}'.toLowerCase().includes(search.toLowerCase())"
                                                    @click="selected = '{{ $option }}'; $wire.set('formData.{{ $question['slug'] }}', '{{ $option }}'); open = false; search = '';">
                                                    {{ $option }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <select wire:model="formData.{{ $question['slug'] }}"
                                    class="w-full px-4 py-3 rounded-xl input-focus @if($fieldErr) error-shake @endif"
                                    style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                    @if ($question['required']) required @endif>
                                    <option value="">Выберите...</option>
                                    @foreach ($question['options'] ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @if($fieldErr)
                                <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'radio')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer border transition-all hover:bg-gray-50 hover:scale-[1.01]"
                                        style="border-color: var(--color-border);">
                                        <input type="radio" name="question_{{ $question['slug'] }}"
                                            value="{{ $option }}" wire:model="formData.{{ $question['slug'] }}"
                                            class="w-4 h-4"
                                            style="accent-color: var(--color-primary);"
                                            @if ($question['required']) required @endif>
                                        <span style="color: var(--color-text);">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($fieldErr)
                                <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'checkbox')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer border transition-all hover:bg-gray-50 hover:scale-[1.01]"
                                        style="border-color: var(--color-border);">
                                        <input type="checkbox" name="question_{{ $question['slug'] }}[]"
                                            value="{{ $option }}" wire:model="formData.{{ $question['slug'] }}"
                                            class="w-4 h-4"
                                            style="accent-color: var(--color-primary);">
                                        <span style="color: var(--color-text);">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($fieldErr)
                                <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'date')
                            <input type="text" wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl input-focus @if($fieldErr) error-shake @endif"
                                style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                placeholder="ДД.ММ.ГГГГ"
                                maxlength="10"
                                x-data
                                x-on:input="
                                    let v = $el.value.replace(/[^0-9]/g, '');
                                    if (v.length > 2) v = v.substring(0,2) + '.' + v.substring(2);
                                    if (v.length > 5) v = v.substring(0,5) + '.' + v.substring(5,9);
                                    $el.value = v.substring(0, 10);
                                    $wire.set('formData.{{ $question['slug'] }}', $el.value);
                                "
                                @if ($question['required']) required @endif>
                            @if($fieldErr)
                                <p class="mt-1 text-sm animate-slide-down" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif
                        @endif
                    </div>
                @endforeach

                <button type="submit"
                    class="w-full py-4 px-6 rounded-xl font-semibold text-white transition-all btn-hover mt-8"
                    style="background-color: var(--color-primary); border-radius: var(--radius-btn);"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Зарегистрироваться</span>
                    <span wire:loading class="animate-pulse-slow">Отправка...</span>
                </button>
            </form>
        @endif
    </div>
</div>
