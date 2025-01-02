<?php

session_start();

include './config.php';
$query = new Database();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login/");
    exit;
}

$login_page = $_GET['page'] ?? "";

if (isset($_COOKIE['last_page']) && $login_page === "login") {
    $last_page = $_COOKIE['last_page'];
    header("Location: ../$last_page");
    exit;
}

setcookie('last_page',  ".." . $_SERVER['SCRIPT_NAME'], time() + (86400 * 30), "/");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="icon" type="image/png" sizes="16x16" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./`css/home.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/sweetalert2.css">
</head>

<body>

    <header class="site-header">
        <a href="./" class="logo-link">
            <img src="./images/logo.png" alt="logo" class="logo-img">
        </a>
    </header>

    <div class="container">
        <h1 class="main-heading">Welcome to the English Learning Portal</h1>
        <div class="links">
            <a href="dictionary/" class="link-item">
                <i class="fas fa-language link-icon"></i>
                <span class="link-text">Dictionary</span>
            </a>

            <a href="sentences/" class="link-item">
                <i class="fas fa-comment-dots link-icon"></i>
                <span class="link-text">Sentences</span>
            </a>

            <a href="texts/" class="link-item">
                <i class="fas fa-book link-icon"></i>
                <span class="link-text">Texts</span>
            </a>

            <a href="exercise/" class="link-item">
                <i class="fas fa-brain link-icon"></i>
                <span class="link-text">Exercise</span>
            </a>

            <a href="settings/" class="link-item">
                <i class="fa-solid fa-gear link-icon"></i>
                <span class="link-text">Settings</span>
            </a>

        </div>
    </div>

    <footer>
        <div class="footer">
            <p>&copy; 2024 Dictionary Portal. All Rights Reserved.</p>
            <p>Follow us on
                <a href="https://iqbolshoh.uz" target="_blank" class="social-icon"><i class="fas fa-globe"></i></a>
                <a href="https://t.me/iqbolshoh_777" target="_blank" class="social-icon"><i
                        class="fab fa-telegram"></i></a>
                <a href="https://www.instagram.com/iqbolshoh_777" target="_blank" class="social-icon"><i
                        class="fab fa-instagram"></i></a>
            </p>
        </div>
    </footer>

</body>

</html>