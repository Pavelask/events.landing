<x-filament-panels::page>
    @php
        $service = app(\App\Services\OnlyOfficeService::class);
        $config = $service->editorConfig($this->record);
        $token = $service->signConfig($config);
    @endphp

    @if (empty($this->record->docx_file))
        <div class="rounded-xl border border-dashed border-danger-300 bg-danger-50 p-6 text-center">
            <p class="font-medium text-danger-600">
                У шаблона нет файла .docx.
            </p>
            <p class="mt-1 text-sm text-danger-500">
                Сначала вернитесь на форму и либо загрузите .docx, либо нажмите «Экспорт в .docx».
            </p>
        </div>
    @else
        @php
            $editorHeight = (int) config('onlyoffice.editor_height', 1123);
            $editorWidth = (int) config('onlyoffice.editor_width', 794);
            $editorChrome = (int) config('onlyoffice.editor_chrome', 140);
            $headerOffset = (int) config('onlyoffice.editor_header_offset', 260);
        @endphp

        @php
            $variables = \App\Services\DocumentTemplateVariableService::all($this->record->formTemplate);
            $variableGroups = collect($variables)->groupBy('group');
        @endphp

        <div class="docvars">
            <div class="docvars__hint">
                Нажми на переменную — она скопируется, затем вставь её (Ctrl+V) в нужное место документа.
            </div>
            <div class="docvars__groups">
                @forelse ($variableGroups as $group => $items)
                    <div class="docvars__group">
                        <span class="docvars__group-title">{{ $group }}</span>
                        <div class="docvars__chips">
                            @foreach ($items as $variable)
                                @php
                                    $variableToken = '{{ ' . $variable['key'] . ' }}';
                                @endphp
                                <button type="button" class="docvar-chip"
                                        data-key="{{ $variable['key'] }}"
                                        data-token="{{ $variableToken }}"
                                        title="{{ $variable['label'] }}">
                                    {{ $variable['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="docvars__empty">
                        Переменных нет. Привяжите к шаблону форму на странице редактирования.
                    </div>
                @endforelse
            </div>
        </div>

        <div id="onlyoffice-wrapper" style="width: 100%;">
            <div id="onlyoffice-placeholder" style="width: 100%; height: 100%;"></div>
        </div>

        <div id="docvars-toast" class="docvars-toast"></div>

        <style>
            .docvars {
                max-width: 1100px;
                margin: 0 auto 16px;
                padding: 12px 14px;
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            }
            .docvars__hint {
                font-size: 12px;
                color: #64748b;
                margin-bottom: 10px;
            }
            .docvars__groups {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .docvars__group-title {
                display: block;
                font-size: 11px;
                font-weight: 600;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                margin-bottom: 4px;
            }
            .docvars__chips {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }
            .docvar-chip {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 10px;
                border: 1px solid #e2e8f0;
                border-radius: 999px;
                background: #f8fafc;
                color: #1f2937;
                font-size: 12px;
                cursor: pointer;
                transition: all 0.15s ease;
            }
            .docvar-chip:hover {
                background: #eff6ff;
                border-color: #bfdbfe;
                color: #1d4ed8;
            }
            .docvar-chip:active {
                background: #dbeafe;
            }
            .docvars__empty {
                font-size: 12px;
                color: #94a3b8;
            }
            .docvars-toast {
                position: fixed;
                left: 50%;
                bottom: 24px;
                transform: translateX(-50%);
                background: #1f2937;
                color: #fff;
                padding: 8px 14px;
                border-radius: 8px;
                font-size: 13px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s ease;
                z-index: 20000;
            }
            .docvars-toast.is-visible {
                opacity: 1;
            }
        </style>

        <script>
            (function () {
                const toast = document.getElementById('docvars-toast');
                let toastTimer = null;

                function showToast(message) {
                    toast.textContent = message;
                    toast.classList.add('is-visible');
                    clearTimeout(toastTimer);
                    toastTimer = setTimeout(() => toast.classList.remove('is-visible'), 2200);
                }

                function copyText(text, onSuccess) {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(onSuccess).catch(() => fallbackCopy(text, onSuccess));
                        return;
                    }
                    fallbackCopy(text, onSuccess);
                }

                function fallbackCopy(text, onSuccess) {
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.style.position = 'fixed';
                    textarea.style.left = '-9999px';
                    document.body.appendChild(textarea);
                    textarea.select();
                    try {
                        document.execCommand('copy');
                        onSuccess();
                    } finally {
                        document.body.removeChild(textarea);
                    }
                }

                document.querySelectorAll('.docvar-chip').forEach((chip) => {
                    chip.addEventListener('click', () => {
                        const token = chip.dataset.token;
                        if (!token) return;
                        copyText(token, () => showToast('Скопировано: ' + token + ' — вставьте Ctrl+V'));
                    });
                });
            })();
        </script>

        <script src="{{ $service->apiJsUrl() }}"></script>
        <script>
            (function () {
                if (typeof DocsAPI === 'undefined') {
                    alert('Не удалось загрузить OnlyOffice API. Проверьте ONLYOFFICE_URL.');
                    return;
                }

                const config = @json($config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                @if ($token)
                    config.token = @json($token);
                @else
                    console.warn('ONLYOFFICE_JWT_SECRET не задан — авторизация Document Server отключена.');
                @endif

                // Авто-подгонка высоты под экран:
                // желаемая = страница A4 + внутренняя панель OnlyOffice,
                // но не больше доступной высоты окна браузера.
                const pageHeight = {{ $editorHeight }};
                const chrome = {{ $editorChrome }};
                const headerOffset = {{ $headerOffset }};
                const desired = pageHeight + chrome;
                const available = window.innerHeight - headerOffset;
                const height = Math.max(400, Math.min(desired, available));

                // Размер задаём ОБЁРТКЕ — OnlyOffice заменяет внутренний placeholder,
                // а его конфиг 100%/100% меряет размер по родителю (обёртке с явным px).
                const wrapper = document.getElementById('onlyoffice-wrapper');
                wrapper.style.height = height + 'px';

                config.events = {
                    onAppReady: () => console.log('ONLYOFFICE_EVENT: onAppReady'),
                    onDocumentReady: () => console.log('ONLYOFFICE_EVENT: onDocumentReady'),
                    onDocumentStateChange: (e) => console.log('ONLYOFFICE_EVENT: state', e && e.data),
                    onError: (e) => console.log('ONLYOFFICE_EVENT: onError', JSON.stringify(e && e.data)),
                    onOutdatedVersion: (e) => console.log('ONLYOFFICE_EVENT: outdated', JSON.stringify(e && e.data)),
                };

                new DocsAPI.DocEditor('onlyoffice-placeholder', config);
            })();
        </script>
    @endif
</x-filament-panels::page>