<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $word_id = $query->select(
        'sentences',
        'word_id',
        'WHERE id = ?',
        [$id],
        'i'
    )[0]['word_id'];

    $word_name = $query->select(
        'words',
        'word',
        'WHERE id = ?',
        [$word_id],
        'i'
    )[0]['word'];

    echo json_encode(['word' => $word_name]);
}
