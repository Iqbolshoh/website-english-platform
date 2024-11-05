<?php include '../check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sentencesId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($sentencesId > 0) {
        $condition = 'id = ?';
        $params = [$sentencesId];
        $types = 'i';

        try {
            $query->delete('sentences', $condition, $params, $types);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete definition.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid word ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}