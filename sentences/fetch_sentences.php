<?php include '../check.php';

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
        $list_id = "list_" . $index;
        $sentenceId = "sentence_" . $index;
        $likeId = "heart_" . $index;
        $text = $lang == 'uz' ? htmlspecialchars($row['translation']) : htmlspecialchars($row['sentence']);
        $isLiked = in_array($row['id'], $likedSentenceIds);

        $isExpanded = isset($expandedSentences[$row['id']]) ? 'expanded' : '';

        if ($sentenceSearch) {
            $text = preg_replace("/(" . preg_quote($sentenceSearch, '/') . ")/i", "<span class='highlight'>$1</span>", $text);
        }

        $html .= "<div class='list' id='{$list_id}'>
        <li id='{$sentenceId}' class='{$isExpanded}' onclick='toggleExpand(this)'>{$text}</li>
        <div class='buttons'>
            <i class='fas fa-volume-up' onclick=\"speakText('{$sentenceId}')\"></i>
            <i class='fas fa-heart " . ($isLiked ? 'liked' : '') . "' id='{$likeId}' onclick=\"Liked('{$likeId}', '{$row['id']}')\"></i>
            <i class='fas fa-info-circle' onclick='showInfo(" . json_encode([
            "sentence" => $row["sentence"],
            "translation" => $row["translation"],
            "list_id" => $list_id,
            "id" => $row["id"]
        ], JSON_HEX_APOS | JSON_HEX_QUOT) . ")'></i>
        </div>
    </div>";
    }
    $html .= "</ul>";

    echo $html;
} else {
    echo "<div class='information-not-found'>
    <i class='fas fa-exclamation-circle'></i>
    <p>No sentences written for this word.</p>
    <a href='./add.php?word_id=$wordId' class='btn btn-primary'>Add Sentences</a>
  </div>";
}
?>

<link rel="stylesheet" href="../css/fetch_all.css">

<div id="infoModal" class="modal" onclick="closeModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalTitle" class="modal-section"></div>
        <div id="modalWord" class="modal-section"></div>
        <hr>
        <div id="modalTranslation" class="modal-section"></div>
        <div class="modal-buttons">
            <i class='fas fa-volume-up' onclick="speakText('modal')"></i>
            <div class="btn">
                <i class='fas fa-edit' onclick="editSentence()"></i>
                <i class="fa-solid fa-trash" onclick="deleteSentences()"></i>
            </div>
        </div>
    </div>
</div>

<script src="../js/fetch_all-sentences.js"></script>