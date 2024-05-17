<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../styles/admin.css">
        <title>Administrateur</title>
        <link rel="icon" href="../logo.png">
    </head>
    <body>
        <nav class="bandeau">
            <img src="../logo.png" class="img" onclick="linkopener('../accueil.php')">
            <div class="bandeautitle">BLOB</div>
            <div class="titrebandeau">Tableau de bord administrateur</div>
            <input type="button" class="bouton" value="Acceuil" onclick="linkopener('../accueil.php')"/>
        </nav>

        <div class="titre">
        <input type="button" class="bouton boutonadmin" value="Signalements" onclick="setopacitybutton(this); signalements(this);"/>
        <input type="button" class="bouton boutonadmin" value="Bannissements" onclick="setopacitybutton(this); bannissements(this);"/>
        <input type="button" class="bouton boutonadmin" value="Modifier/Supprimer un profil" onclick="setopacitybutton(this); modifprofils(this)"/>
        </div>
        <div id="temporarycontent">
        </div>

        <script src="../script.js" type="text/javascript"></script>
        <script src="../scripts/admin.js" type="text/javascript"></script>
    </body>
</html>