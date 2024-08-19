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
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/exercise-test_result.css">
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

<style></style>