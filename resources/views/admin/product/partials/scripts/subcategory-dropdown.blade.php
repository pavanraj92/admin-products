<script>
    $(document).ready(function() {
        $('.select2').select2();

        function loadSubcategories(primaryCategoryId, selectedSubcategories = []) {
            let url = "{{ route('admin.categories.nested_subcategories', ':id') }}";
            url = url.replace(':id', primaryCategoryId);

            $('#subcategory_id').empty();

            if (primaryCategoryId) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#subcategory_id').append(
                            '<option value="" disabled>Select Sub Categories</option>');

                        response.forEach(function(subcategory) {
                            let selected = selectedSubcategories.includes(String(subcategory.id)) ? 'selected' : '';
                            $('#subcategory_id').append(
                                `<option value="${subcategory.id}" ${selected}>${subcategory.title}</option>`
                            );

                            if (subcategory.children && subcategory.children.length > 0) {
                                subcategory.children.forEach(function(subsub) {
                                    let selected = selectedSubcategories.includes(String(subsub.id)) ? 'selected' : '';
                                    $('#subcategory_id').append(
                                        `<option value="${subsub.id}" ${selected}>-- ${subsub.title}</option>`
                                    );
                                });
                            }
                        });

                        $('#subcategory_id').val(selectedSubcategories.map(String)).trigger('change');
                    },
                    error: function() {
                        $('#subcategory_id').append('<option value="">Failed to load</option>');
                    }
                });
            }
        }

        @if (isset($product) && $product->primary_category_id)
            let selectedSubcategories = @json($product->categories->pluck('id')->toArray()).map(String);
            loadSubcategories('{{ $product->primary_category_id }}', selectedSubcategories);
        @endif

        $('#primary_category_id').on('change', function() {
            loadSubcategories($(this).val(), []);
        });
    });
</script>

