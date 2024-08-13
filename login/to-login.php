<?php
// Include the configuration file
require_once 'config.php';

// Initialize the Query class
$query = new Query();

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Process submitted form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = $query->validate($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = $_POST["password"];
    }

    // Check input errors before querying the database
    if (empty($username_err) && empty($password_err)) {
        // Authenticate the user
        $result = $query->authenticate($username, $password);

        if (count($result) == 1) {
            // Successful login
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            header("Location: dashboard.php"); // Redirect to a secure page
            exit;
        } else {
            $login_err = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            margin: 30px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(100% - 60px);
            max-width: 400px;
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

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
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

        @media (max-width: 768px) {
            .justify-center {
                margin: 20px 0;
            }
        }
    </style>
</head>

<body>
    <div class="justify-center">
        <div class="container">
            <h1>Login</h1>

            <?php
            if (!empty($login_err)) {
                echo '<div class="message error">' . $login_err . '</div>';
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username<span>*</span></label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="password">Password<span>*</span></label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>