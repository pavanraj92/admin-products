<script>
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        document.getElementById('gallery_images').files = dt.files;
    }

    $('#productForm').on('submit', function() {
        updateFileInput();
    });
</script>

