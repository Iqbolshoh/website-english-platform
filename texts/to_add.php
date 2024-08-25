<?php

session_start();

include '../config.php';
$query = new Query();

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $text_title = $_POST['text_title'];
    $text_content = $_POST['text_content'];
    $translation = $_POST['translation'];

    if (!empty($text_title) && !empty($text_content) && !empty($user_id)) {
        $data = [
            'user_id' => $user_id,
            'text_title' => $text_title,
            'text_content' => $text_content,
            'translation' => $translation
        ];

        $insertResult = $query->addText($user_id, $text_title, $text_content, $translation);

        if ($insertResult) {
            $response['message'] = "Text added successfully!";
            $response['success'] = true;
        } else {
            $response['message'] = "Failed to add the text.";
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
