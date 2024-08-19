<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$user_id = $_SESSION['user_id'];
$settings = $query->select('voice_settings', '*', 'WHERE user_id = ?', [$user_id], 'i');

if ($settings) {
    $volume = $settings[0]['volume'];
    $rate = $settings[0]['rate'];
    $pitch = $settings[0]['pitch'];
} else {
    $volume = 1;
    $rate = 1;
    $pitch = 1;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice Settings</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/voice.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Voice Settings</h2>
        <div class="settings">

            <div class="slider-container">
                <label for="volume">Volume:</label>
                <input type="range" id="volume" name="volume" min="0" max="1" step="0.1" value="<?php echo $volume; ?>">
                <div id="volume-value" class="value">Volume: <?php echo $volume; ?></div>
            </div>

            <div class="slider-container">
                <label for="rate">Rate:</label>
                <input type="range" id="rate" name="rate" min="0.5" max="2" step="0.1" value="<?php echo $rate; ?>">
                <div id="rate-value" class="value">Rate: <?php echo $rate; ?></div>
            </div>

            <div class="slider-container">
                <label for="pitch">Pitch:</label>
                <input type="range" id="pitch" name="pitch" min="0.5" max="2" step="0.1" value="<?php echo $pitch; ?>">
                <div id="pitch-value" class="value">Pitch: <?php echo $pitch; ?></div>
            </div>

            <div class="test-area">
                <p id="test-text">This text is for testing the settings.</p>
                <i class='fas fa-volume-up' onclick="speakTest()"></i>
            </div>

            <button onclick="saveSettings()">Save Settings</button>

        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        let isSpeaking = false;
        let currentUtterance = null;

        function speakTest() {
            const text = document.getElementById('test-text').innerText;
            const volume = document.getElementById('volume').value;
            const rate = document.getElementById('rate').value;
            const pitch = document.getElementById('pitch').value;

            if (isSpeaking) {
                speechSynthesis.cancel();
                isSpeaking = false;
            }

            if (!isSpeaking) {
                currentUtterance = new SpeechSynthesisUtterance(text);
                currentUtterance.volume = volume;
                currentUtterance.rate = rate;
                currentUtterance.pitch = pitch;

                currentUtterance.lang = 'en-US';
                currentUtterance.onend = () => {
                    isSpeaking = false;
                };

                speechSynthesis.speak(currentUtterance);
                isSpeaking = true;
            }
        }

        document.getElementById('volume').addEventListener('input', function () {
            document.getElementById('volume-value').textContent = `Volume: ${this.value}`;
        });

        document.getElementById('rate').addEventListener('input', function () {
            document.getElementById('rate-value').textContent = `Rate: ${this.value}`;
        });

        document.getElementById('pitch').addEventListener('input', function () {
            document.getElementById('pitch-value').textContent = `Pitch: ${this.value}`;
        });

        function saveSettings() {
            const volume = document.getElementById('volume').value;
            const rate = document.getElementById('rate').value;
            const pitch = document.getElementById('pitch').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_voice.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    Swal.fire('Success!', 'Your settings have been saved.', 'success');
                } else {
                    Swal.fire('Error!', 'There was a problem saving your settings.', 'error');
                }
            };
            xhr.send(`user_id=${<?php echo $user_id; ?>}&volume=${volume}&rate=${rate}&pitch=${pitch}`);
        }
    </script>
</body>

</html>