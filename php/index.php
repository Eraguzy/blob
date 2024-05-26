<?php
//Si l'utilisateur est connecté il est redirigé vers l'accueil
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/index.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <title>Blob</title>
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour la page de connexion, d'Inscription et le freetour -->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <input id="deuxboutons" type="button" class="bouton" value="Inscription"
            onclick="linkopener('page_inscription.php')" />
        <input type="button" class="bouton" value="Connexion" onclick="linkopener('../php/page_connexion.php')" />
        <input type="button" class="bouton" value="Free Tour" onclick="linkopener('freetour.php')" />
    </nav>

    <div class="contenu">
        <h1>Blob : Le premier site de rencontre pour les propriétaires de poissons rouges</h1>
        <div class="imagelogo">
            <img src="../images/logo.png">
        </div>
        <input type="button" class="bouton" value="Rejoignez-nous" id="boutongauche"
            onclick="linkopener('page_inscription.php')" />
        <input type="button" class="bouton" value="Rejoignez-nous" id="boutondroit"
            onclick="linkopener('page_inscription.php')" />
        <h2>Trouvez l'amour avec un compagnon à nageoires ! Rejoignez Blob, le site de rencontre pour les propriétaires de poissons rouges. Plongez dans une expérience unique où l'amour et les écailles se rencontrent.</h2>
    </div>

    <!-- Le script pour le bouton d'accueil pour rediriger vers la page -->
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

<footer>
    <p>©2024 Tous droits réservés. Blob Corporate. Site réalisé par Louèva BERANGER, Lucas GOURNAY, Elias CHEKHAB.</p>
</footer>

</html>