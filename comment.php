<?php
include "db.php";

$postId = $_POST["post_id"] ?? 0;
$author = $_POST["author"] ?? "Anonyme";
$content = $_POST["content"] ?? "";

$sql = "INSERT INTO comments (post_id, author, content, created_at) VALUES ($postId, '$author', '$content', datetime('now'))";
try {
    $db->exec($sql);
} catch (Exception $e) {
    // on ignore l'erreur ici
}

header("Location: view_post.php?id=" . $postId);
exit;
?>
