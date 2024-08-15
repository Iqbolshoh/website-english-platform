<?php
include '../config.php';

session_start();

$query = new Query();
$userId = $_SESSION['user_id'];

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'eng';
$liked = isset($_GET['liked']) ? $_GET['liked'] : false;
$WordSearch = isset($_GET['query']) ? $_GET['query'] : '';
$WordSearch = strtolower($WordSearch);

if ($lang == 'uz') {
    if (!empty($WordSearch)) {
        $queryParam = $query->validate($WordSearch);
        $results = $query->search(
            'words',
            '*',
            "WHERE user_id = ? AND translation LIKE ?",
            [$userId, "%$queryParam%"],
            "is"
        );
    } else {
        $results = $query->select(
            'words',
            '*',
            'WHERE user_id = ? ORDER BY translation ASC',
            [$userId],
            'i'
        );
    }
} else {
    if (!empty($WordSearch)) {
        $queryParam = $query->validate($WordSearch);
        $results = $query->search(
            'words',
            '*',
            "WHERE user_id = ? AND word LIKE ?",
            [$userId, "%$queryParam%"],
            "is"
        );
    } else {
        $results = $query->select(
            'words',
            '*',
            'WHERE user_id = ? ORDER BY word ASC',
            [$userId],
            'i'
        );
    }
}

$likedWords = $query->search('liked_words', 'word_id', 'WHERE user_id = ?', [$userId], 'i');
$likedWordIds = array_column($likedWords, 'word_id');

if ($liked) {
    $results = [];
    foreach ($likedWords as $row) {
        $wordId = $row['word_id'];
        $queryResult = $query->select('words', '*', "WHERE id = ? AND user_id = ?", [$wordId, $userId], 'ii');
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
                <i class='fas fa-heart " . ($isLiked ? 'liked' : '') . "' id='{$likeId}' onclick=\"Liked('{$likeId}', '{$row['id']}')\"></i>
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
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .vocabulary i {
        font-size: 21px;
    }

    .vocabulary .buttons {
        display: flex;
        gap: 10px;
    }

    .vocabulary span.highlight {
        background-color: yellow;
    }

    .vocabulary:hover {
        background-color: #f5f5f5;
    }

    .fa-heart {
        color: #ddd;
        cursor: pointer;
        transition: color 0.3s, transform 0.3s;
    }

    .fa-heart.liked {
        color: red;
    }

    .fa-info-circle,
    .fa-volume-up,
    .fa-trash {
        color: #777;
        transition: color 0.3s, transform 0.3s;
    }

    .fa-info-circle:hover,
    .fa-volume-up:hover,
    .fa-trash:hover {
        color: #ff6347;
        transform: scale(1.2);
    }

    .fa-info-circle:active,
    .fa-volume-up:active,
    .fa-trash:active {
        color: #ff4000;
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
        color: #ff6347;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let isSpeaking = false;
    let currentUtterance = null;

    function speakText(id) {
        const text = document.getElementById(id).innerText;

        if (isSpeaking) {
            speechSynthesis.cancel();
            isSpeaking = false;
        }

        if (!isSpeaking) {
            currentUtterance = new SpeechSynthesisUtterance(text);
            currentUtterance.lang = 'en-US';
            currentUtterance.onend = () => {
                isSpeaking = false;
            };

            speechSynthesis.speak(currentUtterance);
            isSpeaking = true;
        }
    }

    function speakTextFromModal() {
        const text = document.getElementById('modalWord').innerText;

        if (isSpeaking) {
            speechSynthesis.cancel();
            isSpeaking = false;
        }

        if (!isSpeaking) {
            currentUtterance = new SpeechSynthesisUtterance(text);
            currentUtterance.lang = 'en-US';
            currentUtterance.onend = () => {
                isSpeaking = false;
            };

            speechSynthesis.speak(currentUtterance);
            isSpeaking = true;
        }
    }

    function Liked(likeId, wordId) {
        const icon = document.getElementById(likeId);
        const isLiked = icon.classList.contains('liked');
        const action = isLiked ? 'remove' : 'add';
        icon.classList.toggle('liked');

        fetch('liked.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `word_id=${wordId}&action=${action}`
        });
    }

    function showInfo(wordInfo) {
        const info = wordInfo;
        document.getElementById('modalWord').textContent = `${info.word}`;
        document.getElementById('modalTranslation').textContent = `${info.translation}`;
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

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: `You won't be able to revert this action!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete.php',
                    type: 'POST',
                    data: {
                        id: wordId
                    },
                    success: function(response) {
                        swalWithBootstrapButtons.fire({
                            title: 'Deleted!',
                            text: `The dictionary entry "${word}" has been deleted.`,
                            icon: 'success'
                        }).then(() => {
                            closeModal();
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting definition:', status, error);
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: 'Failed to delete the dictionary entry. Please try again.',
                            icon: 'error'
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled',
                    text: 'The dictionary entry is safe :)',
                    icon: 'info'
                });
            }
        });
    }
</script>