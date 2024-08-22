<?php

include '../model/UserModel.php';
$query = new UserModel();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if ($query->emailExists($email)) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
} elseif (isset($_POST['username'])) {
    $username = $_POST['username'];
    if ($query->usernameExists($username)) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
}
