<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['action']) && $_POST['action'] === 'unban'){ //vérif de l'action voulue
            $json_content = file_get_contents('json/bannissements.json');
            $data = json_decode($json_content, true);

            foreach($data['bannissements'] as $key => $bans){ //parcours du fichier json
                if($bans['case'] == $_POST['case']){ // comparaison du numéro de cas
                    array_splice($data['bannissements'], $key, 1);
                    break;
                }
            }
            file_put_contents('json/bannissements.json', json_encode($data, JSON_PRETTY_PRINT));
        }


        if (isset($_POST['action']) && $_POST['action'] === 'ban'){
            $json_content = file_get_contents('json/bannissements.json');
            $data = json_decode($json_content, true);


        }
        if (isset($_POST['action']) && $_POST['action'] === 'supp'){
            $json_content = file_get_contents('json/signalements.json');
            $data = json_decode($json_content, true);
            
        }
    }
?>

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