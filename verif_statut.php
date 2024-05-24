<?php
//on attribue  le temps correspondant aux statuts
$statuts = [
    'decouverte' => 30,
    'classique' => 60,
    'vip' => 150,
    'admin' => 2000000
];
//début session, ouverture fichier, lecture du fichier, récupération du cookie utilisateur
session_start();
$fichier = "compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);
$user_id = $_COOKIE['user_id'];

//fonction qui détermine si le statut est expiré en récupérant le statut de l'utilisateur
function isStatutExpired($statut, $startTime, $statuts) {
    $currentTime = time();
    $duration = $statuts[$statut];
    return ($currentTime - $startTime) > $duration;
}

//fonction qui retourne le profil de l'utilisateur actuel
function getUserProfile($user_id, $data) {
    foreach ($data['profils'] as $profile) {
        if ($profile['id'] == $user_id) {
            return $profile;
        }
    }
    return null;
}

$response = [];
//récupérer le profil
$profile = getUserProfile($user_id, $data);

//si le statut est expiré ou non, rediriger vers le fichier php correspondant (les admin ne sont concernés)
if ($profile) {
    $statut = $profile['statut'];
    $startTime = $profile['statut_starter_time'];
    if ($statut == 'utilisateur') {
        $response['redirect'] = 'accueil.php';
    } elseif (isStatutExpired($statut, $startTime, $statuts) && $statut != 'admin') {
        $response['redirect'] = 'statut_expire.php';
    } else {
        $_SESSION['statut'] = $statut;
        $_SESSION['statut_starter_time'] = $startTime;
        $response['valid'] = true;
        $response['message'] = "Votre statut $statut est encore valide.";
    }
} else {
    $response['error'] = "Profil utilisateur non trouvé.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
