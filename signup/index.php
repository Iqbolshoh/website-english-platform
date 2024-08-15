<?php
session_start();

include '../config.php';

$query = new Query();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ../");
    exit;
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $query->validate($_POST['fullname']);
    $email = $query->validate($_POST['email']);
    $username = $query->validate($_POST['username']);
    $password = $query->validate($_POST['password']);

    if ($query->emailExists($email)) {
        $error_message = "Email already exists.";
    } elseif ($query->usernameExists($username)) {
        $error_message = "Username already exists.";
    } else {
        $hashed_password = $query->hashPassword($password);
        $result = $query->registerUser($fullname, $email, $username, $hashed_password);

        if ($result) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $query->getUserIdByUsername($username);
            $_SESSION['username'] = $username;

            echo "<script>
                    window.onload = function() { 
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registration successful',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '../';
                        });
                    };
                  </script>";
        } else {
            $error_message = "An error occurred. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: calc(100% - 60px);
            max-width: 450px;
            box-sizing: border-box;
        }

        h1 {
            margin: 0 0 20px;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-group button {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .text-center {
            text-align: center;
            margin-top: 15px;
        }

        .text-center a {
            color: #007bff;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        #email-message,
        #username-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form id="signupForm" method="post" action="">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <p id="email-message"></p>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <p id="username-message"></p>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Sign Up</button>
            </div>
        </form>
        <div class="text-center">
            <p>Already have an account? <a href="../login/">Login</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('email').addEventListener('input', function () {
            let email = this.value;
            if (email.length > 0) {
                fetch('check_availability.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(email)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        const messageElement = document.getElementById('email-message');
                        if (data.exists) {
                            messageElement.textContent = 'Email is already in use.';
                        } else {
                            messageElement.textContent = '';
                        }
                    });
            }
        });

        document.getElementById('username').addEventListener('input', function () {
            let username = this.value;
            if (username.length > 0) {
                fetch('check_availability.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `username=${encodeURIComponent(username)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        const messageElement = document.getElementById('username-message');
                        if (data.exists) {
                            messageElement.textContent = 'Username is already in use.';
                        } else {
                            messageElement.textContent = '';
                        }
                    });
            }
        });
    </script>

</body>

</html>