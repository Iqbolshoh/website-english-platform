<?php

session_start();

include '../model/SentencesModal.php';
$query = new SentencesModel();

$userId = $_SESSION['user_id'];

$sentence_id = isset($_POST['sentence_id']) ? (int) $_POST['sentence_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($sentence_id <= 0 || !in_array($action, ['add', 'remove'])) {
    http_response_code(400);
    die('Invalid request');
}

try {
    if ($action === 'add') {
        $exists = $query->select('liked_sentences', 'id', "WHERE user_id = $userId AND sentence_id = $sentence_id");
        if (empty($exists)) {
            $query->insert('liked_sentences', ['user_id' => $userId, 'sentence_id' => $sentence_id]);
        }
    } elseif ($action === 'remove') {
        $query->delete('liked_sentences', "user_id = $userId AND sentence_id = $sentence_id");
    }
    echo 'Success';
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
?>