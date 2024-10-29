<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $textId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($textId > 0) {
        $condition = 'id = ?';
        $params = [$textId];
        $types = 'i';

        try {
            $query->delete('texts', $condition, $params, $types);
            echo json_encode(['status' => 'success', 'message' => 'Text successfully deleted.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete text.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid text ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
