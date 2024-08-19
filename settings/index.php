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
    <title>Settings</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/link-menu.css">
</head>

<body>

    <?php include '../includes/header.php' ?>

    <div class="container">
        <h1>Settings</h1>

        <div class="settings">

            <a href="voice.php" class="link">
                <i class="fas fa-volume-up"></i>
                <span>Voice settings</span>
            </a>

            <a href="dictionary-pdf.php" class="link">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Save dictionary .pdf</span>
            </a>

            <a href="sentences-pdf.php" class="link">
                <i class="fa-solid fa-download"></i>
                <span>Save sentences .pdf</span>
            </a>

        </div>

    </div>

    <?php include '../includes/footer.php' ?>
</body>

</html>