<?php
$statuts = [
    'decouverte' => 30,
    'classique' => 60,
    'vip' => 150,
    'admin' => 2000000
];
session_start();

//vérifie si on est connecté
if (!isset($_COOKIE['user_id'])) {
    header("Location: page_connexion.php");
    exit;
}

//ouverture de la base de donnée json
$user_id = $_COOKIE['user_id'];
$fichier = "compte.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_content = file_get_contents($fichier);
    $data = json_decode($json_content, true);

    //récupération des profils, ajout du temps souhaité à la valeur de début 
    foreach ($data['profils'] as &$profil) {
        if ($profil['id'] == $user_id) {
            $statut = $profil['statut'];
            $duree = $statuts[$statut];
            $profil['statut_starter_time'] = time() + $duree ; 
            break;
        }
    }
    
    file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT));

    header("Location: abonne.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/bloque.css">
    <link rel="icon" href="logo.png">
    <title>Statut expiré</title>
</head>
<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Statut expiré</div>
    </nav>
<body>
    <h1>STATUT EXPIRÉ</h1>
    <p>Souhaitez-vous étendre votre durée d'abonnement ?</p>
    <p>Vous pouvez rajouter à votre temps de session la durée que vous avez choisie lors de l'inscription. Par exemple, si vous avez choisi l'offre découverte, votre temps de session sera augmenté d'une minute.</p>
    <form method="POST" action="">
        <button type="submit" class="bouton">Étendre l'abonnement</button>
        <input type="button" class="bouton" value="Ne pas reconduire" onclick="linkopener('deconnexion.php')" />
    </form>
    
    <script src="script.js" type="text/javascript"></script>
</body>
</html>
