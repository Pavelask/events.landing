@php
    $statePath = $getStatePath();
    $value = $getState() ?? '';
@endphp

<div>
    <textarea
        id="ckeditor-{{ $getId() }}"
        wire:model="{{ $statePath }}"
    >{{ $value }}</textarea>
</div>

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.css">
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('ckeditor-{{ $getId() }}');
        if (!textarea) return;

        ClassicEditor.create(textarea, {
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
            editor.model.document.on('change:data', () => {
                textarea.value = editor.getData();
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
            });
        }).catch(err => {
            console.error('CKEditor error:', err);
        });
    });
</script>

<style>
    .ck-editor__editable {
        min-height: 500px !important;
    }
</style>
