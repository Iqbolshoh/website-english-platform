<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .form-wrapper {
            background-color: #fff;
            padding: 20px;
            border-radius: 9px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 7px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 11px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #ff0000;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>User Login</h1>
            <form id="loginForm" action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Login</button>
            </form>
            <p id="error-message" class="error-message"></p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Formani oddiy yuborishni to'xtatadi

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');

            if (username === '' || password === '') {
                errorMessage.textContent = 'Username and password are required.';
                return;
            }

            // AJAX yordamida so'rov yuborish
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'login.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Yaxshi natija bo'lsa, boshqa sahifaga o'tish yoki muvaffaqiyat haqida xabar
                    if (xhr.responseText === 'success') {
                        window.location.href = 'indep.php'; // Yangi sahifaga o'tish
                    } else {
                        errorMessage.textContent = 'Invalid username or password.';
                    }
                } else {
                    errorMessage.textContent = 'An error occurred. Please try again.';
                }
            };
            xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
        });
    </script>
</body>

</html>