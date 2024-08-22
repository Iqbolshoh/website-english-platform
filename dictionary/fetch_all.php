<script src="../js/fetch_all-dictionary.js"></script>
<link rel="stylesheet" href="../css/fetch_all.css">

<?php

session_start();

include '../model/wordsModel.php';
$query = new WordsModel();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$user_id = $_SESSION['user_id'];

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'eng';
$liked = isset($_GET['liked']) ? $_GET['liked'] : false;
$WordSearch = isset($_GET['query']) ? $_GET['query'] : '';
$WordSearch = strtolower($WordSearch);

if ($lang == 'uz') {
    if (!empty($WordSearch)) {
        $queryParam = $query->validate($WordSearch);
        $results = $query->select('words', '*', "user_id = $user_id AND translation LIKE '%$queryParam%' ORDER BY translation");
    } else {
        $results = $query->select('words', '*', "user_id = $user_id ORDER BY translation ASC");
    }
} else {
    if (!empty($WordSearch)) {
        $queryParam = $query->validate($WordSearch);
        $results = $query->select('words', '*', "user_id = $user_id AND word LIKE '%$queryParam%' ORDER BY word");
    } else {
        $results = $query->select('words', '*', "user_id = $user_id ORDER BY word ASC");
    }
}

$likedWords = $query->select('liked_words', 'word_id', "user_id = $user_id");
$likedWord_ids = array_column($likedWords, 'word_id');

if ($liked) {
    $results = [];
    foreach ($likedWords as $row) {
        $word_id = $row['word_id'];
        $queryResult = $query->select('words', '*', "id = $word_id AND user_id = $user_id");
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
        $word_id = "word_" . $index;
        $likeId = "heart_" . $index;
        $text = $lang == 'uz' ? htmlspecialchars($row['translation']) : htmlspecialchars($row['word']);
        $isLiked = in_array($row['id'], $likedWord_ids);
        $html .= "<div class='vocabulary'>
            <li id='{$word_id}' class='{$isExpanded}' onclick='toggleExpand(this)'>" . str_ireplace($WordSearch, "<span class='highlight'>{$WordSearch}</span>", $text) . "</li>
            <div class='buttons'>
                <i class='fas fa-volume-up' onclick=\"speakText('{$word_id}')\"></i>
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
                <i class='fas fa-volume-up' onclick="speakText('modal')"></i>
                <i class="fa-solid fa-trash" onclick="deleteDefinition()"></i>
            </div>
        </div>
    </div>
</div>