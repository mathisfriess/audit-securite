<?php
include "db.php";
include "header.php";

/*
 L’espace personnel ne dépend JAMAIS d’un paramètre GET
 On utilise uniquement l’ID en session
*/
if (!isset($_SESSION["user_id"])) {
    echo "<p>Vous devez être connecté.</p>";
    include "footer.php";
    exit;
}

$userId = (int) $_SESSION["user_id"];
// On force le type pour éviter toute injection

/*
 Requête préparée
 → empêche l’injection SQL
*/
$sql = "SELECT id, email, is_admin FROM users WHERE id = :id";

try {
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log($e->getMessage());
    $user = false;
}
?>

<h2>Mon espace</h2>

<?php if ($user): ?>
    <p>ID : <?php echo (int)$user["id"]; ?></p>
    <p>Email : <?php echo htmlspecialchars($user["email"], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Admin : <?php echo $user["is_admin"] ? "oui" : "non"; ?></p>
<?php else: ?>
    <p>Utilisateur introuvable.</p>
<?php endif; ?>

<?php include "footer.php"; ?>
