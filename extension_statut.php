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
            $profil['statut_start_time'] = time();
            $statut = $profil['statut'];
            global $statuts;
            $statuts = [
                'decouverte' => 60,
                'classique' => 600,
                'vip' => 3600
            ];
            $profil['statut_start_time'] += $temps_ajoute * $statuts[$statut];
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
        <div class="titrebandeau">Extension du statut</div>
    </nav>
<body>
    <h1>EXTENSION DU STATUT</h1>
    <form method="POST" action="">
        <label for="temps">Durée à ajouter (en secondes) :</label>
        <input type="number" id="temps" name="temps" required><br>
        <button type="submit" class="bouton">Ajouter du temps</button>
    </form>
</body>
</html>