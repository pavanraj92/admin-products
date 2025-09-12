<script>
    $(document).on('click', '.remove-existing-image', function() {
        var btn = $(this);
        var imageId = btn.data('id');
        if (!imageId) return;
        
        $.ajax({
            url: '{{ route('admin.products.image.delete', ':id') }}'.replace(':id', imageId),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    btn.closest('.image-thumb').remove();
                } else {
                    alert('Failed to delete image.');
                }
            },
            error: function() {
                alert('Error deleting image.');
            }
        });            
    });
</script>

