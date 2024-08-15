<?php
include '../config.php';

session_start();

$query = new Query();
$userId = $_SESSION['user_id'];

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'eng';
$liked = isset($_GET['liked']) ? $_GET['liked'] : false;
$sentenceSearch = isset($_GET['query']) ? $_GET['query'] : '';

if ($lang == 'uz') {
    if (!empty($sentenceSearch)) {
        $queryParam = $query->validate($sentenceSearch);
        $results = $query->search(
            'sentences',
            '*',
            "WHERE user_id = ? AND translation LIKE ?",
            [$userId, "%$queryParam%"],
            "is"
        );
    } else {
        $results = $query->select(
            'sentences',
            '*',
            'WHERE user_id = ? ORDER BY translation ASC',
            [$userId],
            'i'
        );
    }
} else {
    if (!empty($sentenceSearch)) {
        $queryParam = $query->validate($sentenceSearch);
        $results = $query->search(
            'sentences',
            '*',
            "WHERE user_id = ? AND sentence LIKE ?",
            [$userId, "%$queryParam%"],
            "is"
        );
    } else {
        $results = $query->select(
            'sentences',
            '*',
            'WHERE user_id = ? ORDER BY sentence ASC',
            [$userId],
            'i'
        );
    }
}

$likedSentences = $query->search('liked_sentences', 'sentence_id', 'WHERE user_id = ?', [$userId], 'i');
$likedSentenceIds = array_column($likedSentences, 'sentence_id');

if ($liked) {
    $results = [];
    foreach ($likedSentences as $row) {
        $sentenceId = $row['sentence_id'];
        $queryResult = $query->select('sentences', '*', "WHERE id = ? AND user_id = ?", [$sentenceId, $userId], 'ii');
        if ($queryResult) {
            $results = array_merge($results, $queryResult);
        }
    }
}

