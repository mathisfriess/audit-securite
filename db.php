<?php
/*
 Chargement de la configuration
 → le chemin de la DB ne doit pas être dans le code
*/
require_once __DIR__ . "/config.php";

try {
    $db = new PDO("sqlite:" . DB_PATH);

    /*
     Mode exception pour gérer les erreurs proprement
     Les erreurs ne sont JAMAIS affichées à l’utilisateur
    */
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {

    /*
     On log l’erreur côté serveur
     → utile pour debug
     → aucune info technique exposée
    */
    error_log($e->getMessage());

    /*
     Message générique uniquement
     */
    die("Erreur interne.");
}
