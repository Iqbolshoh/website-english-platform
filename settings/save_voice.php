<?php

include '../config.php';
$query = new Query();

$user_id = $_POST['user_id'];
$volume = $_POST['volume'];
$rate = $_POST['rate'];
$pitch = $_POST['pitch'];

$existingSettings = $query->select('voice_settings', '*', 'WHERE user_id = ?', [$user_id], 'i');

if ($existingSettings) {
    $query->update('voice_settings', [
        'volume' => $volume,
        'rate' => $rate,
        'pitch' => $pitch
    ], 'user_id = ?', [$user_id], 'i');
} else {
    $query->insert('voice_settings', [
        'user_id' => $user_id,
        'volume' => $volume,
        'rate' => $rate,
        'pitch' => $pitch
    ]);
}
?>