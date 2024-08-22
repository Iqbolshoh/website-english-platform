<?php

session_start();

$user_id = $_SESSION['user_id'];

include '../model/SentencesModal.php';
$query = new SentencesModel();

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $word_id = $_POST['word_id'];
    $sentence = $query->validate($_POST['sentence']);
    $translation = $query->validate($_POST['translation']);

    $result = $query->createSentence($user_id, $word_id, $sentence, $translation);

    if ($result) {
        $response['message'] = "Sentence added successfully!";
        $response['success'] = true;
    } else {
        $response['message'] = "Failed to add the sentence.";
        $response['success'] = false;
    }
} else {
    $response['message'] = "Please fill in all required fields.";
    $response['success'] = false;
}


echo json_encode($response);
?>