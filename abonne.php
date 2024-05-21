<?php
session_start();
$statuts = [
    'decouverte' => 60,
    'classique' => 600,
    'vip' => 3600,
    'admin' => 2000000
];
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if (!isset($_COOKIE['creation_profil']) || $_COOKIE['creation_profil'] == 0) {
        $profil_cree = 0;
        $id_utilisateur = $_COOKIE['user_id'];
        $fichier = "compte.json";
        $json_content = file_get_contents($fichier);
        $data = json_decode($json_content, true);
        foreach ($data['profils'] as $profile) {
            if ($profile['id'] == $id_utilisateur) {
                $profil_cree = 1;
                setcookie("creation_profil", 1, time() + (30 * 24 * 3600), "/");
                break;
            }
        }
        if ($profil_cree == 0) {
            setcookie("creation_profil", 0, time() + (30 * 24 * 3600), "/");
            header("Location: creation_profil.php");
            exit;
        }
    }
} else {
    // Redirection vers la page de connexion si le cookie n'est pas présent
    header("Location: page_connexion.php");
    exit;
}

// Charger les données du fichier JSON
$fichier = "compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

// Trier les profils par ID en supposant que les IDs sont ordonnés chronologiquement
usort($data['profils'], function ($a, $b) {
    return strcmp($a['id'], $b['id']);
});

// Extraire les trois derniers profils
$derniers_utilisateurs = array_slice($data['profils'], -3);

$user_id = $_COOKIE['user_id'];
if(isset($_SESSION['statut']) && ($_SESSION['statut'] == 'utilisateur')) {
    // Redirection vers la page accueil.php si l'utilisateur n'est pas abonné
    header("Location: accueil.php");
    exit;
}
function getUserProfile($user_id, $data) {
    foreach ($data['profils'] as $profile) {
        if ($profile['id'] == $user_id) {
            return $profile;
        }
    }
    return null;
}

function isStatutValid($statut, $startTime, $statuts) {
    $currentTime = time();
    $duration = $statuts[$statut];
    return ($currentTime - $startTime) < $duration;
}

$profile = getUserProfile($user_id, $data);


if ($profile) {
    $statut = $profile['statut'];
    $startTime = $profile['statut_starter_time'];
    if ($statut == 'utilisateur') {
        header("Location: accueil.php");
        exit;
    }
    if (!isStatutValid($statut, $startTime, $statuts) && $statut != 'admin' && $statut != 'admi') {
        // Statut expiré
        header("Location: statut_expire.php");
        exit;
    } else {
        $_SESSION['statut'] = $statut;
        $_SESSION['statut_starter_time'] = $startTime;
        echo "Votre statut $statut est encore valide.";
    }
} else {
    echo "Profil utilisateur non trouvé.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/abonne.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Bonjour</div>
        <input id="boutonmodif" type="button" class="bouton" value="Modifier mon profil" onclick="linkopener('modif_profil.php')" />
        <input type="button" class="bouton" value="Déconnexion" onclick="linkopener('deconnexion.php')" />
    </nav>
    <p class="para">Cher abonné Blob, vous pouvez rechercher dès à présent des personnes en tapant le pseudo sur la barre de recherche, visualiser le profil complet, envoyer un mesage à quelqu'un, et bloquer une personne !</p>

    <div class="conteneur">
        <form action="page_recherche.php" method="get" class="recherche">
            <input type="text" name="q" id="recherche" placeholder="Rechercher..." onkeyup="Suggestions(this.value)">
            <button type="submit">Rechercher</button>
        </form>
        <div id="res"></div>
    </div>

    <div class="contenu">
        
        <p>Les trois derniers profils inscrits sur Blob :</p><br>
        <ul id="utilisateurs">
            <?php foreach ($derniers_utilisateurs as $utilisateur) : ?>
                <li><?php echo htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']); ?></li>
            <?php endforeach; ?>

            <input type="button" class="bouton" value="Liste des bloqués" onclick="linkopener('liste_bloque.php')" />
            <input type="button" class="bouton" value="Vues de mon profil" onclick="linkopener('liste_vues.php')" />
            <input type="button" class="bouton" value="Extension du statut" onclick="linkopener('extension_statut.php')" />
        </ul>
    </div>
        
    <script src="script.js" type="text/javascript"></script>
    <script src="scripts/recherche.js" type="text/javascript"></script>
</body>

</html>
