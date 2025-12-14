<?php
include "db.php";
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $sql = "INSERT INTO users (email, password, is_admin) VALUES ('$email', '$password', 0)";
    try {
        $db->exec($sql);
        $message = "Compte créé, vous pouvez vous connecter.";
    } catch (Exception $e) {
        $message = "Erreur lors de la création du compte : " . $e->getMessage();
    }
}
?>
<h2>Inscription</h2>
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>
<form method="post">
    <label>Email : <input type="text" name="email"></label><br>
    <label>Mot de passe : <input type="password" name="password"></label><br>
    <button type="submit">Créer un compte</button>
</form>
<?php include "footer.php"; ?>
