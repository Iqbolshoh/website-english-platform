<?php
session_start();

include './config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

</head>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f9;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        width: 100%;
        max-width: 900px;
        margin: 0px auto;
        padding: 20px;
        box-sizing: border-box;
    }

    h1 {
        font-size: 32px;
        color: #343a40;
        text-align: center;
        margin-bottom: 30px;
    }

    .links {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .link {
        display: flex;
        align-items: center;
        background-color: #2f4f4f;
        color: #fff;
        padding: 15px 35px;
        margin: 7px 0;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        font-size: 18px;
        width: 100%;
        max-width: 350px;
        transition: background-color 0.3s, transform 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
    }

    .link i {
        margin-right: 12px;
        font-size: 24px;
    }

    .link .flag {
        width: 30px;
        height: 20px;
    }

    .link:hover {
        background-color: #ff6347;
        transform: translateY(-3px);
    }

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f4f7f9;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #2f4f4f;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        padding: 9px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    header a {
        text-decoration: none;
    }

    .logo {
        width: 70px;
        height: 70px;
        border-radius: 7px;
    }

    @media (max-width: 768px) {
        .link {
            font-size: 16px;
            padding: 15px 30px;
        }

        .link {
            display: flex;
            align-items: center;
            background-color: #2f4f4f;
            color: #fff;
            padding: 15px 27px;
            margin: 5px 0;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }

        .logo {
            width: 45px;
            height: 45px;
        }

        h1 {
            font-size: 26px;
        }

        .link .flag {
            width: 30px;
            height: 16px;
        }

        .container {
            width: calc(100% - 20px);
        }
    }

    .swal2-popup {
        font-family: 'Arial', sans-serif;
    }

    .swal2-title {
        color: #2c3e50;
        font-size: 1.5rem;
    }

    .swal2-html-container {
        color: #34495e;
        font-size: 1rem;
        display: flex;
    }

    .swal2-confirm {
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.5em 1em;
        margin-left: 10px;
    }

    .swal2-cancel {
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.5em 1em;
        margin-right: 10px;
    }

    .swal2-confirm:hover {
        background-color: #218838;
    }

    .swal2-cancel:hover {
        background-color: #c82333;
    }
</style>

<body>
    <header>
        <a href="../">
            <img src="./images/logo.png" alt="logo" class="logo">
        </a>
    </header>

    <div class="container">
        <h1>Welcome to the English Learning Portal</h1>
        <div class="links">
            <a href="dictionary/" class="link">
                <i class="fas fa-language"></i>
                <span>Dictionary</span>
            </a>

            <a href="sentences/" class="link">
                <i class="fas s fa-comment-dots"></i>
                <span>Sentences</span>
            </a>

            <a href="exercise/" class="link">
                <i class="fas fa-brain"></i>
                <span>Exercise</span>
            </a>

            <a href="settings/" class="link">
                <i class="fa-solid fa-gear"></i>
                <span>Settings</span>
            </a>

            <a href="#" class="link" onclick="confirmLogout(); return false;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <?php include 'includes/footer.php' ?>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, log out!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './logout/';
                }
            });
        }
    </script>

</body>

</html>