
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
