<div class="min-h-screen" style="background-color: var(--color-background);"
     x-data="{
        errors: {},
        validate() {
            this.errors = {};
            let valid = true;

            if (!this.$wire.get('formData.name')?.trim()) {
                this.errors['formData.name'] = 'Поле «Имя» обязательно для заполнения';
                valid = false;
            }
            const email = this.$wire.get('formData.email')?.trim();
            if (!email) {
                this.errors['formData.email'] = 'Поле «Email» обязательно для заполнения';
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                this.errors['formData.email'] = 'Введите корректный email адрес';
                valid = false;
            }
            return valid;
        }
     }">
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
            <form wire:submit.prevent="submit" class="rounded-2xl p-8" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-card);">

                <div class="mb-7">
                    <label class="block text-sm font-semibold mb-2"
                        :style="{ color: errors['formData.name'] ? '#ef4444' : '' }">Имя *</label>
                    <input type="text" wire:model="formData.name"
                        class="w-full px-4 py-3 rounded-xl"
                        :style="{ border: errors['formData.name'] ? '2px solid #ef4444' : '1px solid var(--color-border)', backgroundColor: 'var(--color-surface)', color: 'var(--color-text)', outline: 'none' }"
                        placeholder="Введите имя">
                    <template x-if="errors['formData.name']">
                        <p class="mt-1 text-sm" style="color: #ef4444;" x-text="errors['formData.name']"></p>
                    </template>
                </div>

                <div class="mb-7">
                    <label class="block text-sm font-semibold mb-2"
                        :style="{ color: errors['formData.email'] ? '#ef4444' : '' }">Email *</label>
                    <input type="email" wire:model="formData.email"
                        class="w-full px-4 py-3 rounded-xl"
                        :style="{ border: errors['formData.email'] ? '2px solid #ef4444' : '1px solid var(--color-border)', backgroundColor: 'var(--color-surface)', color: 'var(--color-text)', outline: 'none' }"
                        placeholder="email@example.com">
                    <template x-if="errors['formData.email']">
                        <p class="mt-1 text-sm" style="color: #ef4444;" x-text="errors['formData.email']"></p>
                    </template>
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

                <button type="submit"
                    class="w-full py-4 px-6 rounded-xl font-semibold text-white transition-colors mt-6"
                    style="background-color: var(--color-primary); border-radius: var(--radius-btn);"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Зарегистрироваться</span>
                    <span wire:loading>Отправка...</span>
                </button>
            </form>
        @endif
    </div>
</div>
