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
    <title>Sentences</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/dictionary-sentences.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="container">

        <form id="searchForm" onsubmit="return false;">
            <label for="word" style="display: none;">Search Word:</label>
            <input type="text" id="word" name="word" placeholder="Type a word..." required>
            <button type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <div class="container-wrapper">

            <div class="display-flex">
                <select id="languageSelect">
                    <option value="eng">English</option>
                    <option value="uz">Uzbek</option>
                </select>
                <div id="liked-btn-1" class="heart-box">
                    <i class='fas fa-heart' id="liked1"></i>
                </div>
            </div>

            <div class="display-flex">
                <div id="liked-btn-2" class="heart-box">
                    <i class='fas fa-heart' id="liked2"></i>
                </div>
            </div>
        </div>

        <div id="suggestions"></div>
        <div id="result"></div>

    </div>

    <script src="../js/sentences-index.js"></script>

</body>

</html>