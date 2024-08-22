<?php

include '../model/SentencesModal.php';
$query = new SentencesModel();

$sentencesId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

try {
    $query->deleteSentence($sentencesId);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete definition.']);
}