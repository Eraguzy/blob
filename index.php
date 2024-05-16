<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <link rel="icon" type="image/png" href="logo.png">
    <title>Blob</title>
    </head>
    <body>
        <nav class="bandeau">
            <img src="logo.png" class="img">
            <div class="bandeautitle">BLOB</div>
            <input id="deuxboutons" type="button" class="bouton" value="Inscription" onclick="linkopener('page_inscription.php')"/>
            <input type="button" class="bouton" value="Connexion" onclick="linkopener('page_connexion.php')"/>
            <input type="button" class="bouton" value="Free Tour" onclick="linkopener('freetour.php')"/>
        </nav>
            
        <div class="contenu">
            <h1>Blob : Le premier site de rencontre pour les propriétaires de poissons rouges</h1>
        <div class="imagelogo">
            <img src="logo.png">
        </div>
        <input type="button" class="bouton" value="Rejoignez-nous" id="boutongauche" onclick="linkopener('page_inscription.php')"/>
        <input type="button" class="bouton" value="Rejoignez-nous" id="boutondroit" onclick="linkopener('page_inscription.php')"/>
        <h2>Trouvez l'amour avec un compagnon à nageoires ! Rejoignez Blob, le site de rencontre pour les propriétaires de poissons rouges. Plongez dans une expérience unique où l'amour et les écailles se rencontrent.</h2>
        </div>
        <script src="script.js" type="text/javascript"></script>
    </body>
    <footer>
    <p>©2024 Tous droits réservés. Blob Corporate. Site réalisé par Louèva BERANGER, Lucas GOURNAY, Elias CHEKHAB.</p>
    </footer>

</html>

