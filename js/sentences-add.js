$(document).ready(function () {
    $('#sentenceForm').on('submit', function (e) {
        e.preventDefault();

        $('button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: './to_add.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'The sentence has been added!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#sentenceForm')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message || 'Something went wrong!',
                    });
                }
                $('button[type="submit"]').prop('disabled', false);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while submitting the form.',
                });
                $('button[type="submit"]').prop('disabled', false);
            }
        });
    });
});