<?php

session_start();

include '../config.php';

$query = new Query();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ../");
    exit;
}

if (isset($_POST['submit'])) {
    $input_username = $query->validate($_POST['username']);
    $input_password = $query->validate($_POST['password']);

    $hashed_password = $query->hashPassword($input_password);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $query->executeQuery($sql, [$input_username, $hashed_password], 'ss');

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo "<script>
                window.onload = function() { 
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
              </script>";
    } else {

        echo "<script>
                window.onload = function() { 
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Invalid credentials',
                        text: 'The login or password is incorrect',
                        showConfirmButton: true
                    });
                };
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            max-width: 400px;
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Login</button>
            </div>
        </form>
        <div class="text-center">
            <p>Don't have an account? <a href="../signup/">Sign Up</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>

</html>