<?php
//Définition des cookies à supprimer
$cookie_name = 'user_id';
$cookie_name2 = 'creation_profil';

//Suppression des cookies
setcookie($cookie_name, '', time() - 3600, '/');
setcookie($cookie_name2, '', time() - 3600, '/');

//On démarre la session
session_start();

//On supprime toutes les variables de la session
session_unset();

//On détruit la session en elle-même
session_destroy();

//On redirige l'utilisateur vers la page pour les utilisateurs non connecté.
header("Location: index.php");
exit;