<script>

    /** Для загрузки */
    function changeProfile() {
        $('#file').click();
    }

    $('#file').change(function () {
        if ($(this).val() != '') {
            upload(this);
        }
    });

    function upload(img) {
        var form_data = new FormData();
        form_data.append('file', img.files[0]);
        form_data.append('_token', '{{ csrf_token() }}');
        $('#loading').css('display', 'block');
        $.ajax({
            url: "{{ url('/admin/products/ajax-image-upload') }}",
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.fail) {
                    $('#preview_image').attr('src', '{{ asset('images/no_image.png') }}');
                    alert(data.errors['file']);
                } else {
                    $('#file_name').val(data);
                    $('#preview_image').attr('src', '{{ asset('uploads/single/') }}/' + data);
                }
                $('#loading').css('display', 'none');
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                $('#preview_image').attr('src', '{{ asset('images/no_image.png') }}');
            }
        });
    }

    /** Для удаления при добавлении товара */
    function removeFile() {
        if ($('#file_name').val() != '') {
            if (confirm('Подтвердите удаление данного изображения')) {
                $('#loading').css('display', 'block');
                var form_data = new FormData();
                form_data.append('_method', 'DELETE');
                form_data.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: '{{ url('/admin/products/ajax-remove-image') }}' + '/' + $('#file_name').val(),
                    data: form_data,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#preview_image').attr('src', '{{ asset('images/no_image.png') }}');
                        $('#file_name').val('');
                        $('#loading').css('display', 'none');
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });
            }
        }
    }

    /** Для удаления при редактировании товара */
    function removeFileImg() {
        if ($('a.myimg').data('name') != '') {
            if (confirm('Подтвердите удаление данного изображения')) {
                $('#loading').css('display', 'block');
                var form_data = new FormData();
                form_data.append('_method', 'DELETE');
                form_data.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: '{{ url('/admin/products/ajax-remove-image') }}' + '/' + $('a.myimg').data('name'),
                    data: form_data,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#preview_image').attr('src', '{{ asset('images/no_image.png') }}');
                        $('#file_name').val('');
                        $('#loading').css('display', 'none');
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });
            }
        }
    }

</script>