@php
    $statePath = $getStatePath();
    $showPreview = $getShowPreview();
    $id = $getId();
    $placeholders = [
        ['title' => 'ФИО', 'value' => '{{ full_name }}'],
        ['title' => 'Серия паспорта', 'value' => '{{ passport_series }}'],
        ['title' => 'Номер паспорта', 'value' => '{{ passport_number }}'],
        ['title' => 'Кем выдан', 'value' => '{{ passport_issued_by }}'],
        ['title' => 'Адрес регистрации', 'value' => '{{ registration_address }}'],
        ['title' => 'Телефон', 'value' => '{{ phone }}'],
        ['title' => 'Email', 'value' => '{{ email }}'],
        ['title' => 'Мероприятие', 'value' => '{{ event_title }}'],
        ['title' => 'Дата мероприятия', 'value' => '{{ event_date }}'],
        ['title' => 'Текущая дата', 'value' => '{{ current_date }}'],
        ['title' => 'Организация', 'value' => '{{ organization_name }}'],
        ['title' => 'ИНН', 'value' => '{{ organization_inn }}'],
    ];
@endphp

<style>
    .monaco-editor-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .monaco-editor-wrapper .tab-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }
    .monaco-editor-wrapper .tab-btn.active {
        background-color: #3b82f6;
        color: white;
        border-color: #2563eb;
    }
    .monaco-editor-wrapper .tab-btn:not(.active) {
        background-color: #f3f4f6;
        color: #374151;
        border-color: #e5e7eb;
    }
    .monaco-editor-wrapper .tab-btn:not(.active):hover {
        background-color: #e5e7eb;
    }
    .monaco-editor-wrapper .editor-panel {
        min-height: 600px;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .monaco-editor-wrapper .code-textarea {
        width: 100%;
        min-height: 600px;
        padding: 1rem;
        font-family: 'Fira Code', 'Cascadia Code', 'JetBrains Mono', Consolas, monospace;
        font-size: 14px;
        line-height: 1.6;
        border: none;
        outline: none;
        resize: vertical;
        background: #1e1e1e;
        color: #d4d4d4;
        tab-size: 4;
    }
    .monaco-editor-wrapper .preview-frame {
        width: 100%;
        min-height: 600px;
        border: none;
        background: white;
    }
    .monaco-editor-wrapper .placeholder-select {
        padding: 0.375rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background: white;
        font-size: 0.875rem;
        cursor: pointer;
    }
    .monaco-editor-wrapper .placeholder-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .monaco-editor-wrapper .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .monaco-editor-wrapper .action-btn-primary {
        background-color: #3b82f6;
        color: white;
        border: 1px solid #2563eb;
    }
    .monaco-editor-wrapper .action-btn-primary:hover {
        background-color: #2563eb;
    }
    .monaco-editor-wrapper .action-btn-secondary {
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }
    .monaco-editor-wrapper .action-btn-secondary:hover {
        background-color: #e5e7eb;
    }
    .monaco-editor-wrapper .ck-editor__editable {
        min-height: 500px !important;
    }
</style>

<div
    x-data="{
        mode: 'split',
        content: @entangle($statePath),
        editor: null,
        ckeditor: null,
        ckeditorLoaded: false,
        initialized: false,
        get previewHtml() {
            return this.content || '';
        }
    }"
    x-init="$nextTick(() => {
        initCodeMirror();
    })"
    class="monaco-editor-wrapper"
