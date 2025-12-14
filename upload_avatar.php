<?php
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["avatar"])) {
    $targetDir = "uploads/avatars/";
    $target = $targetDir . basename($_FILES["avatar"]["name"]);

    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target)) {
        $message = "Avatar uploadé : " . $target;
    } else {
        $message = "Erreur lors de l'upload.";
    }
}
?>
<h2>Upload d'avatar</h2>
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>
<form method="post" enctype="multipart/form-data">
    <label>Fichier avatar : <input type="file" name="avatar"></label>
    <button type="submit">Uploader</button>
</form>
<?php include "footer.php"; ?>
