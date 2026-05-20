import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', () => {
    const editorContainers = document.querySelectorAll('.quill-editor-container');

    editorContainers.forEach(container => {
        const targetId = container.getAttribute('data-target');
        const mode = container.getAttribute('data-quill-mode') || 'standard';
        const targetInput = document.getElementById(targetId);
        
        if (!targetInput) return;

        let toolbarOptions = [];

        if (mode === 'inline') {
            toolbarOptions = [
                ['bold', 'italic', 'underline', 'clean']
            ];
        } else {
            toolbarOptions = [
                [{ 'header': [2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ];
        }

        const quill = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            }
        });

        if (targetInput.value) {
            quill.clipboard.dangerouslyPasteHTML(targetInput.value);
        }

        quill.on('text-change', () => {
            targetInput.value = quill.root.innerHTML;
        });
    });
});
