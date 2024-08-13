<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Word</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    <textarea id="definition" name="definition"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Word</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#wordForm').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: './to-add.php', // The URL where the form will be submitted
                    type: 'POST', // Method of sending data
                    data: $(this).serialize(), // Serialize form data
                    success: function (response) {
                        $('#responseMessage').html(response.message); // Display the response message
                        $('#responseMessage').removeClass('error success');
                        if (response.success) {
                            $('#responseMessage').addClass('success');
                        } else {
                            $('#responseMessage').addClass('error');
                        }
                        $('#wordForm')[0].reset(); // Optional: Reset the form after submission
                    },
                    error: function () {
                        $('#responseMessage').html('An error occurred while submitting the form.');
                        $('#responseMessage').removeClass('success').addClass('error');
                    }
                });
            });
        });
    </script>
</body>

</html>