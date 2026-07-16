import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Highlight from '@tiptap/extension-highlight'
import { TextStyle, TextStyleKit } from '@tiptap/extension-text-style'
import TextAlign from '@tiptap/extension-text-align'
import Image from '@tiptap/extension-image'
import { Table, TableRow, TableCell, TableHeader } from '@tiptap/extension-table'
import TaskList from '@tiptap/extension-task-list'
import TaskItem from '@tiptap/extension-task-item'
import Typography from '@tiptap/extension-typography'
import Link from '@tiptap/extension-link'
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight'
import { common, createLowlight } from 'lowlight'

const lowlight = createLowlight(common)

class TiptapEditor {
    constructor(element, options = {}) {
        this.element = element
        this.textarea = element.querySelector('textarea')
        this.editorContainer = element.querySelector('.tiptap-editor-content')
        this.sourceContainer = element.querySelector('.tiptap-source-content')
        this.toggleBtn = element.querySelector('.tiptap-toggle-source')
        this.toolbar = element.querySelector('.tiptap-toolbar')
        this.isSourceMode = false

        this.editor = new Editor({
            element: this.editorContainer,
            extensions: [
                StarterKit.configure({
                    heading: {
                        levels: [2, 3, 4],
                    },
                    codeBlock: false,
                    link: false,
                }),
                TextStyleKit,
                Placeholder.configure({
                    placeholder: options.placeholder || 'Введите текст...',
                }),
                Highlight,
                TextAlign.configure({
                    types: ['heading', 'paragraph'],
                }),
                Image.configure({
                    inline: true,
                    allowBase64: true,
                }),
                Table.configure({
                    resizable: true,
                }),
                TableRow,
                TableCell,
                TableHeader,
                TaskList,
                TaskItem.configure({
                    nested: true,
                }),
                Typography,
                Link.configure({
                    openOnClick: false,
                    autolink: true,
                }),
                CodeBlockLowlight.configure({
                    lowlight,
                }),
            ],
            content: this.textarea.value || '',
            onUpdate: ({ editor }) => {
                this.textarea.value = editor.getHTML()
                this.textarea.dispatchEvent(new Event('input', { bubbles: true }))
            },
        })

        this.setupToolbar()
        this.setupToggle()
        this.setupSync()
    }

