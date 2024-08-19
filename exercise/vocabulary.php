<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

$userId = $_SESSION['user_id'];
$numWords = (int) ($_GET['num_words'] ?? 10);
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter === 'liked') {
    $likedWords = $query->search('liked_words', 'word_id', 'WHERE user_id = ?', [$userId], 'i');
    $likedWordIds = array_column($likedWords, 'word_id');

    if (empty($likedWordIds)) {
        echo '
        <div class="information-not-found">
            <i class="fa fa-heart-broken"></i>
            <p>You haven\'t liked any words yet.</p>
            <a href="../dictionary/add.php" class="btn btn-primary">Add Words</a>
        </div>';
        exit;
    }

    $results = $query->select(
        'words',
        'id, word, translation',
        'WHERE id IN (' . implode(',', $likedWordIds) . ') AND user_id = ? ORDER BY RAND() LIMIT ?',
        [$userId, $numWords],
        'ii'
    );
} else {
    $results = $query->select(
        'words',
        'id, word, translation',
        'WHERE user_id = ? ORDER BY RAND() LIMIT ?',
        [$userId, $numWords],
        'ii'
    );
}

$words = [];
while ($row = array_shift($results)) {
    $words[] = $row;
}

if (empty($words)) {
    echo '
    <div class="information-not-found">
        <i class="fa fa-info-circle"></i>
        <p>No words found.</p>
        <a href="../dictionary/add.php" class="btn btn-primary">Add Words</a>
    </div>';
    exit;
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
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <title>Vocabulary Test</title>
    <link rel="stylesheet" href="../css/exercise-vocabulary.css">
</head>

<style>
    .information-not-found {
        width: 100%;
        height: calc(100vh - 300px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        margin: 0 auto;
        background-color: #f8d7da;
        color: #721c24;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
    }

    .information-not-found i {
        font-size: 30px;
        color: #ff6347;
        margin-bottom: 20px;
    }

    .information-not-found p {
        font-size: 18px;
        margin: 0;
        color: #721c24;
    }
</style>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Vocabulary Test</h1>

        <form id="numWordsForm" action="" method="GET" style="display: flex; align-items: center; gap: 10px;">
            <div>
                <label for="num_words" style="font-size: 16px; color: #333;">Tests:</label>
                <select name="num_words" id="num_words"
                    style="padding: 5px; border-radius: 4px; border: 1px solid #ddd; font-size: 14px;">
                    <option value="10" <?= $numWords == 10 ? 'selected' : '' ?>>10</option>
                    <option value="15" <?= $numWords == 15 ? 'selected' : '' ?>>15</option>
                    <option value="20" <?= $numWords == 20 ? 'selected' : '' ?>>20</option>
                    <option value="30" <?= $numWords == 30 ? 'selected' : '' ?>>30</option>
                    <option value="50" <?= $numWords == 50 ? 'selected' : '' ?>>50</option>
                </select>
            </div>

            <div>
                <label for="filter" style="font-size: 16px; color: #333;">Filter:</label>
                <select name="filter" id="filter"
                    style="padding: 5px; border-radius: 4px; border: 1px solid #ddd; font-size: 14px;">
                    <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All</option>
                    <option value="liked" <?= $filter == 'liked' ? 'selected' : '' ?>>Liked</option>
                </select>
            </div>
        </form>

        <form action="test_result.php" method="POST">
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
        document.getElementById('num_words').addEventListener('change', function () {
            document.getElementById('numWordsForm').submit();
        });

        document.getElementById('filter').addEventListener('change', function () {
            document.getElementById('numWordsForm').submit();
        });
    </script>

    <script>
        window.onload = function () {
            clearRadioButtons();
        };
        function clearRadioButtons() {
            const radioButtons = document.querySelectorAll('input[type="radio"]');
            radioButtons.forEach(radio => {
                radio.checked = false;
            });
        }
    </script>

</body>

</html>