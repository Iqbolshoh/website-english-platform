<?php
include '../check.php';

$userId = $_SESSION['user_id'];
$sentence_id = isset($_GET['sentence_id']) ? (int)$_GET['sentence_id'] : 0;

$sentence = $query->select('sentences', '*', "WHERE user_id = ? AND id = ?", [$userId, $sentence_id], "ii")[0];

$results = $query->select('words', '*', "WHERE user_id = ?", [$userId], "i");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $updatedSentence = $_POST['sentence'];
    $updatedTranslation = $_POST['translation'];
    $updatedWordId = $_POST['word_id'];

    $sql = "UPDATE sentences SET sentence = ?, translation = ?, word_id = ? WHERE user_id = ? AND id = ?";
    $params = [$updatedSentence, $updatedTranslation, $updatedWordId, $userId, $sentence_id];
    $types = "sssii";

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
    <link rel="stylesheet" href="../css/add.css">
    <title>Edit Sentence</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="justify-center">
        <div class="add-container">

            <h1>Edit Sentence</h1>

            <form id="sentenceForm" method="POST">

                <div class="form-group">
                    <label for="word">Select Word<span>*</span></label>
                    <select id="word" name="word_id" required>
                        <option value="">Select a Word</option>
                        <?php foreach ($results as $row): ?>
                            <option value="<?php echo htmlspecialchars($row['id']); ?>"
                                <?php echo $row['id'] == $sentence['word_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['word']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sentence">Sentence<span>*</span></label>
                    <textarea id="sentence" name="sentence" class="form-control" required maxlength="200"><?php echo htmlspecialchars($sentence['sentence']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="translation">Translation<span>*</span></label>
                    <textarea id="translation" name="translation" class="form-control" required maxlength="255"><?php echo htmlspecialchars($sentence['translation']); ?></textarea>
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
            $("#sentenceForm").submit(function(event) {
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