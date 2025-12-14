<?php
include "db.php";
include "header.php";

$isAdmin = isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;
if (isset($_GET["admin"]) && $_GET["admin"] == 1) {
    $isAdmin = true;
}

if (!$isAdmin) {
    echo "<p>Accès refusé. Réservé à l'administrateur.</p>";
    include "footer.php";
    exit;
}

$users = $db->query("SELECT * FROM users ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Administration</h2>
<p>Liste des utilisateurs :</p>
<table border="1" cellpadding="4" cellspacing="0">
    <tr>
        <th>ID</th><th>Email</th><th>Password (clair)</th><th>Admin</th>
    </tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo $u["id"]; ?></td>
            <td><?php echo $u["email"]; ?></td>
            <td><?php echo $u["password"]; ?></td>
            <td><?php echo $u["is_admin"] ? "oui" : "non"; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include "footer.php"; ?>
