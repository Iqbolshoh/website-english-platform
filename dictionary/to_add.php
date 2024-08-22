<?php

session_start();

include '../model/wordsModel.php';
$query = new WordsModel();

header('Content-Type: application/json');

$response = [];

$user_id = $_SESSION['user_id'];
$word = $query->validate(strtolower($_POST['word']));
$translation = $query->validate(strtolower($_POST['translation']));
$definition = $query->validate($_POST['definition']);

$result = $query->createWord($user_id, $word, $translation, $definition);

if ($result) {
    $response['message'] = "Word added successfully!";
    $response['success'] = true;
} else {
    $response['message'] = "Failed to add the word.";
    $response['success'] = false;
}

echo json_encode($response);