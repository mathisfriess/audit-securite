<?php
include "db.php";
include "header.php";

$userId = $_GET["id"] ?? ($_SESSION["user_id"] ?? null);

if (!$userId) {
    echo "<p>Vous devez être connecté.</p>";
    include "footer.php";
    exit;
}

$user = $db->query("SELECT * FROM users WHERE id=$userId")->fetch(PDO::FETCH_ASSOC);
?>
<h2>Mon espace (ou espace d'un autre utilisateur...)</h2>
<?php if ($user): ?>
    <p>ID : <?php echo $user["id"]; ?></p>
    <p>Email : <?php echo $user["email"]; ?></p>
    <p>Mot de passe (stocké en clair !) : <?php echo $user["password"]; ?></p>
    <p>Admin : <?php echo $user["is_admin"] ? "oui" : "non"; ?></p>
<?php else: ?>
    <p>Utilisateur introuvable.</p>
<?php endif; ?>
<?php include "footer.php"; ?>
