<?php
include "db.php";

/*
 On récupère et on force les types
 → empêche l’injection directe via post_id
*/
$postId = isset($_POST["post_id"]) ? (int) $_POST["post_id"] : 0;

/*
 Champs utilisateur
 trim pour éviter les entrées vides ou abusives
*/
$author  = trim($_POST["author"] ?? "Anonyme");
$content = trim($_POST["content"] ?? "");

/*
 Vérifications minimales
 - post_id valide
 - contenu non vide
*/
if ($postId <= 0 || $content === "") {
    // Redirection silencieuse, pas de message verbeux
    header("Location: index.php");
    exit;
}

/*
 Requête préparée
 → empêche totalement l’injection SQL
*/
$sql = "
    INSERT INTO comments (post_id, author, content, created_at)
    VALUES (:post_id, :author, :content, datetime('now'))
";

try {
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":post_id" => $postId,
        ":author"  => $author,
        ":content" => $content
    ]);

} catch (Exception $e) {
    /*
     On ne montre rien à l’utilisateur
     Mais on log côté serveur pour debug
    */
    error_log($e->getMessage());
}

/*
 Redirection finale
 On caste l’ID pour éviter toute injection dans l’URL
*/
header("Location: view_post.php?id=" . (int)$postId);
exit;
