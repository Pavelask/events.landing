<div class="min-h-screen" style="background-color: var(--color-background);">
    <div class="max-w-2xl mx-auto px-6 py-12">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6 transition-colors font-medium" style="color: var(--color-primary);">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            На главную
        </a>

        <h1 class="text-2xl font-bold mb-8" style="color: var(--color-text);">Регистрация на {{ $event->title }}</h1>

        @if ($successMessage)
            <div class="mb-6 p-6 rounded-xl text-center" style="background-color: #d1fae5; border: 1px solid #16a34a; color: #065f46;">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #16a34a;">
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
            <form wire:submit.prevent="submit" id="regForm" class="rounded-2xl p-8" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-card);">

                <div class="mb-7">
                    <label id="lbl-name" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Имя *</label>
                    <input type="text" wire:model="formData.name" id="field-name"
                        class="w-full px-4 py-3 rounded-xl"
                        style="border: 1px solid var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="Введите имя">
                    <p id="err-name" class="mt-1 text-sm" style="color: #ef4444; display:none;"></p>
                </div>

                <div class="mb-7">
                    <label id="lbl-email" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Email *</label>
                    <input type="email" wire:model="formData.email" id="field-email"
                        class="w-full px-4 py-3 rounded-xl"
                        style="border: 1px solid var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="email@example.com">
                    <p id="err-email" class="mt-1 text-sm" style="color: #ef4444; display:none;"></p>
                </div>

                <div class="mb-7">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Телефон</label>
                    <input type="text" wire:model="formData.phone"
                        class="w-full px-4 py-3 rounded-xl border"
                        style="border-color: var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;"
                        placeholder="+7 (999) 123-45-67">
                </div>

                @foreach ($questions as $question)
                    <div class="mb-7">
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                            {{ $question['label'] }}
                            @if ($question['required']) * @endif
                        </label>

                        @if ($question['type'] === 'text')
                            <input type="text" wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl"
                                style="border: 1px solid var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                placeholder="Введите значение">

                        @elseif ($question['type'] === 'textarea')
                            <textarea wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl"
                                style="border: 1px solid var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                rows="3"
                                placeholder="Введите текст"></textarea>

                        @elseif ($question['type'] === 'select')
                            <select wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl"
                                style="border: 1px solid var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;">
                                <option value="">Выберите...</option>
                                @foreach ($question['options'] ?? [] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>

                        @elseif ($question['type'] === 'radio')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer border transition-colors hover:bg-gray-50"
                                        style="border-color: var(--color-border);">
                                        <input type="radio"
                                            value="{{ $option }}" wire:model="formData.{{ $question['slug'] }}"
                                            class="w-4 h-4"
                                            style="accent-color: var(--color-primary);">
                                        <span style="color: var(--color-text);">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>

                        @elseif ($question['type'] === 'checkbox')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer border transition-colors hover:bg-gray-50"
                                        style="border-color: var(--color-border);">
                                        <input type="checkbox"
                                            value="{{ $option }}" wire:model="formData.{{ $question['slug'] }}"
                                            class="w-4 h-4"
                                            style="accent-color: var(--color-primary);">
                                        <span style="color: var(--color-text);">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>

                        @elseif ($question['type'] === 'date')
                            <input type="text" wire:model="formData.{{ $question['slug'] }}"
                                class="w-full px-4 py-3 rounded-xl"
                                style="border: 1px solid var(--color-border); background-color: var(--color-surface); color: var(--color-text); outline: none;"
                                placeholder="ДД.ММ.ГГГГ"
                                maxlength="10"
                                x-data
                                x-on:input="
                                    let v = $el.value.replace(/[^0-9]/g, '');
                                    if (v.length > 2) v = v.substring(0,2) + '.' + v.substring(2);
                                    if (v.length > 5) v = v.substring(0,5) + '.' + v.substring(5,9);
                                    $el.value = v.substring(0, 10);
                                    $wire.set('formData.{{ $question['slug'] }}', $el.value);
                                ">
                        @endif
                    </div>
                @endforeach

                <button type="button" id="submitBtn"
                    class="w-full py-4 px-6 rounded-xl font-semibold text-white transition-colors mt-6"
                    style="background-color: var(--color-primary); border-radius: var(--radius-btn); cursor: pointer;">
                    Зарегистрироваться
                </button>
            </form>

            <script>
            document.getElementById('submitBtn').addEventListener('click', function() {
                var valid = true;

                var name = document.getElementById('field-name');
                var lblName = document.getElementById('lbl-name');
                var errName = document.getElementById('err-name');
                name.style.borderColor = 'var(--color-border)';
                lblName.style.color = 'var(--color-text)';
                errName.style.display = 'none';

                if (!name.value.trim()) {
                    name.style.borderColor = '#ef4444';
                    lblName.style.color = '#ef4444';
                    errName.textContent = 'Поле «Имя» обязательно для заполнения';
                    errName.style.display = 'block';
                    valid = false;
                }

                var email = document.getElementById('field-email');
                var lblEmail = document.getElementById('lbl-email');
                var errEmail = document.getElementById('err-email');
                email.style.borderColor = 'var(--color-border)';
                lblEmail.style.color = 'var(--color-text)';
                errEmail.style.display = 'none';

                if (!email.value.trim()) {
                    email.style.borderColor = '#ef4444';
                    lblEmail.style.color = '#ef4444';
                    errEmail.textContent = 'Поле «Email» обязательно для заполнения';
                    errEmail.style.display = 'block';
                    valid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                    email.style.borderColor = '#ef4444';
                    lblEmail.style.color = '#ef4444';
                    errEmail.textContent = 'Введите корректный email адрес';
                    errEmail.style.display = 'block';
                    valid = false;
                }

                if (valid) {
                    Livewire.dispatch('submit');
                }
            });
            </script>
        @endif
    </div>
</div>
