@php
    $statePath = $getStatePath();
    $showPreview = $getShowPreview();
    $id = $getId();
@endphp

<div
    x-data="{
        mode: 'split',
        content: @entangle($statePath),
        get previewHtml() {
            return this.content || '';
        }
    }"
    class="fi-fo-code-editor"
>
    {{-- Mode Tabs --}}
    @if($showPreview)
        <div class="mb-2 flex items-center gap-2">
            <button
                type="button"
                @click="mode = 'code'"
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
                @click="mode = 'split'"
                :class="mode === 'split' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors"
            >
                Разделить
            </button>
        </div>
    @endif

    {{-- Editor Container --}}
    <div class="flex gap-2" style="min-height: 500px;">
        {{-- Code Editor --}}
        <div
            x-show="mode === 'code' || mode === 'split'"
            :class="{ 'w-full': mode === 'code', 'w-1/2': mode === 'split' }"
            class="flex flex-col"
        >
            <textarea
                x-model="content"
                wire:model="{{ $statePath }}"
                class="w-full h-full min-h-[500px] rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 p-4 font-mono text-sm text-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 resize-y"
                style="tab-size: 4;"
                spellcheck="false"
            ></textarea>
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
                    style="min-height: 500px;"
                    sandbox="allow-same-origin"
                    :srcdoc="previewHtml"
                ></iframe>
            </div>
        @endif
    </div>
</div>
