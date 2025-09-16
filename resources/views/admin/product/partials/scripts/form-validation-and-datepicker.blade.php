<script>
    let selectedFiles = [];
    $(document).ready(function() {
        $('.select2').select2();

        $('#published_at').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '0:+10',
            showButtonPanel: true,
            closeText: 'Clear',
            currentText: 'Today',
            minDate: 0,
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                'September', 'October', 'November', 'December'
            ],
            monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                'Nov', 'Dec'
            ],
            dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            beforeShow: function(input, inst) {
                setTimeout(function() {
                    var buttonPane = $(input).datepicker("widget").find(
                        ".ui-datepicker-buttonpane");
                    if (buttonPane.length === 0) {
                        $(input).datepicker("widget").append(
                            '<div class="ui-datepicker-buttonpane ui-widget-content ui-helper-clearfix"><button type="button" class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" onclick="clearDate()">Clear</button></div>'
                        );
                    }
                }, 1);
            }
        });

        window.clearDate = function() {
            $('#published_at').val('');
            $('#published_at').datepicker('hide');
        };

        $.validator.addMethod(
            "alphabetsOnly",
            function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            },
            "Please enter letters only"
        );
        $.validator.addMethod("decimal", function(value, element) {
            return this.optional(element) || /^\d+(\.\d{1,2})?$/.test(value);
        }, "Please enter a valid decimal value (up to 2 decimal places).");

        $('#productForm').validate({
            ignore: [],
            rules: {
                seller_id: {
                    required: true
                },
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 100,
                },
                short_description: {
                    required: true,
                    minlength: 3,
                    maxlength: 500
                },
                description: {
                    required: false,
                    minlength: 3
                },
                primary_category_id: {
                    required: true,
                },
                sku: {
                    required: true,
                    minlength: 0,
                    maxlength: 100
                },
                barcode: {
                    required: false,
                    minlength: 0,
                    maxlength: 100
                },
                regular_price: {
                    required: true,
                    decimal: true
                }
            },
            messages: {
                seller_id: {
                    required: "Please select seller",
                },
                name: {
                    required: "Please enter a name",
                    minlength: "Name must be at least 3 characters long",
                    maxlength: "Maximum length of name must be 100 characters long"
                },
                short_description: {
                    required: "Please enter a short description",
                    minlength: "Short description must be at least 3 characters long",
                    maxlength: "Maximum length of short description must be 500 characters long"
                },
                description: {
                    required: "Please enter description",
                    minlength: "Description must be at least 3 characters long"
                },
                primary_category_id: {
                    required: "Please select category",
                },
                sku: {
                    required: "Please enter sku",
                },
                regular_price: {
                    required: "Please enter regular price",
                }
            },
            submitHandler: function(form) {
                $('#description').val($('#description').summernote('code'));

                const $btn = $('#saveBtn');
                if ($btn.text().trim().toLowerCase() === 'update') {
                    $btn.prop('disabled', true).text('Updating...');
                } else {
                    $btn.prop('disabled', true).text('Saving...');
                }
                form.submit();
            },
            errorElement: 'div',
            errorClass: 'text-danger custom-error',
            errorPlacement: function(error, element) {
                $('.validation-error').hide();
                if (element.attr("id") === "description") {
                    error.insertAfter($('.note-editor'));
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('.select2'));
                } else if (element.closest('.input-group').length) {
                    error.insertAfter(element.closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        let newFiles = [];
        $('#imageInput').on('change', function(event) {
            const input = event.target;
            const preview = $('#imagePreview');
            preview.find('.new-image-container').remove();
            newFiles = [];

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    newFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const html = `
                        <div class="image-container new-image-container" style="position:relative; display:inline-block;">
                            <img src="${e.target.result}" style="max-width:200px; max-height:109px; margin-right:5px;" />
                            <button type="button" class="btn btn-danger btn-sm remove-new-image" 
                                style="position:absolute; top:0; right:0; border-radius:50%;" data-index="${index}">
                                🗑
                            </button>
                        </div>`;
                        preview.append(html);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
