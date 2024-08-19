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
$sentenceSearch = isset($_GET['query']) ? $_GET['query'] : '';
$wordId = intval($_GET['word_id']);
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