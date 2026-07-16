@php
    $statePath = $getStatePath();
    $value = $getState() ?? '';
    $id = $getId();
@endphp

<div wire:ignore class="tiptap-container" data-tiptap-id="{{ $id }}" data-placeholder="{{ $getPlaceholder() ?? 'Введите текст...' }}">
    <textarea
        id="tiptap-{{ $id }}"
        wire:model="{{ $statePath }}"
        style="display:none;"
    >{{ $value }}</textarea>

    <div class="tiptap-wrapper">
        <div class="tiptap-toolbar">
            <button type="button" class="tiptap-btn tiptap-btn-bold" data-action="bold" title="Bold (Ctrl+B)">B</button>
            <button type="button" class="tiptap-btn tiptap-btn-italic" data-action="italic" title="Italic (Ctrl+I)">I</button>
            <button type="button" class="tiptap-btn tiptap-btn-underline" data-action="underline" title="Underline (Ctrl+U)">U</button>
            <button type="button" class="tiptap-btn tiptap-btn-strike" data-action="strike" title="Strikethrough">S</button>
            <button type="button" class="tiptap-btn tiptap-btn-highlight" data-action="highlight" title="Highlight">H</button>
            <span class="tiptap-separator"></span>
            <button type="button" class="tiptap-btn" data-action="h2" title="Heading 2">H2</button>
            <button type="button" class="tiptap-btn" data-action="h3" title="Heading 3">H3</button>
            <button type="button" class="tiptap-btn" data-action="h4" title="Heading 4">H4</button>
            <span class="tiptap-separator"></span>
            <button type="button" class="tiptap-btn" data-action="alignLeft" title="Align Left">&#8676;</button>
            <button type="button" class="tiptap-btn" data-action="alignCenter" title="Align Center">&#8596;</button>
            <button type="button" class="tiptap-btn" data-action="alignRight" title="Align Right">&#8677;</button>
            <span class="tiptap-separator"></span>
            <button type="button" class="tiptap-btn" data-action="bulletList" title="Bullet List">&bull;</button>
            <button type="button" class="tiptap-btn" data-action="orderedList" title="Ordered List">1.</button>
            <button type="button" class="tiptap-btn" data-action="taskList" title="Task List">&#9745;</button>
            <span class="tiptap-separator"></span>
            <button type="button" class="tiptap-btn" data-action="blockquote" title="Blockquote">&ldquo;</button>
            <button type="button" class="tiptap-btn tiptap-btn-code" data-action="codeBlock" title="Code Block">&lt;/&gt;</button>
            <button type="button" class="tiptap-btn" data-action="horizontalRule" title="Horizontal Rule">&mdash;</button>
            <span class="tiptap-separator"></span>
            <button type="button" class="tiptap-btn" data-action="link" title="Insert Link">&#128279;</button>
            <button type="button" class="tiptap-btn" data-action="image" title="Insert Image">&#128247;</button>
            <span class="tiptap-separator"></span>
            <button type="button" class="tiptap-btn" data-action="table" title="Insert Table">&#9638;</button>
            <button type="button" class="tiptap-btn tiptap-btn-sm" data-action="tableAddColumnAfter" title="Add Column">+|</button>
            <button type="button" class="tiptap-btn tiptap-btn-sm" data-action="tableAddRowAfter" title="Add Row">+</button>
            <button type="button" class="tiptap-btn tiptap-btn-sm" data-action="tableDeleteColumn" title="Delete Column">-|</button>
            <button type="button" class="tiptap-btn tiptap-btn-sm" data-action="tableDeleteRow" title="Delete Row">-</button>
            <button type="button" class="tiptap-btn tiptap-btn-sm" data-action="tableDeleteTable" title="Delete Table">&#10005;</button>
            <span class="tiptap-separator"></span>
            <button type="button" class="tiptap-btn" data-action="undo" title="Undo (Ctrl+Z)">&#8617;</button>
            <button type="button" class="tiptap-btn" data-action="redo" title="Redo (Ctrl+Y)">&#8618;</button>
            <span class="tiptap-spacer"></span>
            <button type="button" class="tiptap-btn tiptap-toggle-source">HTML</button>
        </div>

        <div class="tiptap-editor-content"></div>

        <textarea class="tiptap-source-content"></textarea>
    </div>
</div>

