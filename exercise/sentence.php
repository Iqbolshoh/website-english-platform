<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

$userId = $_SESSION['user_id'];
$numSentences = (int) ($_GET['num_sentences'] ?? 5);
$filter = $_GET['filter'] ?? 'all';

$results = [];

if ($filter === 'liked') {
    $likedSentences = $query->search('liked_sentences', 'sentence_id', 'WHERE user_id = ?', [$userId], 'i');
    $likedSentenceIds = array_column($likedSentences, 'sentence_id');

    if (!empty($likedSentenceIds)) {
        $placeholders = implode(',', array_fill(0, count($likedSentenceIds), '?'));
        $results = $query->select(
            'sentences',
            'id, sentence, translation',
            "WHERE id IN ($placeholders) AND user_id = ? ORDER BY RAND() LIMIT ?",
            array_merge($likedSentenceIds, [$userId, $numSentences]),
            str_repeat('i', count($likedSentenceIds)) . 'ii'
        );
    }
} else {
    $results = $query->select(
        'sentences',
        'id, sentence, translation',
        'WHERE user_id = ? ORDER BY RAND() LIMIT ?',
        [$userId, $numSentences],
        'ii'
    );
}

function getRandomWords($sentence, $numWords = 8)
{
    $words = explode(' ', $sentence);
    shuffle($words);

    return array_slice($words, 0, $numWords);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentence Test</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/exercise-sentences.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Sentence Test</h1>

        <form action="" method="GET" id="numSentence">
            <div>
                <label for="num_sentences">Tests:</label>
                <select name="num_sentences" id="num_sentences">
                    <option value="5" <?= $numSentences == 5 ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= $numSentences == 10 ? 'selected' : '' ?>>10</option>
                    <option value="15" <?= $numSentences == 15 ? 'selected' : '' ?>>15</option>
                    <option value="20" <?= $numSentences == 20 ? 'selected' : '' ?>>20</option>
                    <option value="30" <?= $numSentences == 30 ? 'selected' : '' ?>>30</option>
                </select>
            </div>

            <div>
                <label for="filter">Filter:</label>
                <select name="filter" id="filter">
                    <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All</option>
                    <option value="liked" <?= $filter == 'liked' ? 'selected' : '' ?>>Liked</option>
                </select>
            </div>
        </form>

        <?php if (empty($results)): ?>
            <div class="information-not-found">
                <i class="fa fa-info-circle"></i>
                <p>No sentence found.</p>
                <a href="../sentences/" class="btn btn-primary">Sentence</a>
            </div>
        <?php else: ?>
            <form action="" method="POST" id="sentence-form">
                <?php foreach ($results as $index => $sentence): ?>
                    <div class="sentences-box">
                        <p><?= htmlspecialchars($sentence['translation']) ?></p>
                        <div class="sentence">
                            <label>
                                <input type="hidden" name="correct_sentences[<?= $index ?>]"
                                    value="<?= htmlspecialchars($sentence['sentence']) ?>">
                                <input type="text" name="sentences[<?= $index ?>]" placeholder="Choose the words" required
                                    readonly>
                            </label>

                            <div class="word-options">
                                <?php
                                $words = getRandomWords($sentence['sentence']);
                                foreach ($words as $word): ?>
                                    <span class="word" data-input="sentences[<?= $index ?>]"><?= htmlspecialchars($word) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <input type="hidden" name="num_sentences" value="<?= $numSentences ?>">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                <input type="hidden" name="total_sentences" value="<?= count($results) ?>">
                <button type="submit">Submit Test</button>
                <button type="button" id="refresh-test">Refresh Test</button>
            </form>

        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
    
    <script src="../js/exercise-sentences.js"></script>

</body>

</html>