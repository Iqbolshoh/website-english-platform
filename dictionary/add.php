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
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon.ico">
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        height: 100vh;
    }

    .justify-center {
        margin: 30px 0px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px 20px 10px 20px;
        width: calc(100% - 60px);
        max-width: 600px;
        box-sizing: border-box;
    }

    label span {
        color: red;
    }

    h1 {
        margin: 0;
        color: #333;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group textarea {
        resize: none;
        height: 100px;
    }

    .form-group button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-group button:hover {
        background-color: #0056b3;
    }

    .message {
        margin-top: 10px;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .message.success {
        color: #28a745;
    }

    .message.error {
        color: #dc3545;
    }

    #word,
    #translation,
    textarea {
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .justify-center {
            margin: 20px 0px;
        }
    }
</style>

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