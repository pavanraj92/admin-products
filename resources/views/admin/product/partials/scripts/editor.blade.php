<script>
    $(document).ready(function() {
        $('#description').summernote({
            height: 250, // ✅ editor height
            minHeight: 250,
            maxHeight: 250,
            toolbar: [
                // ✨ Add "code view" toggle button
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview']],
                ['undo', ['undo', 'redo']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    // keep textarea updated
                    $('#description').val(contents);
                    // trigger validation if needed
                    $('#description').trigger('keyup');
                }
            }
        });
    });
</script>
