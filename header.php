<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TP Sécurité des applications - Mini blog vulnérable</title>
</head>
<body>
    <h1>Mini blog vulnérable</h1>
    <p>Application volontairement vulnérable pour un TP. Ne pas utiliser en production.</p>
    <hr>
    <p>
        <a href="index.php">Accueil</a> |
        <a href="search.php">Recherche</a> |
        <a href="new_post.php">Nouvel article</a> |
        <a href="upload_avatar.php">Upload avatar</a> |
        <?php if (isset($_SESSION["user_email"])): ?>
            Connecté en tant que <?php echo $_SESSION["user_email"]; ?> |
            <a href="dashboard.php">Mon espace</a> |
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a> |
            <a href="register.php">Inscription</a>
        <?php endif; ?>
        |
        <a href="admin.php">Admin</a>
    </p>
    <hr>
