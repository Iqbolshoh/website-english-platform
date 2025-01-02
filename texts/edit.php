<?php
include '../check.php';

$userId = $_SESSION['user_id'];
$text_id = isset($_GET['text_id']) ? (int)$_GET['text_id'] : 0;

$text = $query->select('texts', '*', "WHERE user_id = ? AND id = ?", [$userId, $text_id], "ii")[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $updatedTitle = $_POST['title'];
    $updatedContent = $_POST['content'];
    $updatedTranslation = $_POST['translation'];

    $sql = "UPDATE texts SET title = ?, content = ?, translation = ? WHERE user_id = ? AND id = ?";
    $params = [$updatedTitle, $updatedContent, $updatedTranslation, $userId, $text_id];
    $types = "ssssi";

    if ($query->executeQuery($sql, $params, $types)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../src/css/add.css">
    <title>Edit Text</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="justify-center">
        <div class="add-container">

            <h1>Edit Text</h1>

            <form id="textForm" method="POST">

                <div class="form-group">
                    <label for="title">Title<span>*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($text['title']); ?>" required maxlength="150">
                </div>

                <div class="form-group">
                    <label for="content">Content<span>*</span></label>
                    <textarea id="content" name="content" class="form-control" required maxlength="2000"><?php echo htmlspecialchars($text['content']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <textarea id="translation" name="translation" class="form-control" required maxlength="2000"><?php echo htmlspecialchars($text['translation']); ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        $(document).ready(function() {
            $("#textForm").submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        const res = JSON.parse(response);

                        if (res.status === 'success') {
                            Swal.fire('Success!', 'Changes saved successfully.', 'success');
                        } else {
                            Swal.fire('Error!', res.message || 'There was an error saving your changes.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error!', 'There was an error saving your changes.', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>