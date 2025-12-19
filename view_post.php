<?php
include "db.php";
include "header.php";

/*
 Récupération de l’ID
 On force en entier pour éviter l’injection directe
*/
$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

/*
 Requête préparée
 → empêche l’injection SQL
*/
$sqlPost = "
    SELECT posts.*, users.email AS author_email
    FROM posts
    LEFT JOIN users ON users.id = posts.author_id
    WHERE posts.id = :id
";

try {
    $stmt = $db->prepare($sqlPost);
    $stmt->execute([":id" => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Log serveur uniquement
    error_log($e->getMessage());
    $post = false;
}

if (!$post) {
    echo "<p>Article introuvable.</p>";
    include "footer.php";
    exit;
}

/*
 Récupération des commentaires
 Toujours en requête préparée
*/
$sqlComments = "
    SELECT author, content, created_at
    FROM comments
    WHERE post_id = :id
    ORDER BY created_at DESC
";

try {
    $stmt = $db->prepare($sqlComments);
    $stmt->execute([":id" => $id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log($e->getMessage());
    $comments = [];
}
?>

<!-- Protection XSS : échappement systématique -->
<h2><?php echo htmlspecialchars($post["title"], ENT_QUOTES, 'UTF-8'); ?></h2>

<p>
    <em>
        Par <?php echo htmlspecialchars($post["author_email"], ENT_QUOTES, 'UTF-8'); ?>,
        le <?php echo htmlspecialchars($post["created_at"], ENT_QUOTES, 'UTF-8'); ?>
    </em>
</p>

<div>
    <?php echo nl2br(htmlspecialchars($post["content"], ENT_QUOTES, 'UTF-8')); ?>
</div>

<h3>Commentaires</h3>

<?php if ($comments): ?>
    <ul>
        <?php foreach ($comments as $c): ?>
            <li>
                <strong><?php echo htmlspecialchars($c["author"], ENT_QUOTES, 'UTF-8'); ?> :</strong>
                <?php echo htmlspecialchars($c["content"], ENT_QUOTES, 'UTF-8'); ?>
                (<?php echo htmlspecialchars($c["created_at"], ENT_QUOTES, 'UTF-8'); ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun commentaire.</p>
<?php endif; ?>

<h3>Ajouter un commentaire</h3>

<form method="post" action="comment.php">
    <!-- On caste l’ID pour éviter toute manipulation -->
    <input type="hidden" name="post_id" value="<?php echo (int)$post["id"]; ?>">

    <label>
        Nom :
        <input type="text" name="author" required>
    </label><br>

    <label>Commentaire :</label><br>
    <textarea name="content" rows="4" cols="50" required></textarea><br>

    <button type="submit">Envoyer</button>
</form>

<?php include "footer.php"; ?>
