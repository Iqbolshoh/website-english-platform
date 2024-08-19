<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$wordId = intval($_GET['word_id']);

$word_name = $query->select(
    'words',
    'word',
    'WHERE id = ?',
    [$wordId],
    'i'
)[0]['word'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentences</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/dictionary-sentences.css">
</head>

<style>

</style>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <form id="searchForm" onsubmit="return false;">
            <label for="word" style="display: none;">Search Word:</label>
            <input type="text" id="word" name="word" placeholder="Type a word..." required>
            <button type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <div class="container-wrapper">
            <div class="display-flex">
                <select id="languageSelect">
                    <option value="eng">English</option>
                    <option value="uz">Uzbek</option>
                </select>
                <div id="liked-btn-1" class="heart-box">
                    <i class='fas fa-heart' id="liked1"></i>
                </div>
            </div>

            <div class="display-flex">
                <button onclick="window.location.href='./add.php?word_id=<?= $wordId ?>'">
                    Add a sentences
                </button>
                <div id="liked-btn-2" class="heart-box">
                    <i class='fas fa-heart' id="liked2"></i>
                </div>
            </div>

        </div>

        <div class="word-container">
            <p class="title"><?= $word_name ?></p>
        </div>

        <input type="hidden" id="wordId" value="<?php echo htmlspecialchars($wordId); ?>">

        <div id="suggestions"></div>
        <div id="result"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hearts = document.querySelectorAll('.heart-box');

            hearts.forEach(heart => {
                heart.addEventListener('click', function () {
                    if (this.classList.contains('liked')) {
                        this.classList.remove('liked');
                    } else {
                        hearts.forEach(h => h.classList.remove('liked'));
                        this.classList.add('liked');
                    }
                });
            });
        });

        $(document).ready(function () {
            let showLiked = 0;

            function debounce(func, wait) {
                let timeout;
                return function () {
                    clearTimeout(timeout);
                    const context = this,
                        args = arguments;
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            function fetchSuggestions(query, lang, liked, wordId) {
                $.ajax({
                    url: "fetch_sentences.php",
                    type: "GET",
                    data: {
                        query: query,
                        lang: lang,
                        liked: liked,
                        word_id: wordId
                    },
                    success: function (response) {
                        $("#suggestions").html(response);
                    },
                    error: function () {
                        $("#suggestions").html("An error occurred.");
                    }
                });
            }

            function handleInput() {
                const query = $("#word").val().trim();
                const lang = $("#languageSelect").val();
                const wordId = $("#wordId").val();
                fetchSuggestions(query, lang, showLiked, wordId);
            }

            function toggleLiked() {
                showLiked = showLiked === 0 ? 1 : 0;
                fetchSuggestions($("#word").val().trim(), $("#languageSelect").val(), showLiked, $("#wordId").val());
                $("#liked-btn-1").toggleClass('liked', showLiked === 1);
            }

            const fetchSuggestionsDebounced = debounce(handleInput, 300);

            $("#word").on("keyup", function () {
                fetchSuggestionsDebounced();
            });

            $("#languageSelect").on("change", function () {
                handleInput();
            });

            $("#liked-btn-1").on("click", function () {
                toggleLiked();
            });

            $("#liked-btn-2").on("click", function () {
                toggleLiked();
            });

            fetchSuggestions("", $("#languageSelect").val(), showLiked, $("#wordId").val());
        });


    </script>
</body>

</html>