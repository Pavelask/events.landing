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
        get previewHtml() {
            return this.content || '';
        }
    }"
    x-init="$nextTick(() => {
        if (typeof CodeMirror !== 'undefined' && $refs.codeTextarea) {
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
            this.$watch('content', (val) => {
                if (this.editor && this.editor.getValue() !== val) {
                    this.editor.setValue(val || '');
                }
            });
        }
    })"
    class="fi-fo-code-editor w-full"
>
    {{-- Mode Tabs --}}
    @if($showPreview)
        <div class="mb-2 flex items-center gap-2">
            <button
                type="button"
                @click="mode = 'code'; if (editor) editor.refresh()"
                :class="mode === 'code' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
            >
                Код
            </button>
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
        </div>
    @endif

    {{-- Editor Container --}}
    <div class="flex gap-2 w-full" style="min-height: 600px;">
        {{-- Code Editor --}}
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

        {{-- Preview --}}
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

    <style>
        .CodeMirror {
            min-height: 600px;
            border-radius: 0.5rem;
            font-size: 14px;
            line-height: 1.5;
        }
    </style>
</div>