    setupToolbar() {
        if (!this.toolbar) return

        this.toolbar.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('mousedown', (e) => {
                e.preventDefault()
                if (this.isSourceMode) return

                const action = btn.dataset.action

                switch (action) {
                    case 'bold':
                        this.editor.chain().focus().toggleBold().run()
                        break
                    case 'italic':
                        this.editor.chain().focus().toggleItalic().run()
                        break
                    case 'strike':
                        this.editor.chain().focus().toggleStrike().run()
                        break
                    case 'underline':
                        this.editor.chain().focus().toggleUnderline().run()
                        break
                    case 'h2':
                        this.editor.chain().focus().toggleHeading({ level: 2 }).run()
                        break
                    case 'h3':
                        this.editor.chain().focus().toggleHeading({ level: 3 }).run()
                        break
                    case 'h4':
                        this.editor.chain().focus().toggleHeading({ level: 4 }).run()
                        break
                    case 'alignLeft':
                        this.editor.chain().focus().setTextAlign('left').run()
                        break
                    case 'alignCenter':
                        this.editor.chain().focus().setTextAlign('center').run()
                        break
                    case 'alignRight':
                        this.editor.chain().focus().setTextAlign('right').run()
                        break
                    case 'bulletList':
                        this.editor.chain().focus().toggleBulletList().run()
                        break
                    case 'orderedList':
                        this.editor.chain().focus().toggleOrderedList().run()
                        break
                    case 'taskList':
                        this.editor.chain().focus().toggleTaskList().run()
                        break
                    case 'blockquote':
                        this.editor.chain().focus().toggleBlockquote().run()
                        break
                    case 'codeBlock':
                        this.editor.chain().focus().toggleCodeBlock().run()
                        break
                    case 'horizontalRule':
                        this.editor.chain().focus().setHorizontalRule().run()
                        break
                    case 'highlight':
                        this.editor.chain().focus().toggleHighlight().run()
                        break
                    case 'undo':
                        this.editor.chain().focus().undo().run()
                        break
                    case 'redo':
                        this.editor.chain().focus().redo().run()
                        break
                    case 'link':
                        const url = prompt('URL ссылки:')
                        if (url) {
                            this.editor.chain().focus().setLink({ href: url }).run()
                        }
                        break
                    case 'unlink':
                        this.editor.chain().focus().unsetLink().run()
                        break
                    case 'image':
                        const imgUrl = prompt('URL изображения:')
                        if (imgUrl) {
                            this.editor.chain().focus().setImage({ src: imgUrl }).run()
                        }
                        break
                    case 'table':
                        this.editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()
                        break
                    case 'tableAddColumnAfter':
                        this.editor.chain().focus().addColumnAfter().run()
                        break
                    case 'tableAddRowAfter':
                        this.editor.chain().focus().addRowAfter().run()
                        break
                    case 'tableDeleteColumn':
                        this.editor.chain().focus().deleteColumn().run()
                        break
                    case 'tableDeleteRow':
                        this.editor.chain().focus().deleteRow().run()
                        break
                    case 'tableDeleteTable':
                        this.editor.chain().focus().deleteTable().run()
                        break
                    case 'tableMergeCells':
                        this.editor.chain().focus().mergeCells().run()
                        break
                    case 'tableSplitCell':
                        this.editor.chain().focus().splitCell().run()
                        break
                }

                this.updateToolbarState()
            })
        })

        this.editor.on('selectionUpdate', () => {
            this.updateToolbarState()
        })
    }

    updateToolbarState() {
        if (!this.toolbar) return

        this.toolbar.querySelectorAll('[data-action]').forEach(btn => {
            const action = btn.dataset.action
            let isActive = false

            switch (action) {
                case 'bold':
                    isActive = this.editor.isActive('bold')
                    break
                case 'italic':
                    isActive = this.editor.isActive('italic')
                    break
                case 'strike':
                    isActive = this.editor.isActive('strike')
                    break
                case 'underline':
                    isActive = this.editor.isActive('underline')
                    break
                case 'h2':
                    isActive = this.editor.isActive('heading', { level: 2 })
                    break
                case 'h3':
                    isActive = this.editor.isActive('heading', { level: 3 })
                    break
                case 'h4':
                    isActive = this.editor.isActive('heading', { level: 4 })
                    break
                case 'alignLeft':
                    isActive = this.editor.isActive({ textAlign: 'left' })
                    break
                case 'alignCenter':
                    isActive = this.editor.isActive({ textAlign: 'center' })
                    break
                case 'alignRight':
                    isActive = this.editor.isActive({ textAlign: 'right' })
                    break
                case 'bulletList':
                    isActive = this.editor.isActive('bulletList')
                    break
                case 'orderedList':
                    isActive = this.editor.isActive('orderedList')
                    break
                case 'taskList':
                    isActive = this.editor.isActive('taskList')
                    break
                case 'blockquote':
                    isActive = this.editor.isActive('blockquote')
                    break
                case 'codeBlock':
                    isActive = this.editor.isActive('codeBlock')
                    break
                case 'highlight':
                    isActive = this.editor.isActive('highlight')
                    break
                case 'link':
                    isActive = this.editor.isActive('link')
                    break
            }

            btn.classList.toggle('tiptap-btn-active', isActive)
        })
    }

    setupToggle() {
        if (!this.toggleBtn) return

        // Create highlighted code view
        this.codeHighlight = document.createElement('div')
        this.codeHighlight.className = 'tiptap-code-highlight'
        this.codeHighlight.contentEditable = 'true'
        this.codeHighlight.spellcheck = false
        this.codeHighlight.style.display = 'none'
        this.element.querySelector('.tiptap-wrapper').appendChild(this.codeHighlight)

        // Store raw HTML separately from highlighted view
        this.rawHTML = ''

        this.toggleBtn.addEventListener('click', () => {
            this.isSourceMode = !this.isSourceMode

            if (this.isSourceMode) {
                this.rawHTML = this.editor.getHTML()
                this.codeHighlight.innerHTML = this.highlightHTML(this.rawHTML)
                this.editorContainer.style.display = 'none'
                this.sourceContainer.style.display = 'none'
                this.codeHighlight.style.display = 'block'
                this.toolbar.querySelectorAll('.tiptap-btn:not(.tiptap-toggle-source)').forEach(b => b.style.display = 'none')
                this.toolbar.querySelectorAll('.tiptap-separator').forEach(s => s.style.display = 'none')
                this.toolbar.querySelector('.tiptap-spacer').style.display = 'none'
                this.toggleBtn.textContent = 'WYSIWYG'
                this.toggleBtn.classList.add('tiptap-toggle-active')
                this.codeHighlight.focus()
            } else {
                this.editor.commands.setContent(this.rawHTML)
                this.textarea.value = this.editor.getHTML()
                this.editorContainer.style.display = 'block'
                this.sourceContainer.style.display = 'none'
                this.codeHighlight.style.display = 'none'
                this.toolbar.querySelectorAll('.tiptap-btn:not(.tiptap-toggle-source)').forEach(b => b.style.display = '')
                this.toolbar.querySelectorAll('.tiptap-separator').forEach(s => s.style.display = '')
                this.toolbar.querySelector('.tiptap-spacer').style.display = ''
                this.toggleBtn.textContent = 'HTML'
                this.toggleBtn.classList.remove('tiptap-toggle-active')
            }
        })

        // Sync code highlight changes - extract raw HTML from highlighted content
        this.codeHighlight.addEventListener('input', () => {
            this.rawHTML = this.codeHighlight.textContent
            this.sourceContainer.value = this.rawHTML
            this.textarea.value = this.rawHTML
            this.textarea.dispatchEvent(new Event('input', { bubbles: true }))
        })
    }

    highlightHTML(html) {
        // Escape first, then highlight
        let escaped = html
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')

        // Highlight tags: <tagname and </tagname
        escaped = escaped.replace(/(&lt;\/?)([\w-]+)/g, '$1<span class="tiptap-hl-tag">$2</span>')
        // Highlight attributes: attr=
        escaped = escaped.replace(/\s([\w-]+)(=)/g, ' <span class="tiptap-hl-attr">$1</span>$2')
        // Highlight strings: "value"
        escaped = escaped.replace(/(".*?")/g, '<span class="tiptap-hl-string">$1</span>')
        // Highlight comments
        escaped = escaped.replace(/(&lt;!--[\s\S]*?--&gt;)/g, '<span class="tiptap-hl-comment">$1</span>')

        return escaped
    }

    setupSync() {
        if (!this.sourceContainer) return

        this.sourceContainer.addEventListener('input', () => {
            this.textarea.value = this.sourceContainer.value
            this.textarea.dispatchEvent(new Event('input', { bubbles: true }))
        })
    }

    destroy() {
        this.editor.destroy()
    }
}

function initTiptapEditors() {
    document.querySelectorAll('.tiptap-container:not(.tiptap-initialized)').forEach(container => {
        container.classList.add('tiptap-initialized')
        new TiptapEditor(container, {
            placeholder: container.dataset.placeholder || 'Введите текст...',
        })
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTiptapEditors)
} else {
    initTiptapEditors()
}

// Re-init after Livewire morph updates
document.addEventListener('livewire:initialized', () => {
    Livewire.hook('morph.updated', () => {
        setTimeout(initTiptapEditors, 50)
    })
})
