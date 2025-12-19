<?php
include "db.php";
include "header.php";

/*
 Vérification basique de connexion :
 on vérifie que l'ID existe ET qu'il est valide
 (pas juste défini)
*/
if (
    !isset($_SESSION["user_id"]) ||
    !is_numeric($_SESSION["user_id"])
) {
    echo "<p>Vous devez être connecté pour créer un article.</p>";
    include "footer.php";
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données utilisateur
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $authorId = (int) $_SESSION["user_id"];
    // On force le type int pour éviter toute injection via l'ID

    /*
     Vérification minimale des champs
     (pas critique sécurité, mais évite des données incohérentes)
    */
    if ($title === "" || $content === "") {
        $message = "Titre et contenu obligatoires.";
    } else {

        /*
         Utilisation de requêtes préparées
         → empêche totalement l'injection SQL
        */
        $sql = "INSERT INTO posts (author_id, title, content, created_at)
                VALUES (:author_id, :title, :content, datetime('now'))";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ":author_id" => $authorId,
                ":title"     => $title,
                ":content"   => $content
            ]);

            $message = "Article créé.";

        } catch (Exception $e) {

            /*
             On ne montre jamais l'erreur SQL à l'utilisateur
             → fuite d'infos (structure DB, chemins, requêtes)
            */
            error_log($e->getMessage());
            $message = "Erreur lors de la création de l'article.";
        }
    }
}
?>
<h2>Nouvel article</h2>

<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="post">
    <label>
        Titre :
        <input type="text" name="title">
    </label><br>

    <label>Contenu :</label><br>
    <textarea name="content" rows="6" cols="60"></textarea><br>

    <button type="submit">Publier</button>
</form>

<?php include "footer.php"; ?>
