<?php
include "db.php";
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // On échappe l'email, on évite une injection
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"] ?? "";

    if (!$email || !$password) {
        $message = "Identifiants invalides.";
    } else {
        /*
        Pareil on évite une injection SQL avec une requête préparée, et maintenant qu'on a
        intégré le hash des mots de passe, on vérifie que le mot de passe hashé correspond bien
        au hash stocké en base de données
        */
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            // On pense a regénérer l'ID de session pour éviter un vol de session via cet id
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["is_admin"] = $user["is_admin"];

            // On n'affiche pas d'info sensible, nottament si il y a une erreur, pas de log SQL
            $message = "Connexion réussie.";
        } else {
            $message = "Login incorrect.";
        }
    }
}
?>

<h2>Login</h2>

<?php if ($message): ?>
    <!-- 🔒 Protection XSS -->
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<form method="post">
    <label>Email : <input type="email" name="email" required></label><br>
    <label>Mot de passe : <input type="password" name="password" required></label><br>
    <button type="submit">Se connecter</button>
</form>

<?php include "footer.php"; ?>
