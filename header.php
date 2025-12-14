<?php
/* On configure la sécurité des cookies AVANT de lancer la session */
session_set_cookie_params([
    'httponly' => true,   // Empêche l'accès au cookie via JavaScript (anti XSS)
    'secure' => true,     // Cookie envoyé uniquement en HTTPS
    'samesite' => 'Strict' // Empêche l'envoi du cookie depuis un autre site
]);

session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mini blog</title>
</head>
<body>

<h1>Mini blog</h1>

<hr>

<p>
    <a href="index.php">Accueil</a> |
    <a href="search.php">Recherche</a> |
    <a href="new_post.php">Nouvel article</a> |
    <a href="upload_avatar.php">Upload avatar</a> |

    <?php if (isset($_SESSION["user_email"])): ?>
        <!-- On échappe la sortie, on évite une injection. Comme le mail vient de la session 
         on pourrait avoir tendance a laisser ce point sans échappement, mais l'utilisatuer
         peut y mettre ce qu'il veut en manipulant les cookies, donc on doit l'échapper -->
        Connecté en tant que 
        <?php echo htmlspecialchars($_SESSION["user_email"], ENT_QUOTES, 'UTF-8'); ?> |

        <a href="dashboard.php">Mon espace</a> |
        <a href="logout.php">Logout</a>

        <?php if (!empty($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true): ?>
            | <a href="admin.php">Admin</a>
        <?php endif; ?>

    <?php else: ?>
        <a href="login.php">Login</a> |
        <a href="register.php">Inscription</a>
    <?php endif; ?>
</p>

<hr>
