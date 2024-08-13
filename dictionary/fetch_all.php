<?php
include '../config.php';

$query = new Query();
$userId = 1;

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'eng';
$liked = $_GET['liked'];
$WordSearch = isset($_GET['query']) ? $_GET['query'] : '';

if ($lang == 'uz') {
    if (!empty($WordSearch)) {
        $queryParam = $query->validate($WordSearch);
        $results = $query->search('words', '*', "WHERE translation LIKE ?", ["%$queryParam%"], "s");
    } else {
        $results = $query->select('words', '*', 'ORDER BY translation ASC');
    }
} else {
    if (!empty($WordSearch)) {
        $queryParam = $query->validate($WordSearch);
        $results = $query->search('words', '*', "WHERE word LIKE ?", ["%$queryParam%"], "s");
    } else {
        $results = $query->select('words', '*', 'ORDER BY word ASC');
    }
}

$likedWords = $query->search('liked_words', 'word_id', 'WHERE user_id = ?', [$userId], 'i');
$likedWordIds = array_column($likedWords, 'word_id');

if ($liked) {
    $results = [];
    foreach ($likedWords as $row) {
        $wordId = $row['word_id'];
        $queryResult = $query->select('words', '*', "WHERE id = ?", [$wordId], 'i');
        if ($queryResult) {
            $results = array_merge($results, $queryResult);
        }
    }
}

if ($results) {
    usort($results, function ($a, $b) use ($WordSearch) {
        $wordSearchLower = strtolower($WordSearch);
        $aStartsWith = strtolower(substr($a['word'], 0, strlen($wordSearchLower))) === $wordSearchLower;
        $bStartsWith = strtolower(substr($b['word'], 0, strlen($wordSearchLower))) === $wordSearchLower;

        if ($aStartsWith && !$bStartsWith)
            return -1;
        if (!$aStartsWith && $bStartsWith)
            return 1;

        return strcmp(strtolower($a['word']), strtolower($b['word']));
    });

    $html = "<ul>";
    foreach ($results as $index => $row) {
        $wordId = "word_" . $index;
        $likeId = "heart_" . $index;
        $text = $lang == 'uz' ? htmlspecialchars($row['translation']) : htmlspecialchars($row['word']);
        $isLiked = in_array($row['id'], $likedWordIds);
        $html .= "<div class='vocabulary'>
            <li id='{$wordId}'>" . str_ireplace($WordSearch, "<span class='highlight'>{$WordSearch}</span>", $text) . "</li>
            <div class='buttons'>
                <i class='fas fa-volume-up' onclick=\"speakText('{$wordId}')\"></i>
                <i class='fas fa-heart " . ($isLiked ? 'liked' : '') . "' id='{$likeId}' onclick=\"toggleLike('{$likeId}', '{$row['id']}')\"></i>
                <i class='fas fa-info-circle' onclick='showInfo(" . json_encode(["word" => $row["word"], "translation" => $row["translation"], "definition" => $row["definition"], "id" => $row["id"]], JSON_HEX_APOS | JSON_HEX_QUOT) . ")'></i>
            </div>
        </div>";
    }
    $html .= "</ul>";
    echo $html;
} else {
    echo "No results found.";
}
?>

<style>
    .vocabulary {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .vocabulary i {
        font-size: 21px;
    }

    .vocabulary .buttons {
        display: flex;
        gap: 5px;
    }

    .vocabulary span {
        color: red;
    }

    .vocabulary:hover {
        background-color: #eee;
    }

    .fa-heart {
        color: #ddd;
        cursor: pointer;
        transition: color 0.3s;
    }

    .fa-heart.liked {
        color: red;
    }

    .fa-info-circle,
    .fa-volume-up,
    .fa-trash {
        color: #777;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .fa-info-circle:hover,
    .fa-volume-up:hover,
    .fa-trash:hover {
        color: #007bff;
        transform: scale(1.2);
    }

    .fa-info-circle:active,
    .fa-volume-up:active,
    .fa-trash:active {
        color: #0056b3;
        transform: scale(1.1);
    }

    .fa-heart:hover {
        transform: scale(1.2);
    }

    .fa-heart:active {
        transform: scale(1.1);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        transition: opacity 0.3s ease, visibility 0.3s ease;
        visibility: hidden;
        opacity: 0;
    }

    .modal.fade-in {
        display: block;
        visibility: visible;
        opacity: 1;
    }

    .modal-content {
        background-color: #ffffff;
        margin: 170px auto;
        padding: 20px;
        border-radius: 12px;
        width: 80%;
        max-width: 600px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        position: relative;
        box-sizing: border-box;
    }

    .close {
        color: #000;
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover,
    .close:focus {
        color: #e74c3c;
    }

    #modalWord {
        margin-bottom: 10px;
        font-size: 20px;
        line-height: 1.5;
        color: #2c3e50;
        font-weight: bold;
    }

    #modalTranslation {
        margin-bottom: 10px;
        font-size: 18px;
        line-height: 1.5;
        color: #16a085;
    }

    #modalDefinition {
        font-size: 16px;
        line-height: 1.5;
        color: #34495e;
    }

    .modal-section {
        margin-bottom: 10px;
    }

    .modal-buttons {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }

    .modal-buttons i {
        font-size: 21px;
        cursor: pointer;
        transition: color 0.3s, transform 0.3s;
    }
</style>

<div id="infoModal" class="modal" data-word-id="">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalWord" class="modal-section"></div>
        <div id="modalTranslation" class="modal-section"></div>
        <div id="modalDefinition" class="modal-section"></div>
        <div class="modal-buttons">
            <i class='fas fa-volume-up' onclick="speakTextFromModal()"></i>
            <i class="fa-solid fa-trash" onclick="deleteDefinition()"></i>
        </div>
    </div>
</div>

<script>
    function speakText(id) {
        const text = document.getElementById(id).textContent;
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'en-US';
        window.speechSynthesis.speak(utterance);
    }

    function speakTextFromModal() {
        const text = document.getElementById('modalWord').textContent.replace('Word: ', '');
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'en-US';
        window.speechSynthesis.speak(utterance);
    }

    function toggleLike(likeId, wordId) {
        const icon = document.getElementById(likeId);
        const isLiked = icon.classList.contains('liked');
        const action = isLiked ? 'remove' : 'add';
        icon.classList.toggle('liked');

        fetch('toggle_like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `word_id=${wordId}&action=${action}`
        });
    }

    function showInfo(wordInfo) {
        const info = wordInfo;
        document.getElementById('modalWord').textContent = `Word: ${info.word}`;
        document.getElementById('modalTranslation').textContent = `Translation: ${info.translation}`;
        document.getElementById('modalDefinition').textContent = `Definition: ${info.definition}`;
        document.getElementById('infoModal').dataset.wordId = info.id;
        document.getElementById('infoModal').classList.add('fade-in');
    }

    function closeModal() {
        document.getElementById('infoModal').classList.remove('fade-in');
    }

    function deleteDefinition() {
        const wordId = document.getElementById('infoModal').dataset.wordId;
        const word = document.getElementById('modalWord').textContent.replace('Word: ', '');

        if (confirm(`Are you sure you want to delete the word "${word}"?`)) {
            $.ajax({
                url: 'delete_definition.php',
                type: 'POST',
                data: {
                    id: wordId
                },
                success: function (response) {
                    closeModal();
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error('Error deleting definition:', status, error);
                    alert('Failed to delete the definition. Please try again.');
                }
            });
        }
    }
</script>