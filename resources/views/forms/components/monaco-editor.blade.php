@php
    $statePath = $getStatePath();
    $value = $getState() ?? '';
    $id = $getId();
@endphp

<div>
    <textarea
        id="ckeditor-{{ $id }}"
        wire:model="{{ $statePath }}"
        style="display:none;"
    >{{ $value }}</textarea>
    <div id="ckeditor-loading-{{ $id }}" style="padding:2rem;text-align:center;color:#666;">
        Загрузка редактора...
    </div>
    <div id="ckeditor-wrapper-{{ $id }}"></div>
</div>

<script src="{{ asset('js/ckeditor.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            initCKEditorOnLoad('{{ $id }}');
        }, 100);
    });

    function initCKEditorOnLoad(id) {
        const textarea = document.getElementById('ckeditor-' + id);
        const wrapper = document.getElementById('ckeditor-wrapper-' + id);
        const loading = document.getElementById('ckeditor-loading-' + id);

        if (!textarea || !wrapper) return;

        if (typeof ClassicEditor === 'undefined') {
            if (loading) loading.innerHTML = '<span style="color:red;">CKEditor не загружен. Проверьте файл js/ckeditor.js</span>';
            return;
        }

        loading.style.display = 'none';

        ClassicEditor.create(wrapper, {
            initialData: textarea.value,
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
                'fontSize', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                'alignment', '|',
                'bulletedList', 'numberedList', '|',
                'indent', 'outdent', '|',
                'codeBlock', '|',
                'insertTable', '|',
                'link', 'blockQuote', 'horizontalLine', '|',
                'specialCharacters', '|',
                'findAndReplace', '|',
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
            if (loading) {
                loading.style.display = 'block';
                loading.innerHTML = '<span style="color:red;">Ошибка: ' + err.message + '</span>';
            }
        });
    }
</script>

<style>
    .ck-editor__editable {
        min-height: 500px !important;
    }
</style>
