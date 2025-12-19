<?php
session_start(); 
// On démarre la session pour pouvoir la manipuler

$_SESSION = [];
// On vide toutes les données de session
// Évite qu'une donnée reste accessible par erreur

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    // On récupère les paramètres du cookie de session

    setcookie(
        session_name(),   // Nom du cookie (ex: PHPSESSID)
        '',               // Valeur vide
        time() - 42000,   // Date expirée → le navigateur supprime le cookie
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
    // On supprime explicitement le cookie côté navigateur
    // session_destroy() ne le fait pas tout seul
}

session_destroy();
// Supprime la session côté serveur
// Les données associées à l'ID de session sont détruites

session_regenerate_id(true);
// Génère un nouvel ID de session
// Empêche la réutilisation d'un ancien ID volé

header("Location: index.php");
// Redirection après logout

exit;
