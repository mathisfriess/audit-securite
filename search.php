<?php
include "db.php";
include "header.php";

$q = $_GET["q"] ?? "";
$results = [];

if ($q !== "") {
    $sql = "SELECT posts.*, users.email AS author_email FROM posts LEFT JOIN users ON users.id = posts.author_id WHERE title LIKE '%$q%' ORDER BY created_at DESC";
    $results = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>
<h2>Recherche</h2>
<form method="get">
    <label>Terme de recherche : <input type="text" name="q" value="<?php echo $q; ?>"></label>
    <button type="submit">Rechercher</button>
</form>
<hr>
<?php if ($q !== ""): ?>
    <p>Résultats pour : <?php echo $q; ?></p>
    <?php if ($results): ?>
        <ul>
            <?php foreach ($results as $post): ?>
                <li>
                    <a href="view_post.php?id=<?php echo $post["id"]; ?>"><?php echo $post["title"]; ?></a>
                    (par <?php echo $post["author_email"]; ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun résultat.</p>
    <?php endif; ?>
<?php endif; ?>
<?php include "footer.php"; ?>
