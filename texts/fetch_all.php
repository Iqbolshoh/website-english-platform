<link rel="stylesheet" href="../css/fetch_all.css">
<script src="../js/fetch_all-texts.js"></script>

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
$textsearch = isset($_GET['query']) ? $_GET['query'] : '';
$textsearch = strtolower($textsearch);

$searchColumn = ($lang == 'uz') ? 'translation' : 'content';

if (!empty($textsearch)) {
    $queryParam = $query->validate($textsearch);
    $results = $query->search(
        'texts',
        '*',
        "WHERE user_id = ? AND {$searchColumn} LIKE ?",
        [$userId, "%$queryParam%"],
        "is"
    );
} else {
    $results = $query->select(
        'texts',
        '*',
        'WHERE user_id = ? ORDER BY ' . ($lang == 'uz' ? 'translation' : 'content') . ' ASC',
        [$userId],
        'i'
    );
}

$likedtexts = $query->search('liked_texts', 'text_id', 'WHERE user_id = ?', [$userId], 'i');
$likedtextIds = array_column($likedtexts, 'text_id');

if ($liked) {
    $results = [];
    foreach ($likedtexts as $row) {
        $textId = $row['text_id'];
        $queryResult = $query->select('texts', '*', "WHERE id = ? AND user_id = ?", [$textId, $userId], 'ii');
        if ($queryResult) {
            $results = array_merge($results, $queryResult);
        }
    }
}

if ($results) {
    usort($results, function ($a, $b) use ($textsearch) {
        $textsearchLower = strtolower($textsearch);
        $aStartsWith = strtolower(substr($a['content'], 0, strlen($textsearchLower))) === $textsearchLower;
        $bStartsWith = strtolower(substr($b['content'], 0, strlen($textsearchLower))) === $textsearchLower;

        if ($aStartsWith && !$bStartsWith) return -1;
        if (!$aStartsWith && $bStartsWith) return 1;

        return strcmp(strtolower($a['content']), strtolower($b['content']));
    });

    $isExpanded = isset($expandedSentences[$row['id']]) ? 'expanded' : '';

    $html = "<ul>";
    foreach ($results as $index => $row) {
        $textId = "text_" . $index;
        $likeId = "heart_" . $index;
        $text = $lang == 'uz' ? htmlspecialchars($row['translation']) : htmlspecialchars($row['content']);
        $isLiked = in_array($row['id'], $likedtextIds);
        $html .= "<div class='vocabulary'>
            <li id='{$textId}' class='{$isExpanded}' onclick='toggleExpand(this)'>" . str_ireplace($textsearch, "<span class='highlight'>{$textsearch}</span>", $text) . "</li>
            <div class='buttons'>
                <i class='fas fa-volume-up' onclick=\"speakText('{$textId}')\"></i>
                <i class='fas fa-heart " . ($isLiked ? 'liked' : '') . "' id='{$likeId}' onclick=\"Liked('{$likeId}', '{$row['id']}')\"></i>
                <i class='fas fa-info-circle' onclick='showInfo(" . json_encode(["title" => $row["title"], "word" => $row["content"], "translation" => $row["translation"], "definition" => $row["definition"], "id" => $row["id"]], JSON_HEX_APOS | JSON_HEX_QUOT) . ")'></i>
            </div>
        </div>";
    }
    $html .= "</ul>";
    echo $html;
} else {
?>
    <div class="information-not-found">
        <i class="fa fa-info-circle"></i>
        <p>No texts found.</p>
        <a href="./add.php" class="btn btn-primary">Add texts</a>
    </div>
<?php } ?>

<div id="infoModal" class="modal" onclick="closeModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalTitle" class="modal-section"></div>
        <div id="modalWord" class="modal-section"></div>
        <div id="modalTranslation" class="modal-section"></div>
        <div id="id" class="modal-section"></div>
        <div class="modal-buttons">
            <i class='fas fa-volume-up' onclick="speakText('modal')"></i>
            <i class="fa-solid fa-trash" onclick="deleteText()"></i>
        </div>
    </div>
</div>