<?php
include "db.php";
include "header.php";

$id = $_GET["id"] ?? 0;

$post = $db->query("SELECT posts.*, users.email AS author_email FROM posts LEFT JOIN users ON users.id = posts.author_id WHERE posts.id=$id")->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "<p>Article introuvable.</p>";
    include "footer.php";
    exit;
}

$comments = $db->query("SELECT * FROM comments WHERE post_id=$id ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<h2><?php echo $post["title"]; ?></h2>
<p><em>Par <?php echo $post["author_email"]; ?>, le <?php echo $post["created_at"]; ?></em></p>
<div>
    <?php echo $post["content"]; ?>
</div>

<h3>Commentaires</h3>
<?php if ($comments): ?>
    <ul>
        <?php foreach ($comments as $c): ?>
            <li>
                <strong><?php echo $c["author"]; ?> :</strong>
                <?php echo $c["content"]; ?> (<?php echo $c["created_at"]; ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun commentaire.</p>
<?php endif; ?>

<h3>Ajouter un commentaire</h3>
<form method="post" action="comment.php">
    <input type="hidden" name="post_id" value="<?php echo $post["id"]; ?>">
    <label>Nom : <input type="text" name="author"></label><br>
    <label>Commentaire :</label><br>
    <textarea name="content" rows="4" cols="50"></textarea><br>
    <button type="submit">Envoyer</button>
</form>

<?php include "footer.php"; ?>
