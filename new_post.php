<?php
include "db.php";
include "header.php";

if (!isset($_SESSION["user_id"])) {
    echo "<p>Vous devez être connecté pour créer un article.</p>";
    include "footer.php";
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"] ?? "";
    $content = $_POST["content"] ?? "";
    $authorId = $_SESSION["user_id"];

    $sql = "INSERT INTO posts (author_id, title, content, created_at) VALUES ($authorId, '$title', '$content', datetime('now'))";
    try {
        $db->exec($sql);
        $message = "Article créé.";
    } catch (Exception $e) {
        $message = "Erreur lors de la création : " . $e->getMessage();
    }
}
?>
<h2>Nouvel article</h2>
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>
<form method="post">
    <label>Titre : <input type="text" name="title"></label><br>
    <label>Contenu :</label><br>
    <textarea name="content" rows="6" cols="60"></textarea><br>
    <button type="submit">Publier</button>
</form>
<?php include "footer.php"; ?>
