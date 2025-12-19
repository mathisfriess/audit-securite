<?php
include "db.php";
include "header.php";

/*
 Vérification stricte :
 - l’utilisateur doit être connecté
 - l’admin ne dépend JAMAIS d’un paramètre GET
*/
if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["is_admin"]) ||
    $_SESSION["is_admin"] !== 1
) {
    echo "<p>Accès refusé.</p>";
    include "footer.php";
    exit;
}

/*
 Requête préparée
 → même si ici il n’y a pas d’input utilisateur,
   on garde une pratique propre
*/
try {
    $stmt = $db->prepare("
        SELECT id, email, is_admin
        FROM users
        ORDER BY id ASC
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    // Log serveur uniquement
    error_log($e->getMessage());
    $users = [];
}
?>

<h2>Administration</h2>
<p>Liste des utilisateurs :</p>

<table border="1" cellpadding="4" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Admin</th>
    </tr>

    <?php foreach ($users as $u): ?>
        <tr>
            <!-- Échappement XSS -->
            <td><?php echo (int)$u["id"]; ?></td>
            <td><?php echo htmlspecialchars($u["email"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo $u["is_admin"] ? "oui" : "non"; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include "footer.php"; ?>
