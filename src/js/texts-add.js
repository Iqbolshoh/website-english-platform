$(document).ready(function () {
    $('#textForm').on('submit', function (e) {
        e.preventDefault();

        $('button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: './to_add.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'The text has been added successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        $('#textForm').trigger('reset');
                        $('button[type="submit"]').prop('disabled', false);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message || 'Something went wrong!',
                    }).then(() => {
                        $('button[type="submit"]').prop('disabled', false);
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while submitting the form.',
                }).then(() => {
                    $('button[type="submit"]').prop('disabled', false);
                });
            }
        });
    });
});
