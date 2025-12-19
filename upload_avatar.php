<?php
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["avatar"])) {

    // Dossier d’upload
    $targetDir = "uploads/avatars/";

    // Taille max du fichier (ex : 2 Mo)
    $maxSize = 2 * 1024 * 1024;

    // Types MIME autorisés (images uniquement)
    $allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/gif"
    ];

    // Vérifie qu’un fichier a bien été uploadé
    if ($_FILES["avatar"]["error"] !== UPLOAD_ERR_OK) {
        $message = "Erreur lors de l’upload.";

    // Vérifie la taille du fichier
    } elseif ($_FILES["avatar"]["size"] > $maxSize) {
        $message = "Fichier trop volumineux.";

    // Vérifie le vrai type du fichier (pas l’extension)
    } elseif (!in_array(mime_content_type($_FILES["avatar"]["tmp_name"]), $allowedTypes)) {
        $message = "Type de fichier non autorisé.";

    } else {

        /*
         On génère un nom unique
         → empêche l’écrasement de fichiers existants
         → empêche l’upload avec un nom piégé
        */
        $extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . "." . $extension;
        $target = $targetDir . $filename;

        /*
         move_uploaded_file garantit que le fichier vient bien d’un upload HTTP
        */
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target)) {

            /*
             Message neutre
             → on ne révèle jamais le chemin du fichier
            */
            $message = "Avatar uploadé avec succès.";

        } else {
            $message = "Erreur lors de l’enregistrement du fichier.";
        }
    }
}
?>

<h2>Upload d'avatar</h2>

<?php if ($message): ?>
    <!-- Protection XSS -->
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>
        Fichier avatar :
        <input type="file" name="avatar" accept="image/*" required>
    </label>
    <button type="submit">Uploader</button>
</form>

<?php include "footer.php"; ?>
