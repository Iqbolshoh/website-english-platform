<?php include '../check.php';

$userId = $_SESSION['user_id'];

$word_id = isset($_POST['word_id']) ? (int) $_POST['word_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($word_id <= 0 || !in_array($action, ['add', 'remove'])) {
    http_response_code(400);
    die('Invalid request');
}

if ($action === 'add') {
    $query->insert('liked_words', ['user_id' => $userId, 'word_id' => $word_id]);
} elseif ($action === 'remove') {
    $query->delete('liked_words', 'user_id = ? AND word_id = ?', [$userId, $word_id], 'ii');
}