<script>
    $(document).ready(function() {
        $('#gallery_images').on('change', function(e) {
            $('#galleryPreview .new-image-thumb').remove();
            selectedFiles = Array.from(e.target.files);

            selectedFiles.forEach(function(file, idx) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    $('#galleryPreview').append(`
            <div class="image-thumb new-image-thumb position-relative mb-2 p-1" style="display:inline-block;">
                <img src="${ev.target.result}" alt="${file.name}" style="max-width:109px; max-height:109px; border:1px solid #eee;">
                <button type="button" class="btn btn-danger btn-sm remove-new-image" data-idx="${idx}" style="position:absolute;top:2px;right:2px;">&times;</button>
            </div>
        `);
                };
                reader.readAsDataURL(file);
            });
        });

        $('#customGalleryBox').on('click', function() {
            $('#gallery_images').trigger('click');
        });
        $('#customGalleryBox').on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });
        $('#customGalleryBox').on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });
        $('#customGalleryBox').on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
            let files = e.originalEvent.dataTransfer.files;
            $('#gallery_images')[0].files = files;
            $('#gallery_images').trigger('change');
        });

        $(document).on('click', '.remove-new-image', function() {
            $(this).closest('.new-image-thumb').remove();
        });
    });
</script>

