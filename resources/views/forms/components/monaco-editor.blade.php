@php
    $statePath = $getStatePath();
    $showPreview = $getShowPreview();
    $id = $getId();
@endphp

<div
    x-data="{
        mode: 'split',
        content: @entangle($statePath),
        editor: null,
        grapesjsEditor: null,
        initialized: false,
        get previewHtml() {
            return this.content || '';
        }
    }"
    x-init="$nextTick(() => {
        if (typeof CodeMirror !== 'undefined' && $refs.codeTextarea && !this.initialized) {
            this.editor = CodeMirror.fromTextArea($refs.codeTextarea, {
                mode: 'htmlmixed',
                theme: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dracula' : 'default',
                lineNumbers: true,
                lineWrapping: true,
                tabSize: 4,
                indentWithTabs: false,
                matchBrackets: true,
                autoCloseTags: true,
                autoCloseBrackets: true,
                extraKeys: { 'Tab': (cm) => cm.replaceSelection('    ', 'end') }
            });
            this.editor.on('change', () => {
                this.content = this.editor.getValue();
            });
            this.initialized = true;
        }
    })"
    class="fi-fo-code-editor w-full"
>
    {{-- Mode Tabs --}}
    <div class="mb-2 flex items-center gap-2">
        <button
            type="button"
            @click="mode = 'code'; if (editor) editor.refresh()"
            :class="mode === 'code' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
            class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
        >
            Код
        </button>
        @if($showPreview)
            <button
                type="button"
                @click="mode = 'preview'"
                :class="mode === 'preview' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
            >
                Визуально
            </button>
            <button
                type="button"
                @click="mode = 'split'; $nextTick(() => { if (editor) editor.refresh() })"
                :class="mode === 'split' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
            >
                Разделить
            </button>
        @endif
        <button
            type="button"
            @click="mode = 'constructor'; initGrapesJS()"
            :class="mode === 'constructor' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
            class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
        >
            Конструктор
        </button>
    </div>

    {{-- Code + Preview --}}
    <div x-show="mode !== 'constructor'" class="flex gap-2 w-full" style="min-height: 600px;">
        <div
            x-show="mode === 'code' || mode === 'split'"
            :class="{ 'w-full': mode === 'code', 'w-1/2': mode === 'split' }"
            class="flex flex-col"
        >
            <textarea
                x-ref="codeTextarea"
                wire:model="{{ $statePath }}"
                class="w-full h-full min-h-[600px] rounded-lg border border-gray-300 dark:border-gray-600"
                style="tab-size: 4;"
                spellcheck="false"
            >{!! e($getState() ?? '') !!}</textarea>
        </div>
        @if($showPreview)
            <div
                x-show="mode === 'preview' || mode === 'split'"
                :class="{ 'w-full': mode === 'preview', 'w-1/2': mode === 'split' }"
                class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white"
            >
                <iframe
                    x-ref="previewFrame"
                    class="w-full h-full border-0"
                    style="min-height: 600px;"
                    sandbox="allow-same-origin"
                    :srcdoc="previewHtml"
                ></iframe>
            </div>
        @endif
    </div>

    {{-- GrapesJS Constructor --}}
    <div x-show="mode === 'constructor'" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
        <div x-ref="grapesjsContainer" style="height: 700px;"></div>
        <div class="p-2 bg-gray-100 dark:bg-gray-800 flex gap-2">
            <button
                type="button"
                @click="if (grapesjsEditor) { content = grapesjsEditor.getHtml(); mode = 'code'; $nextTick(() => { if (editor) { editor.setValue(content); editor.refresh(); } }) }"
                class="rounded-lg bg-primary-500 px-4 py-2 text-sm font-medium text-white hover:bg-primary-600 transition-colors"
            >
                Применить к коду
            </button>
            <button
                type="button"
                @click="mode = 'split'; $nextTick(() => { if (editor) editor.refresh() })"
                class="rounded-lg bg-gray-200 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
                Отмена
            </button>
        </div>
    </div>

    {{-- CodeMirror CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.css">

    {{-- GrapesJS CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/grapesjs@0.21.10/dist/css/grapes.min.css">

    {{-- CodeMirror JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>

    {{-- GrapesJS JS --}}
    <script src="https://unpkg.com/grapesjs@0.21.10/dist/grapes.min.js"></script>

    <script>
        function initGrapesJS() {
            const container = document.querySelector('[x-ref="grapesjsContainer"]');
            if (!container || this.grapesjsEditor) return;

            const currentContent = this.content || '';

            this.grapesjsEditor = grapesjs.init({
                container: container,
                height: '100%',
                fromElement: false,
                storageManager: false,
                plugins: [],
                blockManager: {
                    appendTo: '',
                },
            });

            // Add custom blocks
            const bm = this.grapesjsEditor.BlockManager;

            bm.add('heading', {
                label: 'Заголовок',
                category: 'Базовые',
                content: '<h2>Заголовок</h2>',
            });

            bm.add('paragraph', {
                label: 'Параграф',
                category: 'Базовые',
                content: '<p>Текст параграфа</p>',
            });

            bm.add('placeholder', {
                label: 'Плейсхолдер',
                category: 'Данные',
                content: '<span style="background: #fff3cd; padding: 2px 6px; border-radius: 4px; border: 1px dashed #ffc107;">{{ full_name }}</span>',
            });

            bm.add('passport', {
                label: 'Паспорт',
                category: 'Данные',
                content: '<p>Паспорт: серия {{ passport_series }} номер {{ passport_number }}</p>',
            });

            bm.add('address', {
                label: 'Адрес',
                category: 'Данные',
                content: '<p>Адрес: {{ registration_address }}</p>',
            });

            bm.add('table-2x2', {
                label: 'Таблица 2×2',
                category: 'Таблицы',
                content: `
                    <table style="width: 100%; border-collapse: collapse; margin: 10px 0;">
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 8px;">Ячейка 1</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">Ячейка 2</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 8px;">Ячейка 3</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">Ячейка 4</td>
                        </tr>
                    </table>
                `,
            });

            bm.add('table-3x6', {
                label: 'Таблица категорий ПД',
                category: 'Таблицы',
                content: `
                    <table style="width: 100%; border-collapse: collapse; margin: 10px 0;">
                        <thead>
                            <tr style="background: #f5f5f5;">
                                <th style="border: 1px solid #ccc; padding: 8px;">№</th>
                                <th style="border: 1px solid #ccc; padding: 8px;">Категория</th>
                                <th style="border: 1px solid #ccc; padding: 8px;">ДА</th>
                                <th style="border: 1px solid #ccc; padding: 8px;">НЕТ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #ccc; padding: 8px;">1</td>
                                <td style="border: 1px solid #ccc; padding: 8px;">ФИО</td>
                                <td style="border: 1px solid #ccc; padding: 8px;">☐</td>
                                <td style="border: 1px solid #ccc; padding: 8px;">☐</td>
                            </tr>
                        </tbody>
                    </table>
                `,
            });

            bm.add('date-line', {
                label: 'Дата и подпись',
                category: 'Базовые',
                content: '<p>«____» __________ 2026 г.</p><p>________________________ / ___________________________________________________</p>',
            });

            bm.add('spacer', {
                label: 'Отступ',
                category: 'Базовые',
                content: '<div style="height: 20px;"></div>',
            });

            bm.add('divider', {
                label: 'Разделитель',
                category: 'Базовые',
                content: '<hr style="border: none; border-top: 1px solid #ccc; margin: 15px 0;">',
            });

            // Load current content
            if (currentContent) {
                this.grapesjsEditor.DomComponents.getWrapper().set('content', currentContent);
            }
        }
    </script>

    <style>
        .CodeMirror {
            min-height: 600px;
            border-radius: 0.5rem;
            font-size: 14px;
            line-height: 1.5;
        }
    </style>
</div>
