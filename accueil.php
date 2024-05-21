<?php
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

//si on veut accéder à accueil.php, on ne doit pas être un abonné 
session_start();
if(isset($_SESSION['statut']) && ($_SESSION['statut'] == 'decouverte' || $_SESSION['statut'] == 'vip' || $_SESSION['statut'] == 'classique')) {
    // Redirection vers la page abonne.php si l'utilisateur est abonné
    header("Location: abonne.php");
    exit;
}

// Trier les profils par ID en supposant que les IDs sont ordonnés chronologiquement
usort($data['profils'], function ($a, $b) {
    return strcmp($a['id'], $b['id']);
});

// Extraire les trois derniers profils
$derniers_utilisateurs = array_slice($data['profils'], -3);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/accueil.css">
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
    <p class="para">Vous êtes maintenant inscrit sur Blob, vous pouvez rechercher dès à présent des personnes en tapant des mots-clés sur la barre de recherche.</p>

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
        </ul>
        <p class="para">Afin d'échanger avec tous les utilisateurs de Blob, cliquez sur le bouton en bas afin de découvrir toutes nos offres d'abonnement !</p>
    </div>
    <input type="button" class="bouton" id="souscription" value="Souscrire" onclick="linkopener('souscription.php')" />

    <script src="script.js" type="text/javascript"></script>
    <script src="scripts/recherche.js" type="text/javascript"></script>
</body>

</html>
