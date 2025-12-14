<?php
include "db.php";
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $user = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["is_admin"] = $user["is_admin"];
        $message = "Connexion réussie pour " . $user["email"];
    } else {
        $message = "Login incorrect";
    }
}
?>
<h2>Login</h2>
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>
<form method="post">
    <label>Email : <input type="text" name="email"></label><br>
    <label>Mot de passe : <input type="password" name="password"></label><br>
    <button type="submit">Se connecter</button>
</form>
<?php include "footer.php"; ?>
