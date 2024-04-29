<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>
<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau" >Bienvenue</div>
        <input type="button" class="bouton" value="Modifier mon profil" onclick="linkopener('modif.php')"/>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')"/>
    </nav>
    <p class="para">Vous êtes maintenant inscrit sur Blob, vous pouvez rechercher dès à présent des personnes en tapant des mots-clés sur la barre de recherche.</p>
    <form action="/search" method="get" class="recherche">
        <input type="text" name="query" placeholder="Rechercher...">
        <button type="submit">Rechercher</button>
    </form>
    <div class="contenu">
        <p class="para">Afin d'échanger avec tous les utilisateurs de Blob, souscrivez à notre abonnement pour la modique somme de 39,90 euros par an !</p>
    </div>
    <input type="button" class="bouton" id="souscription" value="Souscrire" onclick="linkopener('index.php')"/>

    <script src="script.js" type="text/javascript"></script>
</body>
</html>
