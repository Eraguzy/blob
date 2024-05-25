<?php
// Définition du nom du cookie à supprimer
$cookie_name = 'user_id';
$cookie_name2 = 'creation_profil';

// Suppression du cookie en définissant une date d'expiration dans le passé
setcookie($cookie_name, '', time() - 3600, '/');
setcookie($cookie_name2, '', time() - 3600, '/');

session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil ou une autre page
header("Location: index.php");
exit;