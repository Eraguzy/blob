<?php
$jsonFile = "compte.json";

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

$bloques = [];

foreach ($data['profils'] as $profil) {
    if ($profil['id'] === $userID) {
        $bloques = $profil['utilisateurs_bloques'];
        break;
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
                <?php foreach ($bloques as $bloqueID) : ?>
                    <li><?php echo $bloqueID; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script src="script.js" type="text/javascript"></script>
</body>
</html>
