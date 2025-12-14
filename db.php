<?php
try {
    $db = new PDO('sqlite:/var/www/html/data.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion à la base : " . $e->getMessage());
}
?>
