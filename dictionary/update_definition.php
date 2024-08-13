<?php
include '../config.php';

$query = new Query();
$wordId = $_POST['id'];
$word = $_POST['word'];
$translation = $_POST['translation'];
$definition = $_POST['definition'];

$response = ['success' => false, 'message' => ''];

if ($query->update('words', ['word' => $word, 'translation' => $translation, 'definition' => $definition], 'id = ?', [$wordId])) {
    $response['success'] = true;
    $response['word'] = $word;
    $response['translation'] = $translation;
    $response['definition'] = $definition;
} else {
    $response['message'] = 'Failed to update the word.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>