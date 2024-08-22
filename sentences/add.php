<?php

session_start();

include '../model/SentencesModal.php';
$query = new SentencesModel();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$wordId = intval($_GET['word_id']);

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
        <div class="container">
            <h1>Add New Sentence</h1>

            <div id="responseMessage" class="message"></div>

            <form id="sentenceForm" method="post">
                <input type="hidden" name="word_id" value="<?php echo htmlspecialchars($wordId); ?>" />
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