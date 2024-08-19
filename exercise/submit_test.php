<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

$userId = $_SESSION['user_id'];
$answers = $_POST['answers'] ?? [];

$totalQuestions = (int) ($_POST['total_questions'] ?? 0);
$words = json_decode($_POST['words_data']);
$correctAnswers = 0;


for ($i = 0; $i < $totalQuestions; $i++) {
    if ($answers[$i] == $words[$i]->word) {
        $correctAnswers++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
    <title>Test Results</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Test Results</h1>
        <p>Total Questions: <?= $totalQuestions ?></p>
        <p>Correct Answers: <?= $correctAnswers ?></p>
        <p>Score: <?= round(($correctAnswers / $totalQuestions) * 100, 2) ?>%</p>
        <a href="vocabulary.php">Retake Test</a>
    </div>
</body>

</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        width: calc(100% - 60px);
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
    }

    p {
        font-size: 18px;
        color: #555;
        margin-bottom: 10px;
    }

    .container a {
        display: inline-block;
        padding: 10px 20px;
        text-decoration: none;
        color: #007bff;
        border: 1px solid #007bff;
        border-radius: 4px;
        background-color: #fff;
        font-size: 16px;
    }

    .container a:hover {
        background-color: #007bff;
        color: #fff;
    }
</style>