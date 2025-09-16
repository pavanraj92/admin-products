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
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview']] // ✅ source code button
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
