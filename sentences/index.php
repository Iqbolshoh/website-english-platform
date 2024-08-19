<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentences</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon.ico">
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        height: 100vh;
        box-sizing: border-box;
    }

    .container {
        width: 80%;
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
    }

    form {
        width: 100%;
        margin-bottom: 14px;
        display: flex;
        gap: 10px;
        align-items: center;
        box-sizing: border-box;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }

    #liked-btn-1,
    #liked-btn-2,
    button {
        padding: 10px 20px;
        border: none;
        background-color: #4CAF50;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    button:hover {
        background-color: #45a049;
    }

    .container-wrapper {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 17px;
    }

    .container-wrapper button {
        background-color: #4CAF50;
        color: #fff;
        text-align: center;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .container-wrapper .display-flex:nth-child(1) {
        flex: 1;
    }

    .container-wrapper #liked-btn-1 {
        display: none;
    }

    .container-wrapper button:hover {
        background-color: #4CAF50;
    }

    .container-wrapper select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        background-color: #fff;
        cursor: pointer;
        transition: border-color 0.3s;
        flex: 1;
    }

    .container-wrapper select:hover {
        border-color: #4CAF50;
    }

    .container-wrapper .display-flex {
        display: flex;
        gap: 10px;
    }

    .container-wrapper #liked {
        color: white;
    }

    #suggestions {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        height: calc(100vh - 300px);
        overflow-y: auto;
        padding: 10px;
    }

    #suggestions ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #suggestions .vocabulary {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #suggestions li:hover {
        background-color: #f1f1f1;
    }

    .heart-box i {
        color: white;
        transition: color 0.3s ease;
        cursor: pointer;
    }

    .heart-box.liked i {
        color: red;
    }


    @media (max-width: 768px) {
        .container {
            width: calc(100% - 60px);
            padding: 12px;
        }

        form {
            margin-bottom: 10px;
        }

        input[type="text"] {
            padding: 7px;
        }

        .container-wrapper {
            flex-direction: column;
            gap: 0px;
            margin-bottom: 5px;
        }

        .container-wrapper button,
        .container-wrapper select {
            width: 100%;
            margin-bottom: 10px;
        }

        .container-wrapper #liked-btn-2 {
            display: none;
        }

        .container-wrapper #liked-btn-1 {
            display: block;
        }

        .container-wrapper .display-flex {
            width: 100%;
            display: flex;
            align-items: flex-start;
            box-sizing: border-box;
        }

        .container-wrapper #liked-btn-2 {
            display: none;
        }

        .container-wrapper #liked-btn-1 {
            display: block;
        }

    }
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
                <div id="liked-btn-2" class="heart-box">
                    <i class='fas fa-heart' id="liked2"></i>
                </div>
            </div>
        </div>


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

            function fetchSuggestions(query, lang, liked) {
                $.ajax({
                    url: "fetch_all.php",
                    type: "GET",
                    data: {
                        query: query,
                        lang: lang,
                        liked: liked
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
                fetchSuggestions(query, lang, showLiked);
            }

            function toggleLiked() {
                showLiked = showLiked === 0 ? 1 : 0;
                fetchSuggestions($("#word").val().trim(), $("#languageSelect").val(), showLiked);
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

            fetchSuggestions("", $("#languageSelect").val(), showLiked);
        });
    </script>
</body>

</html>