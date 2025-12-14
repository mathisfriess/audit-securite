<?php
include "db.php";
include "header.php";

/* On utilise une requête préparée pour éviter les injections SQL */
$stmt = $db->prepare(
    "SELECT posts.*, users.email AS author_email
     FROM posts
     LEFT JOIN users ON users.id = posts.author_id
     ORDER BY created_at DESC"
);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Liste des articles</h2>

<?php if ($posts): ?>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <!-- On échappe bien toutes les données affichées qui viennent de la base de 
                 données pour éviter les attaques XSS -->
                <a href="view_post.php?id=<?php echo (int)$post['id']; ?>">
                    <?php echo htmlspecialchars($post["title"], ENT_QUOTES, "UTF-8"); ?>
                </a>
                (par <?php echo htmlspecialchars($post["author_email"], ENT_QUOTES, "UTF-8"); ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun article pour le moment.</p>
<?php endif; ?>

<?php include "footer.php"; ?>
