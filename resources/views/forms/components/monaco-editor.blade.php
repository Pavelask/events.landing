@php
    $statePath = $getStatePath();
@endphp

<div wire:ignore>
    <div x-data="{
        content: @entangle($statePath),
        ckeditor: null,
        initialized: false
    }" x-init="$nextTick(() => { if (!this.initialized) initCKEditor.call(this) })">
        <div x-ref="container" style="min-height: 500px;"></div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.css">
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.umd.min.js"></script>

<script>
    function initCKEditor() {
        const self = this;
        const container = self.$refs.container;
        if (!container || self.initialized) return;

        if (typeof ClassicEditor === 'undefined') {
            container.innerHTML = '<div style="padding:2rem;color:red;text-align:center;">CKEditor не загружен. Проверьте подключение к интернету.</div>';
            return;
        }

        const data = self.content || '';

        ClassicEditor.create(container, {
            initialData: data,
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                'bulletedList', 'numberedList', '|',
                'indent', 'outdent', '|',
                'insertTable', '|',
                'link', 'blockQuote', 'horizontalLine', '|',
                'undo', 'redo', '|',
                'sourceEditing'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4' },
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            },
        }).then(editor => {
            self.ckeditor = editor;
            self.initialized = true;

            editor.model.document.on('change:data', () => {
                self.content = editor.getData();
            });
        }).catch(err => {
            console.error('CKEditor error:', err);
        });
    }
</script>

<style>
    .ck-editor__editable {
        min-height: 500px !important;
    }
</style>
