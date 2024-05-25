<?php
session_start();

// on vérifie si l'utilisateur est connecté
if (!isset($_COOKIE['user_id'])) {
    http_response_code(403);
    echo "Non autorisé";
    exit;
}

// on récupère l'ID de l'utilisateur et l'ID de la cible grâce à la méthode post
$stalker_id = $_COOKIE['user_id'];
$target_id = $_POST['target_id'];

$fichier = "compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

$profile_found = false;

// Trouver l'utilisateur cible et on écrit l'ID de l'utilisateur actuel dans sa liste de stalkers
foreach ($data['profils'] as &$profile) {
    if ($profile['id'] == $target_id) {
        $profile_found = true;
        if (!isset($profile['stalkers'])) {
            $profile['stalkers'] = [];
        }
        //évite les doublons d'ID
        if (!in_array($stalker_id, $profile['stalkers'])) {
            $profile['stalkers'][] = $stalker_id;
        }
        break;
    }
}
//on remet tout dans compte.json
if ($profile_found) {
    file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT));
    echo "ID enregistré avec succès";
} else {
    http_response_code(404);
    echo "Profil utilisateur non trouvé";
}
?>
