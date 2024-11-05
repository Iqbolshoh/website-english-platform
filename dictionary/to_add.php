<?php include '../check.php';

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $word = $query->validate(strtolower(trim($_POST['word'])));
    $translation = $query->validate(strtolower(trim($_POST['translation'])));
    $definition = $query->validate($_POST['definition']);

    if (!empty($word) && !empty($translation) && !empty($user_id)) {
        $data = [
            'user_id' => $user_id,
            'word' => $word,
            'translation' => $translation,
            'definition' => $definition
        ];

        $insertResult = $query->insert('words', $data);

        if ($insertResult) {
            $response['message'] = "Word added successfully!";
            $response['success'] = true;
        } else {
            $response['message'] = "Failed to add the word.";
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
