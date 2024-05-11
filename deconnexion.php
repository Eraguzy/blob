<?php
// Définition du nom du cookie à supprimer
$cookie_name = 'user_id';

// Suppression du cookie en définissant une date d'expiration dans le passé
setcookie($cookie_name, '', time() - 3600, '/');

// Rediriger l'utilisateur vers la page d'accueil ou une autre page
header("Location: index.php");
exit;