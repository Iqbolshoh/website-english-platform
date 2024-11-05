<?php include '../check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wordId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($wordId > 0) {
        $condition = 'id = ?';
        $params = [$wordId];
        $types = 'i';

        try {
            $query->delete('words', $condition, $params, $types);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete definition.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid word ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
