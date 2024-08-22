<?php

include '../model/wordsModel.php';
$query = new WordsModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wordId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    try {
        $query->deleteWord($wordId);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete definition.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}