<?php

session_start();

include '../config.php';
$query = new Query();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ../");
    exit;
}

if (isset($_COOKIE['username']) && isset($_COOKIE['session_token'])) {

    if (session_id() !== $_COOKIE['session_token']) {
        session_write_close();
        session_id($_COOKIE['session_token']);
        session_start();
    }

    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['user_id'] = $query->getUserIdByUsername($_COOKIE['username']);

    header("Location: ../");
    exit;
}

if (isset($_POST['submit'])) {
    $input_username = $query->validate($_POST['username']);
    $input_password = $_POST['password'];
    $hashed_password = $query->hashPassword($input_password);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $query->executeQuery($sql, [$input_username, $hashed_password], 'ss');

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        setcookie('username', $input_username, time() + (86400 * 30), "/", "", true, true);
        setcookie('session_token', session_id(), time() + (86400 * 30), "/", "", true, true);
        ?>

        <script>
            window.onload = function () {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Login successful',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '../';
                });
            };
        </script>

        <?php
    } else {
        ?>

        <script>
            window.onload = function () {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Invalid credentials',
                    text: 'The login or password is incorrect',
                    showConfirmButton: true
                });
            };
        </script>

        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <script src="../js/sweetalert2.js"></script>
    <link rel="stylesheet" href="../css/login_signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="container">

        <h1>Login</h1>

        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required maxlength="255">
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
                <button type="submit" name="submit" id="submit">Login</button>
            </div>
        </form>

        <div class="text-center">
            <p>Don't have an account? <a href="../signup/">Sign Up</a></p>
        </div>

    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
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