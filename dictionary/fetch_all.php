<link rel="stylesheet" href="../css/fetch_all.css">

<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

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

    $isExpanded = isset($expandedSentences[$row['id']]) ? 'expanded' : '';

    $html = "<ul>";
    foreach ($results as $index => $row) {
        $wordId = "word_" . $index;
        $likeId = "heart_" . $index;
        $text = $lang == 'uz' ? htmlspecialchars($row['translation']) : htmlspecialchars($row['word']);
        $isLiked = in_array($row['id'], $likedWordIds);
        $html .= "<div class='vocabulary'>
            <li id='{$wordId}' class='{$isExpanded}' onclick='toggleExpand(this)'>" . str_ireplace($WordSearch, "<span class='highlight'>{$WordSearch}</span>", $text) . "</li>
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
    ?>

    <div class="information-not-found">
        <i class="fa fa-info-circle"></i>
        <p>No words found.</p>
        <a href="../dictionary/add.php" class="btn btn-primary">Add Words</a>
    </div>

<?php } ?>

<div id="infoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalWord" class="modal-section"></div>
        <div id="modalTranslation" class="modal-section"></div>
        <div id="modalDefinition" class="modal-section"></div>
        <div class="modal-buttons">
            <a class="sentences" onclick="sentences()">
                Sentences
            </a>

            <div class="btn">
                <i class='fas fa-volume-up' onclick="speakTextFromModal()"></i>
                <i class="fa-solid fa-trash" onclick="deleteDefinition()"></i>
            </div>
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
        const text = document.getElementById('modalWord').innerText;

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
        document.getElementById('modalDefinition').textContent = `${info.definition}`;
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
                    success: function (response) {
                        swalWithBootstrapButtons.fire({
                            title: 'Deleted!',
                            text: `The dictionary entry "${word}" has been deleted.`,
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

    function toggleExpand(element) {
        const isExpanded = element.classList.contains('expanded');

        document.querySelectorAll('.vocabulary li').forEach(el => el.classList.remove('expanded'));

        if (!isExpanded) {
            element.classList.add('expanded');
        }
    }
    document.addEventListener('DOMContentLoaded', () => { });

    function sentences() {
        const wordId = document.getElementById('infoModal').dataset.wordId;

        window.location.href = '../sentences/sentences.php?word_id=' + wordId;
    }

</script>