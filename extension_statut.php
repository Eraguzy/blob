<?php
session_start();

if (!isset($_COOKIE['user_id'])) {
    header("Location: page_connexion.php");
    exit;
}

$user_id = $_COOKIE['user_id'];
$fichier = "compte.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temps_ajoute = intval($_POST['temps']);
    $json_content = file_get_contents($fichier);
    $data = json_decode($json_content, true);

    foreach ($data['profils'] as &$profil) {
        if ($profil['id'] == $user_id) {
            $statut = $profil['statut'];
            global $statuts;
            $statuts = [
                'decouverte' => 30,
                'classique' => 60,
                'vip' => 150
            ];
            $profil['statut_start_time'] = time() + $statuts[$statut];
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
    <title>Extension du statut</title>
</head>
<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Extension du statut</div>
        <input type="button" class="bouton" value="Retour" onclick="linkopener('abonne.php')" />
    </nav>
<body>
    <h1>EXTENSION DU STATUT</h1>
    <form method="POST" action="">
        <button type="submit" class="bouton">Ajouter du temps</button>
    </form>
    <script src="script.js" type="text/javascript"></script>
</body>
</html>
