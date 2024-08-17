<?php
session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$wordId = intval($_GET['word_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sentence</title>
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
            width: 100%;
            height: calc(100vh - 250px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 22px;
            width: calc(100% - 60px);
            max-width: 600px;
            box-sizing: border-box;
        }

        label span {
            color: red;
        }

        h1 {
            margin: 20px 0px;
            color: #333;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group textarea {
            width: 100%;
            height: 90px;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            resize: none;
            overflow-y: hidden;
            font-size: 16px;
        }

        .form-group button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
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
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="justify-center">
        <div class="container">
            <h1>Add New Sentence</h1>

            <div id="responseMessage" class="message"></div>

            <form id="sentenceForm" method="post">
                <input type="hidden" name="word_id" value="<?php echo htmlspecialchars($wordId); ?>" />
                <div class="form-group">
                    <label for="sentence">Sentence<span>*</span></label>
                    <textarea id="sentence" name="sentence" required maxlength="200"></textarea>
                </div>
                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <textarea id="translation" name="translation" required maxlength="255"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Sentence</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sentenceForm').on('submit', function (e) {
                e.preventDefault();

                var sentence = $('#sentence').val();
                var translation = $('#translation').val();

                if (sentence.length > 200) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The sentence cannot exceed 200 characters.',
                    });
                    return;
                }
                if (translation.length > 255) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The translation cannot exceed 255 characters.',
                    });
                    return;
                }
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