if ($results) {
    usort($results, function ($a, $b) use ($sentenceSearch) {
        $sentenceSearchLower = strtolower($sentenceSearch);
        $aStartsWith = strtolower(substr($a['sentence'], 0, strlen($sentenceSearchLower))) === $sentenceSearchLower;
        $bStartsWith = strtolower(substr($b['sentence'], 0, strlen($sentenceSearchLower))) === $sentenceSearchLower;

        if ($aStartsWith && !$bStartsWith)
            return -1;
        if (!$aStartsWith && $bStartsWith)
            return 1;

        return strcmp(strtolower($a['sentence']), strtolower($b['sentence']));
    });

    $html = "<ul>";
    foreach ($results as $index => $row) {
        $sentenceId = "sentence_" . $index;
        $likeId = "heart_" . $index;
        $text = $lang == 'uz' ? htmlspecialchars($row['translation']) : htmlspecialchars($row['sentence']);
        $isLiked = in_array($row['id'], $likedSentenceIds);
        $html .= "<div class='vocabulary'>
            <li id='{$sentenceId}'>" . str_ireplace($sentenceSearch, "<span class='highlight'>{$sentenceSearch}</span>", $text) . "</li>
            <div class='buttons'>
                <i class='fas fa-volume-up' onclick=\"speakText('{$sentenceId}')\"></i>
                <i class='fas fa-heart " . ($isLiked ? 'liked' : '') . "' id='{$likeId}' onclick=\"toggleLike('{$likeId}', '{$row['id']}')\"></i>
                <i class='fas fa-info-circle' onclick='showInfo(" . json_encode(["sentence" => $row["sentence"], "translation" => $row["translation"], "definition" => $row["definition"], "id" => $row["id"]], JSON_HEX_APOS | JSON_HEX_QUOT) . ")'></i>
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
        margin: 100px auto;
        padding: 20px;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        position: relative;
        box-sizing: border-box;
        animation: slideIn 0.3s ease;
    }

    #modalSentence {
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
        word-wrap: break-word;
    }

    .modal-section {
        margin-bottom: 10px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .modal-buttons i {
        cursor: pointer;
        font-size: 24px;
        transition: color 0.3s ease;
    }

    .modal-buttons i:hover {
        color: #007bff;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-200px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<div id="infoModal" class="modal" data-sentence-id="">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalSentence" class="modal-section"></div>
        <div id="modalTranslation" class="modal-section"></div>
        <div id="modalDefinition" class="modal-section"></div>
        <div class="modal-buttons">
            <i class='fas fa-volume-up' onclick="speakTextFromModal()"></i>
            <i class="fa-solid fa-trash" onclick="deleteDefinition()"></i>
        </div>
    </div>
</div>

<style>
    .swal2-popup {
        font-family: 'Arial', sans-serif;
    }

    .swal2-title {
        color: #2c3e50;
        font-size: 1.5rem;
    }

    .swal2-html-container {
        color: #34495e;
        font-size: 1rem;
        display: flex;
    }

    .swal2-confirm {
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.5em 1em;
        margin-top: 1rem;
    }

    .swal2-cancel {
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.5em 1em;
        margin-top: 1rem;
        margin-right: 1rem;
    }

    .swal2-cancel:hover,
    .swal2-confirm:hover {
        background-color: #0062cc;
        color: #fff;
    }

    .swal2-styled {
        box-shadow: 0px 4px 6px -1px rgba(0, 0, 0, 0.2);
    }
</style>

<script>
    function closeModal() {
        var modal = document.getElementById("infoModal");
        modal.classList.remove("fade-in");
        setTimeout(function () {
            modal.style.display = "none";
        }, 300);
    }

    function speakText(elementId) {
        var sentenceText = document.getElementById(elementId).innerText;
        var speech = new SpeechSynthesisUtterance(sentenceText);
        speech.lang = 'en-US';
        speech.rate = 0.9;
        window.speechSynthesis.speak(speech);
    }

    function toggleLike(likeElementId, sentenceId) {
        var likeElement = document.getElementById(likeElementId);
        var isLiked = likeElement.classList.contains("liked");
        var newStatus = isLiked ? 0 : 1;

        $.ajax({
            type: 'POST',
            url: 'like.php',
            data: { sentence_id: sentenceId, status: newStatus },
            success: function (response) {
                if (response === "success") {
                    likeElement.classList.toggle("liked");
                } else {
                    console.error("Error updating like status.");
                }
            }
        });
    }

    function showInfo(info) {
        var modal = document.getElementById("infoModal");
        var modalSentence = document.getElementById("modalSentence");
        var modalTranslation = document.getElementById("modalTranslation");
        var modalDefinition = document.getElementById("modalDefinition");

        modalSentence.innerHTML = info.sentence;
        modalTranslation.innerHTML = info.translation;
        modalDefinition.innerHTML = info.definition;
        modal.dataset.sentenceId = info.id;

        modal.style.display = "block";
        setTimeout(function () {
            modal.classList.add("fade-in");
        }, 10);
    }

    function speakTextFromModal() {
        var modalSentenceText = document.getElementById("modalSentence").innerText;
        var speech = new SpeechSynthesisUtterance(modalSentenceText);
        speech.lang = 'en-US';
        speech.rate = 0.9;
        window.speechSynthesis.speak(speech);
    }

    function deleteDefinition() {
        Swal.fire({
            title: 'Delete Definition',
            html: "Are you sure you want to delete this definition?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                var sentenceId = document.getElementById("infoModal").dataset.sentenceId;
                $.ajax({
                    type: 'POST',
                    url: 'delete.php',
                    data: { sentence_id: sentenceId },
                    success: function (response) {
                        if (response === "success") {
                            Swal.fire({
                                title: 'Deleted!',
                                html: "The definition has been deleted.",
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                closeModal();
                                location.reload();
                            });
                        } else {
                            console.error("Error deleting definition.");
                        }
                    }
                });
            }
        });
    }
</script>