<?php

session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ../");
    exit;
}

include '../config.php';
$query = new Query();

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $query->validate($_POST['fullname']);
    $email = $query->validate($_POST['email']);
    $username = $query->validate($_POST['username']);
    $hashed_password = $query->hashPassword($_POST['password']);

    if ($query->emailExists($email)) {
        $error_message = "Email already exists.";
    } elseif ($query->usernameExists($username)) {
        $error_message = "Username already exists.";
    } else {
        $result = $query->registerUser($fullname, $email, $username, $hashed_password);

        if ($result) {
            $user_id = $query->getUserIdByUsername($username);
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            setcookie('username', $username, time() + (86400 * 30), "/", "", true, true);
            setcookie('session_token', session_id(), time() + (86400 * 30), "/", "", true, true);
            setcookie('user_id', $user_id, time() + (86400 * 30), "/", "", true, true);
?>

            <script>
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
            </script>

<?php
        } else {
            echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '$error_message',
        });
    </script>";
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
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <script src="../js/sweetalert2.js"></script>
    <link rel="stylesheet" href="../css/login_signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="form-container">
        <h1>Sign Up</h1>
        <form id="signupForm" method="post" action="">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required maxlength="255">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required maxlength="255">
                <p id="email-message"></p>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required maxlength="255">
                <p id="username-message"></p>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required maxlength="255">
                    <button type="button" id="toggle-password" class="password-toggle"><i
                            class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" id="submit">Sign Up</button>
            </div>
        </form>
        <div class="text-center">
            <p>Already have an account? <a href="../login/">Login</a></p>
        </div>
    </div>

    <script>
        document.getElementById('email').addEventListener('input', function() {
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

        document.getElementById('username').addEventListener('input', function() {
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

        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const toggleIcon = this.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>