<?php
session_start();
include '../config.php';

$query = new Query();
$userId = $_SESSION['user_id'];

$sentence_id = isset($_POST['sentence_id']) ? (int) $_POST['sentence_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($sentence_id <= 0 || !in_array($action, ['add', 'remove'])) {
    http_response_code(400);
    die('Invalid request');
}

try {
    if ($action === 'add') {
        $exists = $query->select('liked_sentences', 'id', 'WHERE user_id = ? AND sentence_id = ?', [$userId, $sentence_id], 'ii');
        if (empty($exists)) {
            $query->insert('liked_sentences', ['user_id' => $userId, 'sentence_id' => $sentence_id]);
        }
    } elseif ($action === 'remove') {
        $query->delete('liked_sentences', 'user_id = ? AND sentence_id = ?', [$userId, $sentence_id], 'ii');
    }
    echo 'Success';
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
?>