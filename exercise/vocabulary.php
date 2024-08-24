<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

$userId = $_SESSION['user_id'];
$numWords = (int) ($_GET['num_words'] ?? 5);
$filter = $_GET['filter'] ?? 'all';

$results = [];

if ($filter === 'liked') {
    $likedWords = $query->search('liked_words', 'word_id', 'WHERE user_id = ?', [$userId], 'i');
    $likedWordIds = array_column($likedWords, 'word_id');

    if (!empty($likedWordIds)) {
        $placeholders = implode(',', array_fill(0, count($likedWordIds), '?'));
        $types = str_repeat('i', count($likedWordIds) + 2);
        $params = array_merge($likedWordIds, [$userId, $numWords]);
        $results = $query->select(
            'words',
            'id, word, translation',
            "WHERE id IN ($placeholders) AND user_id = ? ORDER BY RAND() LIMIT ?",
            $params,
            $types
        );
    }
} else {
    $results = $query->select(
        'words',
        'id, word, translation',
        'WHERE user_id = ? ORDER BY RAND() LIMIT ?',
        [$userId, $numWords],
        'ii'
    );
}

function getRandomOptions($correctWord, $allWords, $numOptions = 4)
{
    $options = [$correctWord];
    $allWordsCount = count($allWords);

    if ($allWordsCount > 1) {
        foreach ($allWords as $randomWord) {
            if ($randomWord !== $correctWord && !in_array($randomWord, $options)) {
                $options[] = $randomWord;
            }
            if (count($options) >= $numOptions) {
                break;
            }
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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
        }

        .correct {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Vocabulary Test</h1>

        <form id="numWordsForm" action="" method="GET" style="display: flex; align-items: center; gap: 10px;">
            <div>
                <label for="num_words" style="font-size: 16px; color: #333;">Number of Words:</label>
                <select name="num_words" id="num_words"
                    style="padding: 5px; border-radius: 4px; border: 1px solid #ddd; font-size: 14px;">
                    <option value="5" <?= $numWords == 5 ? 'selected' : '' ?>>5</option>
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

        <?php if (empty($results)): ?>
            <div class="information-not-found">
                <i class="fa fa-info-circle"></i>
                <p>No words found.</p>
                <a href="../dictionary/" class="btn btn-primary">Dictionary</a>
            </div>
        <?php else: ?>
            <form id="testForm" action="test_result.php" method="POST">
                <?php foreach ($results as $index => $word): ?>
                    <div class="question">
                        <p><?= ($index + 1) . ") " . htmlspecialchars($word['translation']) ?></p>
                        <?php
                        $allWords = array_column($results, 'word');
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
                <input type="hidden" name="total_questions" value="<?= count($results) ?>">
                <input type="hidden" name="words_data" value="<?= htmlspecialchars(json_encode($results)) ?>">
                <button type="submit">Submit Test</button>
                <button type="button" id="refresh-test">Refresh Test</button>
            </form>

        <?php endif; ?>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const answers = formData.getAll('answers[]');
            const totalQuestions = parseInt(formData.get('total_questions'), 10);
            const wordsData = JSON.parse(formData.get('words_data'));

            let correctCount = 0;
            let allCorrect = true;

            wordsData.forEach((word, index) => {
                const correctWord = word.word;
                const selectedAnswer = answers[index] || '';

                const questionDiv = form.querySelector(`.question:nth-child(${index + 1})`);
                const labels = questionDiv.querySelectorAll('label');

                labels.forEach(label => {
                    const radio = label.querySelector('input[type="radio"]');
                    if (radio.value === correctWord) {
                        label.classList.add('correct');
                        if (radio.checked) {
                            correctCount++;
                        }
                    } else if (radio.checked && radio.value !== correctWord) {
                        label.classList.add('error');
                        allCorrect = false;
                    }
                });
            });

            Swal.fire({
                title: "Test Results",
                text: `You got ${correctCount} out of ${totalQuestions} correct!`,
                icon: "success",
                confirmButtonText: 'OK'
            });

            form.querySelectorAll('input[type="radio"]').forEach(input => {
                input.disabled = true;
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('refresh-test').addEventListener('click', () => {
                window.location.reload();
            });
        });
    </script>

</body>

</html>