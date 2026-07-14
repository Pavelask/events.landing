@php
    $statePath = $getStatePath();
    $language = $getLanguage();
    $theme = $getTheme();
    $showPreview = $getShowPreview();
    $id = $getId();
@endphp

<div
    wire:ignore
    x-data="monacoEditor({
        statePath: '{{ $statePath }}',
        language: '{{ $language }}',
        theme: '{{ $theme }}',
        showPreview: {{ $showPreview ? 'true' : 'false' }},
    })"
    x-init="init()"
    class="fi-fo-code-editor"
>
    {{-- Mode Tabs --}}
    @if($showPreview)
        <div class="mb-2 flex items-center gap-2">
            <button
                type="button"
                @click="mode = 'code'; updatePreview()"
                :class="mode === 'code' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
            >
                <x-heroicon-s-code-bracket class="inline h-4 w-4 mr-1" />
                Код
            </button>
            <button
                type="button"
                @click="mode = 'preview'; updatePreview()"
                :class="mode === 'preview' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
            >
                <x-heroicon-s-eye class="inline h-4 w-4 mr-1" />
                Визуально
            </button>
            <button
                type="button"
                @click="mode = 'split'; updatePreview()"
                :class="mode === 'split' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
            >
                <x-heroicon-s-square-3-stack-3d class="inline h-4 w-4 mr-1" />
                Разделить
            </button>
        </div>
    @endif

    {{-- Editor Container --}}
    <div class="flex gap-2" :class="{ 'h-[600px]': true }">
        {{-- Code Editor --}}
        <div
            x-show="mode === 'code' || mode === 'split'"
            x-ref="editorContainer"
            class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
            :class="{ 'w-full': mode === 'code', 'w-1/2': mode === 'split' }"
            style="min-height: 500px;"
        ></div>

        {{-- Preview --}}
        @if($showPreview)
            <div
                x-show="mode === 'preview' || mode === 'split'"
                class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white"
                :class="{ 'w-full': mode === 'preview', 'w-1/2': mode === 'split' }"
            >
                <iframe
                    x-ref="previewFrame"
                    class="w-full h-full border-0"
                    style="min-height: 500px;"
                    sandbox="allow-same-origin"
                ></iframe>
            </div>
        @endif
    </div>

    {{-- Load Monaco from CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

    <script>
        function monacoEditor(config) {
            return {
                editor: null,
                mode: config.showPreview ? 'code' : 'code',
                config: config,

                init() {
                    require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });

                    require(['vs/editor/editor.main'], () => {
                        // Get current state from Livewire
                        const state = @js($getState());
                        const value = typeof state === 'string' ? state : '';

                        // Create editor
                        this.editor = monaco.editor.create(this.$refs.editorContainer, {
                            value: value,
                            language: this.config.language,
                            theme: this.config.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'vs-dark' : 'vs'),
                            minimap: { enabled: false },
                            fontSize: 14,
                            lineNumbers: 'on',
                            scrollBeyondLastLine: false,
                            wordWrap: 'on',
                            automaticLayout: true,
                            tabSize: 4,
                            padding: { top: 10, bottom: 10 },
                        });

                        // Sync editor changes to Livewire state
                        this.editor.onDidChangeModelContent(() => {
                            const value = this.editor.getValue();
                            $wire.set(this.config.statePath, value);
                            this.updatePreview();
                        });

                        // Initial preview
                        if (this.config.showPreview) {
                            this.updatePreview();
                        }
                    });
                },

                updatePreview() {
                    if (!this.config.showPreview) return;

                    const value = this.editor ? this.editor.getValue() : '';
                    const html = `
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <style>
                                body { font-family: system-ui, -apple-system, sans-serif; padding: 20px; margin: 0; }
                                table { border-collapse: collapse; width: 100%; margin: 10px 0; }
                                td, th { border: 1px solid #ccc; padding: 8px; }
                            </style>
                        </head>
                        <body>${value}</body>
                        </html>
                    `;

                    const blob = new Blob([html], { type: 'text/html' });
                    const url = URL.createObjectURL(blob);

                    if (this.$refs.previewFrame) {
                        this.$refs.previewFrame.src = url;
                    }
                },
            };
        }
    </script>
</div>
