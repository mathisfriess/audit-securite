<?php
include "db.php";
include "header.php";

$posts = $db->query("SELECT posts.*, users.email AS author_email FROM posts LEFT JOIN users ON users.id = posts.author_id ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Liste des articles</h2>
<?php if ($posts): ?>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <a href="view_post.php?id=<?php echo $post["id"]; ?>"><?php echo $post["title"]; ?></a>
                (par <?php echo $post["author_email"]; ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun article pour le moment.</p>
<?php endif; ?>
<?php include "footer.php"; ?>
