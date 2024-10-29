<?php
include '../config.php';
$query = new Query();

$response = ['exists' => false];

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if ($query->emailExists($email)) {
        $response['exists'] = true;
    }
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    if ($query->usernameExists($username)) {
        $response['exists'] = true;
    }
}

echo json_encode($response);
exit;
