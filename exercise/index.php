<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/link-menu.css">
</head>

<body>

    <?php include '../includes/header.php' ?>

    <div class="container">
        <h1>Exercise</h1>
        <div class="exercise">

            <a href="./vocabulary.php" class="link">
                <i class="fas fa-language"></i>
                <span>Vocabulary Test</span>
            </a>

            <a href="./sentence.php" class="link">
                <i class="fas fa-comment-dots link-icon"></i>
                <span>Order Sentences</span>
            </a>

        </div>
    </div>

    <?php include '../includes/footer.php' ?>
</body>

</html>