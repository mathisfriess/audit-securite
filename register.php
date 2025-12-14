<?php
require_once "db.php";
require_once "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* 
    On vérifie que l'email entré est bien conforme a ce que l'on attend, et non pas du 
    code malveillant
    */
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"] ?? "";

    if (!$email) {
        $message = "Email invalide.";
        /* 
        On vérifie que le mot de passe existe et qu'il est suffisamment long pour ajouter un 
        minimum de sécurité, on pourrait dans des versions futures, rajouter une fonctions 
        qui empeche l'utilisateur d'avoir un mot de passe contenant son nom prénom par exemple, 
        ou sa date de naissance 
        */
    } elseif (strlen($password) < 8) {
        $message = "Le mot de passe doit faire au moins 8 caractères.";
    } else {

        /* On hash le mot de passe, pour qu'il n'apparraisse jamais en clair
        Comme ca si un pirate arrive a obtenir la base de données de notre site, il
        ne connaitra pas directement les mots de passes, il n'aura que les hash
         */
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        /* On prépare la requête SQL avec des paramètres nommés pour éviter les injections SQL */
        $sql = "INSERT INTO users (email, password, is_admin) VALUES (:email, :password, 0)";
        $stmt = $db->prepare($sql);

        /* execute permet d'échapper automatiquement avant d'être insérées, pas d'injection */
        try {
            $stmt->execute([
                ":email" => $email,
                ":password" => $hashedPassword
            ]);

            $message = "Compte créé, vous pouvez vous connecter.";

        } catch (Exception $e) {
            /* ❌ On ne renvoie PAS l'erreur SQL à l'utilisateur */
            // L'erreur devrait être loggée côté serveur
            $message = "Erreur lors de la création du compte.";
        }
    }
}
?>

<h2>Inscription</h2>

<?php if ($message): ?>
    <!-- 🔒 Protection XSS : on échappe la sortie -->
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<form method="post">
    <label>Email :
        <input type="email" name="email" required>
    </label><br>

    <label>Mot de passe :
        <input type="password" name="password" required>
    </label><br>

    <button type="submit">Créer un compte</button>
</form>

<?php include "footer.php"; ?>
