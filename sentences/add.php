<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

$wordId = isset($_GET['word_id']) ? intval($_GET['word_id']) : 0;
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$results = [];
$word_name = "";

if ($wordId) {
    $word_name = $query->select(
        'words',
        'word',
        'WHERE id = ? AND user_id = ?',
        [$wordId, $userId],
        'ii'
    )[0]['word'];
} else {
    $results = $query->select(
        'words',
        '*',
        'WHERE user_id = ? ORDER BY word ASC',
        [$userId],
        'i'
    );
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sentence</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/add.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="justify-center">
        <div class="add-container">

            <h1>Add New Sentence</h1>

            <div id="responseMessage" class="message"></div>

            <form id="sentenceForm" method="post">
                <?php if ($wordId): ?>
                    <div class="form-group">
                        <label for="word">Selected Word</label>
                        <input type="hidden" id="word" name="word_id" value="<?php echo htmlspecialchars($wordId); ?>">
                        <select disabled>
                            <option>
                                <?php echo htmlspecialchars($word_name); ?>
                            </option>
                        </select>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="word">Select Word<span>*</span></label>
                        <select id="word" name="word_id" required>
                            <option value="">Select a Word</option>
                            <?php foreach ($results as $row): ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <?php echo htmlspecialchars($row['word']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="sentence">Sentence<span>*</span></label>
                    <textarea id="sentence" name="sentence" required maxlength="200"></textarea>
                </div>
                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <textarea id="translation" name="translation" required maxlength="255"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Sentence</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="../js/sentences-add.js"></script>
</body>

</html>