<style>
    .tiptap-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        overflow: hidden;
        background: white;
    }
    .tiptap-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        padding: 6px 8px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        min-height: 40px;
        align-items: center;
    }
    .tiptap-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        padding: 2px 6px;
        border: 1px solid transparent;
        border-radius: 4px;
        background: transparent;
        color: #374151;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .tiptap-btn:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
    }
    .tiptap-btn:active {
        background: #cbd5e1;
    }
    .tiptap-btn.tiptap-btn-active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .tiptap-btn.tiptap-btn-active:hover {
        background: #2563eb;
        border-color: #2563eb;
    }
    .tiptap-btn-bold { font-weight: 700; }
    .tiptap-btn-italic { font-style: italic; }
    .tiptap-btn-underline { text-decoration: underline; }
    .tiptap-btn-strike { text-decoration: line-through; }
    .tiptap-btn-highlight { background: #fef08a; }
    .tiptap-btn-highlight:hover { background: #fde047; }
    .tiptap-btn-code { font-family: monospace; font-size: 12px; }
    .tiptap-btn-sm { font-size: 11px; min-width: 24px; }
    .tiptap-separator {
        width: 1px;
        height: 20px;
        background: #e2e8f0;
        margin: 0 4px;
        flex-shrink: 0;
    }
    .tiptap-spacer {
        flex: 1;
    }
    .tiptap-toggle-source {
        font-size: 11px;
        border: 1px solid #e2e8f0;
        background: white;
        margin-left: auto;
    }
    .tiptap-editor-content {
        min-height: 300px;
        padding: 1rem;
        outline: none;
        line-height: 1.6;
    }
    .tiptap-source-content {
        display: none;
        width: 100%;
        min-height: 300px;
        padding: 1rem;
        border: none;
        outline: none;
        resize: vertical;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        font-size: 13px;
        line-height: 1.5;
        tab-size: 2;
    }
    .tiptap-editor-content p.is-editor-empty:first-child::before {
        content: attr(data-placeholder);
        float: left;
        color: #9ca3af;
        pointer-events: none;
        height: 0;
    }
    .tiptap-editor-content h2 { font-size: 1.5em; font-weight: 700; margin: 1em 0 0.5em; }
    .tiptap-editor-content h3 { font-size: 1.25em; font-weight: 700; margin: 1em 0 0.5em; }
    .tiptap-editor-content h4 { font-size: 1.1em; font-weight: 700; margin: 1em 0 0.5em; }
    .tiptap-editor-content blockquote {
        border-left: 3px solid #e2e8f0;
        padding-left: 1rem;
        margin-left: 0;
        color: #64748b;
    }
    .tiptap-editor-content pre {
        background: #f1f5f9;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        overflow-x: auto;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        font-size: 0.9em;
    }
    .tiptap-editor-content code {
        background: #f1f5f9;
        padding: 0.15em 0.3em;
        border-radius: 0.25em;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        font-size: 0.9em;
    }
    .tiptap-editor-content pre code {
        background: none;
        padding: 0;
    }
    .tiptap-editor-content hr {
        border: none;
        border-top: 1px solid #e2e8f0;
        margin: 1rem 0;
    }
    .tiptap-editor-content ul, .tiptap-editor-content ol {
        padding-left: 1.5rem;
    }
    .tiptap-editor-content a {
        color: #3b82f6;
        text-decoration: underline;
    }
    .tiptap-editor-content mark {
        background-color: #fef08a;
        padding: 0.1em 0.2em;
        border-radius: 2px;
    }
    .tiptap-editor-content ul[data-type="taskList"] {
        list-style: none;
        padding-left: 0;
    }
    .tiptap-editor-content ul[data-type="taskList"] li {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    .tiptap-editor-content ul[data-type="taskList"] li label {
        margin-top: 0.25rem;
    }
    .tiptap-editor-content ul[data-type="taskList"] li input[type="checkbox"] {
        cursor: pointer;
    }
    .tiptap-editor-content table {
        border-collapse: collapse;
        width: 100%;
        margin: 1rem 0;
    }
    .tiptap-editor-content table td,
    .tiptap-editor-content table th {
        border: 1px solid #e2e8f0;
        padding: 0.5rem;
        min-width: 80px;
    }
    .tiptap-editor-content table th {
        background: #f8fafc;
        font-weight: 600;
    }
    .tiptap-editor-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.375rem;
    }
    .tiptap-toggle-active {
        background: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
    }
    .tiptap-code-highlight {
        min-height: 300px;
        max-height: 600px;
        overflow-y: auto;
        padding: 1rem;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        font-size: 13px;
        line-height: 1.6;
        tab-size: 2;
        white-space: pre-wrap;
        word-wrap: break-word;
        background: #1e293b;
        color: #e2e8f0;
        outline: none;
        border: none;
    }
    .tiptap-code-highlight .tiptap-hl-tag { color: #7dd3fc; }
    .tiptap-code-highlight .tiptap-hl-attr { color: #c4b5fd; }
    .tiptap-code-highlight .tiptap-hl-string { color: #86efac; }
    .tiptap-code-highlight .tiptap-hl-comment { color: #64748b; font-style: italic; }
</style>

<script type="module" src="{{ Vite::asset('resources/js/tiptap-editor.js') }}"></script>
