<script>
    let ckEditorInstance;
    ClassicEditor
        .create(document.querySelector('#description'))
        .then(editor => {
            ckEditorInstance = editor;

            editor.ui.view.editable.element.style.minHeight = '250px';
            editor.ui.view.editable.element.style.maxHeight = '250px';
            editor.ui.view.editable.element.style.overflowY = 'auto';

            editor.model.document.on('change:data', () => {
                const descriptionVal = editor.getData();
                $('#description').val(descriptionVal);
                $('#description').trigger('keyup');
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>

