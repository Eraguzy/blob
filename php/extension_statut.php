<?php
//début session
session_start();
//vérif du cookie utilisateur
if (!isset($_COOKIE['user_id'])) {
    header("Location: ../php/page_connexion.php");
    exit;
}
//récupération de l'id de l'user actuel
$user_id = $_COOKIE['user_id'];
$fichier = "../database/compte.json";
//ouverture du fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_content = file_get_contents($fichier);
    $data = json_decode($json_content, true);
//récupération du statut de l'utilisateur, affiliation des statuts à des temps en seconde
    foreach ($data['profils'] as &$profil) {
        if ($profil['id'] == $user_id) {
            $statut = $profil['statut'];
            global $statuts;
            $statuts = [
                'decouverte' => 30,
                'classique' => 60,
                'vip' => 150
            ];
            //on remplace le temps de début par le temps actuel + le nombre de secondes correspondant
            $profil['statut_start_time'] = time() + $statuts[$statut];
            break;
        }
    }
//réécriture dans le fichier
    file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT));
//redirection vers abonne.php
    header("Location: abonne.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/bloque.css">
    <link rel="icon" href="../images/logo.png">
    <title>Extension du statut</title>
</head>
<body>
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Extension du statut</div>
        <input type="button" class="bouton" value="Retour" onclick="linkopener('abonne.php')" />
        
    </nav>
<body>
    <h1>EXTENSION DU STATUT</h1>
    <p>Vous pouvez rajouter à votre temps de session la durée que vous avez choisie lors de l'inscription. Par exemple, si vous avez choisi l'offre découverte, votre temps de session sera augmenté d'une minute.</p>
    <form method="POST" action="">
        <div class="ajouter">
        <button type="submit" class="bouton">Ajouter du temps</button>
        </div>
        <div class="poisson">
            <img src="../images/poisson_montre.jpeg" class="poisson-img">
        </div>
    </form>
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>
</html>
