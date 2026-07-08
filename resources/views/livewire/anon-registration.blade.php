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

        @if ($errorMessage)
            <div class="mb-6 p-4 rounded-xl" style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b;">
                {{ $errorMessage }}
            </div>
        @endif

        @if (!$submitted)
            <form wire:submit="submit" class="rounded-2xl p-8" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-card);">
                <input type="hidden" wire:model="formLoadedAt">

                <div style="position:absolute;left:-9999px" aria-hidden="true">
                    <input type="text" name="website" wire:model="honeypot" tabindex="-1" autocomplete="off">
                </div>

                @php
                    $nameErr = !empty($fieldErrors['formData.name']);
                    $emailErr = !empty($fieldErrors['formData.email']);
                    $phoneErr = !empty($fieldErrors['formData.phone']);
                @endphp

                {{-- ФИО --}}
                <div class="mb-7">
                    <label for="name" class="block text-sm font-semibold mb-2" style="color: {{ $nameErr ? '#ef4444' : 'var(--color-text)' }};">ФИО *</label>
                    <input type="text" id="name" wire:model="formData.name"
                        class="w-full px-4 py-3 rounded-xl"
                        style="border: {{ $nameErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="Введите имя"
                        @if($nameErr) data-err @endif>
                    @if($nameErr)
                        <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.name'] }}</p>
                    @endif
                </div>

                {{-- Email --}}
                <div class="mb-7">
                    <label for="email" class="block text-sm font-semibold mb-2" style="color: {{ $emailErr ? '#ef4444' : 'var(--color-text)' }};">Email *</label>
                    <input type="email" id="email" wire:model="formData.email"
                        class="w-full px-4 py-3 rounded-xl"
                        style="border: {{ $emailErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="email@example.com"
                        @if($emailErr) data-err @endif>
                    @if($emailErr)
                        <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.email'] }}</p>
                    @endif
                </div>

                {{-- Телефон --}}
                <div class="mb-7" x-data="{ phoneComplete: false, phoneVal: '' }">
                    <label for="phone" class="block text-sm font-semibold mb-2" style="color: {{ $phoneErr ? '#ef4444' : 'var(--color-text)' }};">Телефон</label>
                    <input type="text" id="phone" wire:model="formData.phone"
                        class="w-full px-4 py-3 rounded-xl"
                        style="border: {{ $phoneErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="+7 (999) 123-45-67"
                        x-on:input="
                            let digits = $el.value.replace(/[^0-9]/g, '');
                            if (digits.length > 0 && digits[0] === '8') digits = '7' + digits.substring(1);
                            if (digits.length > 0 && digits[0] !== '7') digits = '7' + digits;
                            let formatted = '+7';
                            if (digits.length > 1) formatted += ' (' + digits.substring(1, 4);
                            if (digits.length > 4) formatted += ') ' + digits.substring(4, 7);
                            if (digits.length > 7) formatted += '-' + digits.substring(7, 9);
                            if (digits.length > 9) formatted += '-' + digits.substring(9, 11);
                            $el.value = formatted;
                            phoneVal = formatted;
                            phoneComplete = digits.length >= 11;
                            $wire.set('formData.phone', formatted);
                        "
                        x-on:blur="
                            let digits = $el.value.replace(/[^0-9]/g, '');
                            if (digits.length > 0 && digits.length < 11) {
                                phoneComplete = false;
                                phoneVal = '';
                                $el.value = '';
                                $wire.set('formData.phone', '');
                            }
                        "
                        maxlength="18">
                    @if($phoneErr)
                        <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.phone'] }}</p>
                    @endif
                    <p x-show="!phoneComplete && phoneVal.length > 0" x-cloak class="mt-1 text-sm" style="color: #ef4444;">Введите полный номер телефона</p>
                    <p x-show="phoneVal.length === 0" x-cloak class="mt-1 text-sm" style="color: var(--color-text); opacity: 0.5;">Формат: +7 (999) 123-45-67</p>
                </div>

                {{-- Динамические вопросы --}}
                @foreach ($questions as $question)
                    @php $fieldErr = !empty($fieldErrors['formData.' . $question['slug']]); @endphp
                    <div class="mb-7">
                        <label class="block text-sm font-semibold mb-2" style="color: {{ $fieldErr ? '#ef4444' : 'var(--color-text)' }};">
                            {{ $question['label'] }}
                            @if ($question['required']) * @endif
                        </label>

                        @if ($question['type'] === 'text')
                            <input type="text" wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl"
                                style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                placeholder="Введите значение"
                                @if($fieldErr) data-err @endif>
                            @if($fieldErr)
                                <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'textarea')
                            <textarea wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl"
                                style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                rows="3"
                                placeholder="Введите текст"
                                @if($fieldErr) data-err @endif></textarea>
                            @if($fieldErr)
                                <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'select')
                            @if ($question['searchable'] ?? false)
                                <div x-data="{ search: '', open: false, selected: '' }" class="relative">
                                    <input type="hidden" wire:model="formData.{{ $question['slug'] }}">
                                    <div @click="open = !open" @click.outside="open = false"
                                        class="w-full px-4 py-3 rounded-xl cursor-pointer flex justify-between items-center"
                                        style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text);">
                                        <span x-text="selected || 'Выберите...'"></span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    <div x-show="open" x-transition
                                        class="absolute z-10 w-full mt-1 rounded-xl shadow-lg border overflow-hidden"
                                        style="background-color: var(--color-surface); border-color: var(--color-border);">
                                        <div class="p-2">
                                            <input type="text" x-model="search" placeholder="Поиск..."
                                                class="w-full px-3 py-2 rounded-lg border text-sm"
                                                style="border-color: var(--color-border); background-color: var(--color-surface); color: var(--color-text);">
                                        </div>
                                        <div class="max-h-[252px] overflow-y-auto">
                                            @foreach ($question['options'] ?? [] as $option)
                                                <div class="px-4 py-2 cursor-pointer hover:bg-gray-100 text-sm"
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
                                    class="w-full px-4 py-3 rounded-xl"
                                    style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                    @if($fieldErr) data-err @endif>
                                    <option value="">Выберите...</option>
                                    @foreach ($question['options'] ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @if($fieldErr)
                                <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'radio')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer border transition-colors hover:bg-gray-50"
                                        style="border-color: {{ $fieldErr ? '#ef4444' : 'var(--color-border)' }};">
                                        <input type="radio" name="question_{{ $question['slug'] }}"
                                            value="{{ $option }}" wire:model="formData.{{ $question['slug'] }}"
                                            class="w-4 h-4"
                                            style="accent-color: var(--color-primary);">
                                        <span style="color: var(--color-text);">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($fieldErr)
                                <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'checkbox')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer border transition-colors hover:bg-gray-50"
                                        style="border-color: {{ $fieldErr ? '#ef4444' : 'var(--color-border)' }};">
                                        <input type="checkbox" name="question_{{ $question['slug'] }}[]"
                                            value="{{ $option }}" wire:model="formData.{{ $question['slug'] }}"
                                            class="w-4 h-4"
                                            style="accent-color: var(--color-primary);">
                                        <span style="color: var(--color-text);">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($fieldErr)
                                <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif

                        @elseif ($question['type'] === 'date')
                            <input type="text" wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl"
                                style="border: {{ $fieldErr ? '2px solid #ef4444' : '1px solid var(--color-border)' }}; background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                placeholder="ДД.ММ.ГГГГ"
                                maxlength="10"
                                @if($fieldErr) data-err @endif
                                x-data
                                x-on:input="
                                    let v = $el.value.replace(/[^0-9]/g, '');
                                    if (v.length > 2) v = v.substring(0,2) + '.' + v.substring(2);
                                    if (v.length > 5) v = v.substring(0,5) + '.' + v.substring(5,9);
                                    $el.value = v.substring(0, 10);
                                    $wire.set('formData.{{ $question['slug'] }}', $el.value);
                                ">
                            @if($fieldErr)
                                <p class="mt-1 text-sm" style="color: #ef4444;">{{ $fieldErrors['formData.' . $question['slug']] }}</p>
                            @endif
                        @endif
                    </div>
                @endforeach

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

@script
<script>
    Livewire.hook('morph.updated', ({ el }) => {
        const form = el.querySelector('form');
        if (!form) return;
        const walker = document.createTreeWalker(form, NodeFilter.SHOW_ELEMENT);
        while (walker.nextNode()) {
            const node = walker.currentNode;
            if (node.hasAttribute('data-err') && (node.tagName === 'INPUT' || node.tagName === 'TEXTAREA' || node.tagName === 'SELECT')) {
                node.focus();
                node.scrollIntoView({ behavior: 'smooth', block: 'center' });
                break;
            }
        }
    });
</script>
@endscript
