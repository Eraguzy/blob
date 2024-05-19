<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if (!isset($_GET['id_utilisateur'])) {
        header("Location: accueil.php");
        exit;
    }
} else {
    header("Location: page_connexion.php");
    exit;
}

$id_utilisateur = $_GET['id_utilisateur'];
$fichier = "compte.json";

$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

foreach ($data['profils'] as $profil) {
    if ($profil['id'] == $id_utilisateur) {
        $profil_visite['nom'] = $profil['nom'];
        $profil_visite['prenom'] = $profil['prenom'];
        $profil_visite['date'] = $profil['date'];
        $profil_visite['genre'] = $profil['genre'];
        $profil_visite['pseudo'] = $profil['pseudo'];
        $profil_visite['situation'] = $profil['situation'];
        $profil_visite['adresse'] = $profil['adresse'];
        $profil_visite['ville'] = $profil['ville'];
        $profil_visite['pays'] = $profil['pays'];
        $profil_visite['couleur_des_yeux'] = $profil['couleur_des_yeux'];
        $profil_visite['couleur_des_cheveux'] = $profil['couleur_des_cheveux'];
        $profil_visite['taille'] = $profil['taille'];
        $profil_visite['poids'] = $profil['poids'];
        break;
    }
}

function calculerAge($dateNaissance)
{
    $dateNaissance = new DateTime($dateNaissance);
    $maintenant = new DateTime();
    $difference = $maintenant->diff($dateNaissance);
    return $difference->y; // La propriété "y" de l'objet DateInterval contient l'âge en années
}

function affichage_info($nom, $information, $id_utilisateur)
{
    $nom = ucfirst($nom);
    $bouton_message = '';
    if ($nom == 'Poids') {
        $bouton_message = "<input type='button' class='bouton' value='Envoyer un message' onclick=\"linkopener('page_discussion.php?id_cible=$id_utilisateur')\" />";
    }
    echo "<div class='donnees'>
            $nom : $information
          </div>
          $bouton_message";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_profil.css">
    <link rel="icon" href="logo.png">
    <title>Blob</title>
</head>

<nav class="bandeau">
    <img src="logo.png" class="img">
    <div class="bandeautitle">BLOB</div>
    <div class="titrebandeau">Profil</div>
    <input type="button" class="bouton" value="Accueil" onclick="linkopener('abonne.php')" />
</nav>

<div class="Connexion-page">
    <div class="Connexion-boite">
        <img id="profil" src="photo_profil_utilisateurs/<?php echo $id_utilisateur; ?>.jpg" alt="Photo de profil">
        <?php
        affichage_info("nom", $profil_visite['pseudo'], $id_utilisateur);
        affichage_info("prenom", $profil_visite['nom'], $id_utilisateur);
        affichage_info("pseudo", $profil_visite['prenom'], $id_utilisateur);
        affichage_info("age", calculerAge($profil_visite['date']), $id_utilisateur);
        affichage_info("genre", $profil_visite['genre'], $id_utilisateur);
        affichage_info("situation", $profil_visite['situation'], $id_utilisateur);
        affichage_info("adresse", $profil_visite['adresse'], $id_utilisateur);
        affichage_info("ville", $profil_visite['ville'], $id_utilisateur);
        affichage_info("pays", $profil_visite['pays'], $id_utilisateur);
        affichage_info("couleur_des_cheveux", $profil_visite['couleur_des_cheveux'], $id_utilisateur);
        affichage_info("couleur_des_yeux", $profil_visite['couleur_des_yeux'], $id_utilisateur);
        affichage_info("taille", $profil_visite['taille'], $id_utilisateur);
        affichage_info("poids", $profil_visite['poids'], $id_utilisateur);
        ?>
    </div>
</div>

<script src="script.js" type="text/javascript"></script>
</body>

</html>
