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
        ckeditor: null,
        sourceMode: false,
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
    <div class="mb-2 flex items-center gap-2 flex-wrap">
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
            @click="mode = 'constructor'; $nextTick(() => initCKEditor())"
            :class="mode === 'constructor' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
            class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
        >
            Конструктор
        </button>
        @if($showPreview)
            <div class="ml-auto flex items-center gap-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Плейсхолдер:</label>
                <select
                    x-ref="placeholderSelect"
                    @change="insertPlaceholder($event.target.value); $event.target.value = ''"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1.5 text-sm"
                >
                    <option value="">Выберите...</option>
                    <option value="{{ full_name }}">ФИО</option>
                    <option value="{{ passport_series }}">Серия паспорта</option>
                    <option value="{{ passport_number }}">Номер паспорта</option>
                    <option value="{{ passport_issued_by }}">Кем выдан</option>
                    <option value="{{ registration_address }}">Адрес</option>
                    <option value="{{ phone }}">Телефон</option>
                    <option value="{{ email }}">Email</option>
                    <option value="{{ event_title }}">Мероприятие</option>
                    <option value="{{ event_date }}">Дата мероприятия</option>
                    <option value="{{ current_date }}">Текущая дата</option>
                    <option value="{{ organization_name }}">Организация</option>
                    <option value="{{ organization_inn }}">ИНН</option>
                </select>
            </div>
        @endif
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

    {{-- CKEditor Constructor --}}
    <div x-show="mode === 'constructor'" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
        <div x-ref="ckeditorContainer" style="min-height: 500px;"></div>
        <div class="p-2 bg-gray-100 dark:bg-gray-800 flex gap-2">
            <button
                type="button"
                @click="applyFromCKEditor()"
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

    {{-- CodeMirror JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>

    {{-- CKEditor 5 CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.umd.min.js"></script>

    <script>
        function initCKEditor() {
            const container = this.$refs.ckeditorContainer;
            if (!container || this.ckeditor) return;

            const currentContent = this.content || '';
            const placeholderOptions = [
                { title: 'ФИО', value: '{{ full_name }}' },
                { title: 'Серия паспорта', value: '{{ passport_series }}' },
                { title: 'Номер паспорта', value: '{{ passport_number }}' },
                { title: 'Кем выдан', value: '{{ passport_issued_by }}' },
                { title: 'Адрес', value: '{{ registration_address }}' },
                { title: 'Телефон', value: '{{ phone }}' },
                { title: 'Email', value: '{{ email }}' },
                { title: 'Мероприятие', value: '{{ event_title }}' },
                { title: 'Дата мероприятия', value: '{{ event_date }}' },
                { title: 'Текущая дата', value: '{{ current_date }}' },
                { title: 'Организация', value: '{{ organization_name }}' },
                { title: 'ИНН', value: '{{ organization_inn }}' },
            ];

            const ClassicEditor = window.ClassicEditor;

            this.ckeditor = ClassicEditor.create(container, {
                initialData: currentContent,
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'indent', 'outdent', '|',
                    'insertTable', '|',
                    'link', 'blockQuote', '|',
                    'undo', 'redo', '|',
                    'sourceEditing'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells',
                        'tableProperties', 'tableCellProperties'
                    ]
                },
                sourceEditing: {
                    allowContentNames: true
                },
            }).then(editor => {
                // Sync CKEditor changes to Livewire state
                editor.model.document.on('change:data', () => {
                    this.content = editor.getData();
                });

                // Handle source editing toggle
                editor.plugins.get('SourceEditing').on('change:isEnabled', () => {
                    this.sourceMode = editor.plugins.get('SourceEditing').isEnabled;
                });
            }).catch(error => {
                console.error('CKEditor init error:', error);
            });
        }

        function applyFromCKEditor() {
            if (this.ckeditor) {
                this.content = this.ckeditor.getData();
            }
            this.mode = 'code';
            this.$nextTick(() => {
                if (this.editor) {
                    this.editor.setValue(this.content || '');
                    this.editor.refresh();
                }
            });
        }

        function insertPlaceholder(value) {
            if (!value) return;

            if (this.mode === 'constructor' && this.ckeditor) {
                const editor = this.ckeditor;
                const selection = editor.model.document.selection;
                editor.model.change(writer => {
                    const insertPosition = selection.getFirstPosition();
                    writer.insertText(value, insertPosition);
                });
            } else if (this.editor) {
                const cursor = this.editor.getCursor();
                this.editor.replaceRange(value, cursor, cursor);
                this.editor.focus();
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
        .ck-editor__editable {
            min-height: 500px !important;
        }
    </style>
</div>
