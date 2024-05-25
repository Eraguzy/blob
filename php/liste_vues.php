<?php
$jsonFile = "../database/compte.json";
//fonction qui lit le fichier json
function loadJson($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }
    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

$data = loadJson($jsonFile);

if ($data === false) {
    die("Erreur : Impossible de charger le fichier JSON.");
}

$userID = $_COOKIE['user_id'];
$vues = [];
$vuesPseudos = [];
//remplissage du tableau "vues" avec la liste des stallkers
foreach ($data['profils'] as $profil) {
    if ($profil['id'] === $userID) {
        $vues = $profil['stalkers'];
        break;
    }
}
//récupération des pseudos des stalkers à partir de leur ID
foreach ($vues as $vuesID) {
    foreach ($data['profils'] as $profil) {
        if ($profil['id'] === $vuesID) {
            $vuesPseudos[] = $profil['pseudo'];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/bloque.css">
    <link rel="icon" href="../images/logo.png">
    <title>Liste des utilisateurs ayant vu mon profil</title>
    <script>
        //fonction qui vérifie le statut toutes les 5 secondes
        function checkStatut() {
            fetch('verif_statut.php')
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.error) {
                    console.error('Erreur:', data.error);
                } else if (data.valid) {
                    console.log(data.message);
                }
            })
            .catch(error => console.error('Erreur:', error));
        }
        setInterval(checkStatut, 5000);
    </script>
</head>
<body>
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Liste des utilisateurs ayant vu mon profil</div>
        <input type="button" class="bouton" value="Retour" onclick="linkopener('abonne.php')" />
    </nav>
    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2>Mes stalkers :</h2>
            <ul>
                <!-- affichage des pseudos des stalkers -->
                <?php foreach ($vuesPseudos as $pseudos) : ?>
                    <li><?php echo $pseudos; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>
</html>
