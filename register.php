<?php
require_once "db.php";
require_once "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validation de l’email
    // Empêche les formats invalides et certains inputs malveillants
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

    // Récupération du mot de passe
    $password = $_POST["password"] ?? "";

    // Vérifie que l’email est valide
    if (!$email) {
        $message = "Email invalide.";

    // Vérifie la présence et la longueur minimale du mot de passe
    } elseif (strlen($password) < 8) {
        $message = "Le mot de passe doit faire au moins 8 caractères.";

    } else {

        /*
         Hash du mot de passe
         → le mot de passe n’est jamais stocké en clair
        */
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        /*
         Requête préparée
         → protège contre les injections SQL
        */
        $sql = "INSERT INTO users (email, password, is_admin)
                VALUES (:email, :password, 0)";
        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([
                ":email" => $email,
                ":password" => $hashedPassword
            ]);

            $message = "Compte créé, vous pouvez vous connecter.";

        } catch (Exception $e) {

            /*
             Ne jamais afficher l’erreur SQL à l’utilisateur
             → fuite d’infos (structure DB, contraintes, etc.)
            */
            error_log($e->getMessage());
            $message = "Erreur lors de la création du compte.";
        }
    }
}
?>

<h2>Inscription</h2>

<?php if ($message): ?>
    <!-- Protection XSS : on échappe l’affichage -->
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<form method="post">
    <label>
        Email :
        <input type="email" name="email" required>
    </label><br>

    <label>
        Mot de passe :
        <input type="password" name="password" required>
    </label><br>

    <button type="submit">Créer un compte</button>
</form>

<?php include "footer.php"; ?>
