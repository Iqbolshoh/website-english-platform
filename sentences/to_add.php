<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

$user_id = $_SESSION['user_id'];

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wordId = $_POST['word_id'];
    $sentence = $query->validate($_POST['sentence']);
    $translation = $query->validate($_POST['translation']);

    if (!empty($sentence) && !empty($translation) && !empty($user_id)) {
        $data = [
            'user_id' => $user_id,
            'word_id' => $wordId,
            'sentence' => $sentence,
            'translation' => $translation
        ];

        $insertResult = $query->insert('sentences', $data);

        if ($insertResult) {
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
} else {
    $response['message'] = "Invalid request method.";
    $response['success'] = false;
}

echo json_encode($response);