>
    {{-- Mode Tabs --}}
    <div class="mb-3 flex items-center gap-2 flex-wrap">
        <button
            type="button"
            @click="mode = 'code'; $nextTick(() => { if (editor) editor.refresh() })"
            :class="mode === 'code' ? 'active' : ''"
            class="tab-btn"
        >
            Код
        </button>
        @if($showPreview)
            <button
                type="button"
                @click="mode = 'preview'"
                :class="mode === 'preview' ? 'active' : ''"
                class="tab-btn"
            >
                Визуально
            </button>
            <button
                type="button"
                @click="mode = 'split'; $nextTick(() => { if (editor) editor.refresh() })"
                :class="mode === 'split' ? 'active' : ''"
                class="tab-btn"
            >
                Разделить
            </button>
        @endif
        <button
            type="button"
            @click="mode = 'constructor'; $nextTick(() => initCKEditor())"
            :class="mode === 'constructor' ? 'active' : ''"
            class="tab-btn"
        >
            Конструктор
        </button>

        @if($showPreview)
            <div class="ml-auto flex items-center gap-2">
                <label class="text-sm text-gray-600">Плейсхолдер:</label>
                <select
                    x-ref="placeholderSelect"
                    @change="insertPlaceholder($event.target.value); $event.target.value = ''"
                    class="placeholder-select"
                >
                    <option value="">Выберите...</option>
                    @foreach($placeholders as $ph)
                        <option value="{{ $ph['value'] }}">{{ $ph['title'] }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    {{-- Code + Preview --}}
    <div x-show="mode !== 'constructor'" class="flex gap-2 w-full">
        {{-- Code Editor --}}
        <div
            x-show="mode === 'code' || mode === 'split'"
            :class="{ 'w-full': mode === 'code', 'w-1/2': mode === 'split' }"
            class="editor-panel"
        >
            <textarea
                x-ref="codeTextarea"
                wire:model="{{ $statePath }}"
                class="code-textarea"
                spellcheck="false"
            >{!! e($getState() ?? '<p></p>') !!}</textarea>
        </div>

        {{-- Preview --}}
        @if($showPreview)
            <div
                x-show="mode === 'preview' || mode === 'split'"
                :class="{ 'w-full': mode === 'preview', 'w-1/2': mode === 'split' }"
                class="editor-panel"
            >
                <iframe
                    x-ref="previewFrame"
                    class="preview-frame"
                    sandbox="allow-same-origin"
                    :srcdoc="previewHtml"
                ></iframe>
            </div>
        @endif
    </div>

    {{-- CKEditor Constructor --}}
    <div x-show="mode === 'constructor'" class="w-full">
        <div class="editor-panel" style="min-height: 600px;">
            <div x-ref="ckeditorContainer" style="min-height: 500px;"></div>
        </div>
        <div class="mt-2 flex gap-2">
            <button
                type="button"
                @click="applyFromCKEditor()"
                class="action-btn action-btn-primary"
            >
                Применить к коду
            </button>
            <button
                type="button"
                @click="mode = 'split'; $nextTick(() => { if (editor) editor.refresh() })"
                class="action-btn action-btn-secondary"
            >
                Отмена
            </button>
        </div>
    </div>

    {{-- CodeMirror CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">

    {{-- CKEditor 5 CSS --}}
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.css">

    {{-- CodeMirror JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>

    {{-- CKEditor 5 UMD --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.umd.min.js"></script>

    <script>
        function initCodeMirror() {
            if (typeof CodeMirror === 'undefined' || !this.$refs.codeTextarea || this.initialized) return;

            this.editor = CodeMirror.fromTextArea(this.$refs.codeTextarea, {
                mode: 'htmlmixed',
                theme: 'dracula',
                lineNumbers: true,
                lineWrapping: true,
                tabSize: 4,
                indentWithTabs: false,
                matchBrackets: true,
                autoCloseTags: true,
                autoCloseBrackets: true,
            });

            this.editor.on('change', () => {
                this.content = this.editor.getValue();
            });

            this.initialized = true;
        }

        function initCKEditor() {
            const container = this.$refs.ckeditorContainer;
            if (!container) return;

            if (this.ckeditor) {
                this.ckeditor.destroy();
                this.ckeditor = null;
            }

            if (typeof ClassicEditor === 'undefined') {
                console.error('CKEditor 5 not loaded. Check CDN.');
                container.innerHTML = '<div style="padding: 2rem; color: red; text-align: center;">CKEditor 5 не загружен. Проверьте подключение к интернету.</div>';
                return;
            }

            const currentContent = this.content || '';

            ClassicEditor.create(container, {
                initialData: currentContent,
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'bulletedList', 'numberedList', '|',
                        'indent', 'outdent', '|',
                        'insertTable', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
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
            }).then(editor => {
                this.ckeditor = editor;
                this.ckeditorLoaded = true;

                editor.model.document.on('change:data', () => {
                    this.content = editor.getData();
                });
            }).catch(error => {
                console.error('CKEditor error:', error);
                container.innerHTML = '<div style="padding: 2rem; color: red; text-align: center;">Ошибка инициализации CKEditor: ' + error.message + '</div>';
            });
        }

        function applyFromCKEditor() {
            if (this.ckeditor) {
                this.content = this.ckeditor.getData();
            }
            this.mode = 'split';
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
                this.ckeditor.model.change(writer => {
                    const insertPosition = this.ckeditor.model.document.selection.getFirstPosition();
                    writer.insertText(value, insertPosition);
                });
            } else if (this.editor) {
                const cursor = this.editor.getCursor();
                this.editor.replaceRange(value, cursor, cursor);
                this.editor.focus();
            }
        }
    </script>
</div>
