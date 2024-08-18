<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
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

    .exercise {
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
</style>

<body>

    <?php include '../includes/header.php' ?>

    <div class="container">
        <h1>exercise</h1>
        <div class="exercise">

            <a href="./vocabulary.php" class="link">
                <i class="fas fa-language"></i>
                <span>Vocabulary Test</span>
            </a>

        </div>
    </div>

    <?php include '../includes/footer.php' ?>
</body>

</html>