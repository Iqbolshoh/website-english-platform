<?php

session_start();

include '../config.php';
$query = new Query();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$user_id = $_SESSION['user_id'];

$response = array('volume' => 1, 'rate' => 1, 'pitch' => 1);

$settings = $query->select('voice_settings', '*', 'WHERE user_id = ?', [$user_id], 'i');

if ($settings) {
    $response = [
        'volume' => $settings[0]['volume'],
        'rate' => $settings[0]['rate'],
        'pitch' => $settings[0]['pitch']
    ];
}
header('Content-Type: application/json');
echo json_encode($response);

?>

<!-- json kadan darkor  seessiya sar shudanba jsnonba saqla kunad agar tamom shavad udalit kunat voise.json-->