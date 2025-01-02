<?php include '../check.php' ?>
<?php include '../last_page.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Word</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../src/css/add.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="justify-center">
        <div class="add-container">
            <h1>Add New Word</h1>

            <form id="wordForm" method="post">
                <div class="form-group">
                    <label for="word">Word<span>*</span></label>
                    <input type="text" id="word" name="word" required maxlength="150">
                </div>
                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <input type="text" id="translation" name="translation" required maxlength="150">
                </div>
                <div class="form-group">
                    <label for="definition">Definition</label>
                    <textarea id="definition" name="definition" maxlength="255"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Word</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="../src/js/dictionary-add.js"></script>

</body>

</html>