@php
    $statePath = $getStatePath();
    $value = $getState() ?? '';
@endphp

<div>
    <textarea
        id="ckeditor-{{ $getId() }}"
        wire:model="{{ $statePath }}"
        style="display:none;"
    >{{ $value }}</textarea>
    <div id="ckeditor-loading-{{ $getId() }}" style="padding:2rem;text-align:center;color:#666;">
        Загрузка редактора...
    </div>
    <div id="ckeditor-wrapper-{{ $getId() }}"></div>
</div>

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.css">
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/ckeditor5.umd.min.js" onload="initCKEditorOnLoad('{{ $getId() }}')"></script>

<script>
    function initCKEditorOnLoad(id) {
        const textarea = document.getElementById('ckeditor-' + id);
        const wrapper = document.getElementById('ckeditor-wrapper-' + id);
        const loading = document.getElementById('ckeditor-loading-' + id);

        if (!textarea || !wrapper || typeof ClassicEditor === 'undefined') {
            if (loading) loading.innerHTML = '<span style="color:red;">Ошибка загрузки редактора</span>';
            return;
        }

        loading.style.display = 'none';

        ClassicEditor.create(wrapper, {
            initialData: textarea.value,
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
            loading.style.display = 'block';
            loading.innerHTML = '<span style="color:red;">Ошибка: ' + err.message + '</span>';
        });
    }

    // Also try if script loads after DOMContentLoaded
    if (typeof ClassicEditor !== 'undefined' && document.getElementById('ckeditor-wrapper-{{ $getId() }}')) {
        initCKEditorOnLoad('{{ $getId() }}');
    }
</script>

<style>
    .ck-editor__editable {
        min-height: 500px !important;
    }
</style>
