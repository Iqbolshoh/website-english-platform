<?php
include '../config.php';

session_start();

$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$userId = $_SESSION['user_id'];

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'eng';
$liked = isset($_GET['liked']) ? $_GET['liked'] : false;
$sentenceSearch = isset($_GET['query']) ? $_GET['query'] : '';
$wordId = isset($_GET['word_id']) ? $_GET['word_id'] : 4;
$results = [];

$queryParam = $query->validate($sentenceSearch);

if ($lang == 'uz') {
    $searchCondition = !empty($sentenceSearch) ? "translation LIKE ?" : '';
    $orderBy = 'translation ASC';
} else {
    $searchCondition = !empty($sentenceSearch) ? "sentence LIKE ?" : '';
    $orderBy = 'sentence ASC';
}

if ($searchCondition) {
    $results = $query->search(
        'sentences',
        '*',
        "WHERE user_id = ? AND word_id = ? AND $searchCondition ORDER BY $orderBy",
        [$userId, $wordId, "%$queryParam%"],
        "iis"
    );
} else {
    $results = $query->select(
        'sentences',
        '*',
        "WHERE user_id = ? AND word_id = ? ORDER BY $orderBy",
        [$userId, $wordId],
        'ii'
    );
}

$likedSentences = $query->search('liked_sentences', 'sentence_id', 'WHERE user_id = ?', [$userId], 'i');
$likedSentenceIds = array_column($likedSentences, 'sentence_id');

