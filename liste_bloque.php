<?php
$jsonFile = "compte.json";
//ouverture du fichier json
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
//on récup l'id de l'utilisateur actuel
$userID = $_COOKIE['user_id'];
$bloques = [];
$bloquesPseudos = [];

//on remplit le tableau bloques avec tous les ID des gens bloqués de la liste de la personne
foreach ($data['profils'] as $profil) {
    if ($profil['id'] === $userID) {
        $bloques = $profil['utilisateurs_bloques'];
        break;
    }
}
//on récupère le pseudo de l'utilisateur bloqué afin de ne pas afficher l'ID (inutile)
foreach ($bloques as $bloqueID) {
    foreach ($data['profils'] as $profil) {
        if ($profil['id'] === $bloqueID) {
            $bloquesPseudos[] = $profil['pseudo'];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/bloque.css">
    <link rel="icon" href="logo.png">
    <title>Liste des utilisateurs bloqués</title>
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
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Liste des utilisateurs bloqués</div>
        <input type="button" class="bouton" value="Retour" onclick="linkopener('abonne.php')" />
    </nav>
    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2>Utilisateurs bloqués :</h2>
            <ul>
                <!-- affiche les bloqués par leur pseudo -->
                <?php foreach ($bloquesPseudos as $pseudos) : ?>
                    <li><?php echo $pseudos; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script src="script.js" type="text/javascript"></script>
</body>
</html>
