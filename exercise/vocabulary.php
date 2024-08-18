<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

$userId = $_SESSION['user_id'];
$numWords = (int)($_GET['num_words'] ?? 10);

$results = $query->select(
    'words',
    'id, word, translation',
    'WHERE user_id = ? ORDER BY RAND() LIMIT ?',
    [$userId, $numWords],
    'ii'
);

$words = [];
while ($row = array_shift($results)) {
    $words[] = $row;
}

function getRandomOptions($correctWord, $allWords, $numOptions = 4)
{
    $options = [$correctWord];
    while (count($options) < $numOptions) {
        $randomWord = $allWords[array_rand($allWords)];
        if (!in_array($randomWord, $options)) {
            $options[] = $randomWord;
        }
    }
    shuffle($options);
    return $options;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
    <title>Vocabulary Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        .question {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fafafa;
        }

        .question p {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #numWordsForm {
            margin: 15px 0px;
            width: 100%;
        }

        #numWordsForm select {
            width: 120px;
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Vocabulary Test</h1>

        <form id="numWordsForm" action="" method="GET" style="display: flex; align-items: center; gap: 10px;">
            <label for="num_words" style="font-size: 16px; color: #333;">Number of Words:</label>
            <select name="num_words" id="num_words" style="padding: 5px; border-radius: 4px; border: 1px solid #ddd; font-size: 14px;">
                <option value="10" <?= $numWords == 10 ? 'selected' : '' ?>>10</option>
                <option value="15" <?= $numWords == 15 ? 'selected' : '' ?>>15</option>
                <option value="20" <?= $numWords == 20 ? 'selected' : '' ?>>20</option>
                <option value="30" <?= $numWords == 30 ? 'selected' : '' ?>>30</option>
                <option value="50" <?= $numWords == 50 ? 'selected' : '' ?>>50</option>
            </select>
            <button type="submit" style="padding: 5px 15px; border-radius: 4px; border: none; background-color: #007bff; color: #fff; font-size: 14px; cursor: pointer;">
                Refresh
            </button>
        </form>


        <form action="submit_test.php" method="POST">
            <?php foreach ($words as $index => $word): ?>
                <div class="question">
                    <p><?= $index + 1, ") ", htmlspecialchars($word['translation']) ?></p>
                    <?php
                    $allWords = array_column($words, 'word');
                    $options = getRandomOptions($word['word'], $allWords);
                    ?>
                    <?php foreach ($options as $optionIndex => $option): ?>
                        <label>
                            <input type="radio" name="answers[<?= $index ?>]" value="<?= htmlspecialchars($option) ?>">
                            <?= htmlspecialchars($option) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="total_questions" value="<?= count($words) ?>">
            <input type="hidden" name="words_data" value="<?= htmlspecialchars(json_encode($words)) ?>">
            <button type="submit">Submit Test</button>
        </form>
    </div>

    <script>
        document.getElementById('num_words').addEventListener('change', function() {
            document.getElementById('numWordsForm').submit();
        });
    </script>

<script>
    // Sahifa yuklanganda radio tugmachalarni tozalash funksiyasi
    window.onload = function() {
        clearRadioButtons();
    };

    // Radio tugmachalarni tozalash funksiyasi
    function clearRadioButtons() {
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.checked = false;
        });
    }
</script>

</body>

</html>