if ($liked) {
    $results = [];
    foreach ($likedSentences as $row) {
        $sentenceId = $row['sentence_id'];
        $queryResult = $query->select('sentences', '*', "WHERE id = ? AND user_id = ? AND word_id = ?", [$sentenceId, $userId, $wordId], 'iii');
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

        return strcmp($a['sentence'], $b['sentence']);
    });

    $html = "<ul>";
    foreach ($results as $index => $row) {
        $sentenceId = "sentence_" . $index;
        $likeId = "heart_" . $index;
        $text = $lang == 'uz' ? htmlspecialchars($row['translation']) : htmlspecialchars($row['sentence']);
        $isLiked = in_array($row['id'], $likedSentenceIds);

        $isExpanded = isset($expandedSentences[$row['id']]) ? 'expanded' : '';

        if ($sentenceSearch) {
            $text = preg_replace("/(" . preg_quote($sentenceSearch, '/') . ")/i", "<span class='highlight'>$1</span>", $text);
        }

        $html .= "<div class='vocabulary'>
            <li id='{$sentenceId}' class='{$isExpanded}' onclick='toggleExpand(this)'>{$text}</li>
            <div class='buttons'>
                <i class='fas fa-volume-up' onclick=\"speakText('{$sentenceId}')\"></i>
                <i class='fas fa-heart " . ($isLiked ? 'liked' : '') . "' id='{$likeId}' onclick=\"Liked('{$likeId}', '{$row['id']}')\"></i>
                <i class='fas fa-info-circle' onclick='showInfo(" . json_encode(["sentence" => $row["sentence"], "translation" => $row["translation"], "id" => $row["id"]], JSON_HEX_APOS | JSON_HEX_QUOT) . ")'></i>
            </div>
        </div>";
    }
    $html .= "</ul>";

    echo $html;
} else {
    echo "<div class='information-not-found'>
    <i class='fas fa-exclamation-circle'></i>
    <p>Information not found</p>
  </div>";
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
        gap: 10px;
    }

    .vocabulary li {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }

    .vocabulary li.expanded {
        white-space: normal;
        overflow: visible;
        text-overflow: initial;
    }

    .vocabulary i {
        font-size: 21px;
    }

    .vocabulary .buttons {
        display: flex;
        gap: 5px;
    }

    .vocabulary {
        line-height: 1.7;
    }

    .vocabulary span.highlight {
        color: red;
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

    .fa-heart:hover {
        transform: scale(1.2);
    }

    .fa-info-circle:active,
    .fa-volume-up:active,
    .fa-trash:active {
        color: #ff4000;
        transform: scale(1.1);
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
        font-size: 18px;
        line-height: 1.5;
        color: #2c3e50;
        font-weight: bold;
    }

    #modalTranslation {
        margin-bottom: 10px;
        font-size: 16px;
        line-height: 1.5;
        color: #16a085;
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

    .modal-content hr {
        border: 0;
        height: 1px;
        background: #ddd;
        margin: 10px 0;
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

    .information-not-found {
        width: 100%;
        height: calc(100vh - 300px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        margin: 0 auto;
        background-color: #f8d7da;
        color: #721c24;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
    }

    .information-not-found i {
        font-size: 30px;
        color: #ff6347;
        margin-bottom: 20px;
    }

    .information-not-found p {
        font-size: 18px;
        margin: 0;
        color: #721c24;
    }
</style>

<div id="infoModal" class="modal" data-sentences-id="">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalSentence" class="modal-section"></div>
        <hr>
        <div id="modalTranslation" class="modal-section"></div>
        <div class="modal-buttons">
            <i class="fas fa-volume-up" onclick="speakTextFromModal()"></i>
            <i class="fa-solid fa-trash" onclick="deleteSentences()"></i>
        </div>
    </div>
</div>



<script>
    let isSpeaking = false;
    let currentUtterance = null;

    function fetchVoiceParameters(callback) {
        fetch('../settings/voice_json.php')
            .then(response => response.json())
            .then(data => {
                callback(data);
            })
            .catch(error => console.error('Xatolik:', error));
    }

    function setUtteranceParameters(utterance, params) {
        utterance.volume = params.volume;
        utterance.rate = params.rate;
        utterance.pitch = params.pitch;
    }

    function speakText(id) {
        const text = document.getElementById(id).innerText;

        if (isSpeaking) {
            speechSynthesis.cancel();
            isSpeaking = false;
        }

        if (!isSpeaking) {
            fetchVoiceParameters(params => {
                currentUtterance = new SpeechSynthesisUtterance(text);
                currentUtterance.lang = 'en-US';
                setUtteranceParameters(currentUtterance, params);
                currentUtterance.onend = () => {
                    isSpeaking = false;
                };

                speechSynthesis.speak(currentUtterance);
                isSpeaking = true;
            });
        }
    }

    function speakTextFromModal() {
        const text = document.getElementById('modalSentence').innerText;

        if (isSpeaking) {
            speechSynthesis.cancel();
            isSpeaking = false;
        }

        if (!isSpeaking) {
            fetchVoiceParameters(params => {
                currentUtterance = new SpeechSynthesisUtterance(text);
                currentUtterance.lang = 'en-US';
                setUtteranceParameters(currentUtterance, params);
                currentUtterance.onend = () => {
                    isSpeaking = false;
                };

                speechSynthesis.speak(currentUtterance);
                isSpeaking = true;
            });
        }
    }

    function closeModal() {
        document.getElementById('infoModal').classList.remove('fade-in');
    }

    function Liked(likeId, sentence_id) {
        const element = document.getElementById(likeId);
        const isLiked = element.classList.contains("liked");
        const action = isLiked ? 'remove' : 'add';

        fetch('liked.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `sentence_id=${sentence_id}&action=${action}`
        })
            .then(response => response.text())
            .then(result => {
                if (result === 'Success') {
                    if (action === 'add') {
                        element.classList.add('liked');
                        element.style.color = 'red';
                    } else {
                        element.classList.remove('liked');
                        element.style.color = '#ddd';
                    }
                } else {
                    console.error('Failed to update like status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function deleteSentences() {
        const modal = document.getElementById('infoModal');
        const sentenceId = modal.getAttribute('data-sentences-id');

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
                        id: sentenceId
                    },
                    success: function (response) {
                        swalWithBootstrapButtons.fire({
                            title: 'Deleted!',
                            text: `The dictionary entry has been deleted.`,
                            icon: 'success'
                        }).then(() => {
                            closeModal();
                            location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
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

    function showInfo(data) {
        document.getElementById("modalSentence").innerText = data.sentence;
        document.getElementById("modalTranslation").innerText = data.translation;
        const modal = document.getElementById("infoModal");
        modal.setAttribute('data-sentences-id', data.id);
        modal.classList.add("fade-in");
        modal.style.display = "block";
    }

    function toggleExpand(element) {
        const isExpanded = element.classList.contains('expanded');

        document.querySelectorAll('.vocabulary li').forEach(el => el.classList.remove('expanded'));

        if (!isExpanded) {
            element.classList.add('expanded');
        }
    }

    document.addEventListener('DOMContentLoaded', () => { });
</script>