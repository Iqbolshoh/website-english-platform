<?php
include '../config.php';

$query = new Query();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the word ID from POST data
    $wordId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($wordId > 0) {
        // Prepare the condition and parameters for the deletion
        $condition = 'id = ?';
        $params = [$wordId];
        $types = 'i'; // 'i' for integer

        // Call the delete method
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
?>