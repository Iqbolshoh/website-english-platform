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
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.6.15/sweetalert2.min.css">
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
                    <input type="text" id="word" name="word" required>
                </div>
                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <input type="text" id="translation" name="translation" required>
                </div>
                <div class="form-group">
                    <label for="definition">Definition</label>
                    <textarea id="definition" name="definition" maxlength="500"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Word</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.6.15/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#wordForm').on('submit', function (e) {
                e.preventDefault();

                // Validate the Definition length
                var definition = $('#definition').val();
                if (definition.length > 500) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The definition cannot exceed 500 characters.',
                    });
                    return;
                }

                $.ajax({
                    url: './to-add.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'The dictionary entry has been added!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#wordForm')[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message || 'Something went wrong!',
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while submitting the form.',
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>