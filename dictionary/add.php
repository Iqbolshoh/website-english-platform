<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Word</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/add.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="justify-center">
        <div class="container">
            <h1>Add New Word</h1>

            <div id="responseMessage" class="message"></div>

            <form id="wordForm" method="post">
                <div class="form-group">
                    <label for="word">Word<span>*</span></label>
                    <input type="text" id="word" name="word" required maxlength="150">
                </div>
                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <input type="text" id="translation" name="translation" required maxlength="150">
                </div>
                <div class="form-group">
                    <label for="definition">Definition</label>
                    <textarea id="definition" name="definition" maxlength="255"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Word</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        $(document).ready(function () {
            $('#wordForm').on('submit', function (e) {
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
                                title: 'The dictionary entry has been added!',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                $('#wordForm').trigger('reset');
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
    </script>

</body>

</html>