<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login/");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: calc(100% - 60px);
            max-width: 450px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        h2 {
            font-size: 27px;
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }

        .settings {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .slider-container {
            width: 100%;
            margin-bottom: 10px;
        }

        .slider-container input[type="range"] {
            width: 100%;
        }

        .slider-container .value {
            font-size: 18px;
            color: #343a40;
            margin-top: 5px;
            text-align: center;
        }

        .test-area {
            margin: 17px 0;
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            text-align: center;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .test-area p {
            font-size: 18px;
            color: #343a40;
            margin: 5px 0;
        }

        .test-area i {
            cursor: pointer;
            font-size: 24px;
            color: #777;
            transition: color 0.3s, transform 0.3s;
        }

        .test-area i:hover {
            color: #ff6347;
            transform: scale(1.2);
        }

        .test-area i:active {
            color: #ff4000;
            transform: scale(1.1);
        }

        button {
            background-color: #2f4f4f;
            color: #fff;
            border: none;
            padding: 14px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #ff6347;
        }
    </style>
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