<?php include '../check.php' ?>
<?php include '../last_page.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Text</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../src/css/add.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="justify-center">
        <div class="add-container">

            <h1>Add New Text</h1>

            <form id="textForm" method="post">
                <div class="form-group">
                    <label for="text_title">Text Title<span>*</span></label>
                    <input type="text" id="text_title" name="text_title" required maxlength="150">
                </div>
                <div class="form-group">
                    <label for="text_content">Text Content<span>*</span></label>
                    <textarea id="text_content" name="text_content" required maxlength="2000"></textarea>
                </div>
                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <textarea id="translation" name="translation" required maxlength="2000"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Text</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="../src/js/texts-add.js"></script>

</body>

</html>