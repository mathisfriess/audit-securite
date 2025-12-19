<?php
include "db.php";
include "header.php";

/*
 Récupération du paramètre de recherche
 On force en string pour éviter des types inattendus
*/
$q = isset($_GET["q"]) ? trim($_GET["q"]) : "";
$results = [];

if ($q !== "") {

    /*
     Requête préparée
     → empêche totalement l’injection SQL
     Le LIKE est construit côté PHP
    */
    $sql = "
        SELECT posts.*, users.email AS author_email
        FROM posts
        LEFT JOIN users ON users.id = posts.author_id
        WHERE title LIKE :search
        ORDER BY created_at DESC
    ";

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ":search" => "%" . $q . "%"
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        // On log l’erreur côté serveur uniquement
        error_log($e->getMessage());
        $results = [];
    }
}
?>

<h2>Recherche</h2>

<form method="get">
    <label>
        Terme de recherche :
        <input
            type="text"
            name="q"
            value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
    </label>
    <button type="submit">Rechercher</button>
</form>

<hr>

<?php if ($q !== ""): ?>
    <!-- Protection XSS : on échappe l’affichage -->
    <p>Résultats pour : <?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if ($results): ?>
        <ul>
            <?php foreach ($results as $post): ?>
                <li>
                    <a href="view_post.php?id=<?php echo (int)$post["id"]; ?>">
                        <?php echo htmlspecialchars($post["title"], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                    (par <?php echo htmlspecialchars($post["author_email"], ENT_QUOTES, 'UTF-8'); ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun résultat.</p>
    <?php endif; ?>
<?php endif; ?>

<?php include "footer.php"; ?>
