<?php

session_start();

include '../config.php';
$query = new Query();

$userId = $_SESSION['user_id'];

$text_id = isset($_POST['text_id']) ? (int) $_POST['text_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($text_id <= 0 || !in_array($action, ['add', 'remove'])) {
    http_response_code(400);
    die('Invalid request');
}

if ($action === 'add') {
    $query->insert('liked_texts', ['user_id' => $userId, 'text_id' => $text_id]);
} elseif ($action === 'remove') {
    $query->delete('liked_texts', 'user_id = ? AND text_id = ?', [$userId, $text_id], 'ii');
}
