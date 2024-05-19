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

foreach ($data['profils'] as $profil){
    if ($profil['id'] == $id_utilisateur){
        $profil_visite['nom'] = $profil['nom'];
        $profil_visite['prenom'] = $profil['prenom'];
        $profil_visite['genre'] = $profil['genre'];

        foreach ($data['utilisateurs'] as $user){ //recup email de l'utilisateur visité pour signalement
            if ($user['id'] == $id_utilisateur){
                $emailuser = $user['email'];
            }
        }
        break;
    }
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
    <div class="titrebandeau">Profil résumé</div>
    <input type="button" class="bouton" value="Accueil" onclick="linkopener('abonne.php')" />
</nav>

<div class="Connexion-page">
    <div class="Connexion-boite">
        <img id="profil" src="photo_profil_utilisateurs/<?php echo $id_utilisateur; ?>.jpg" alt="Photo de profil">
        <div class='donnees'>
            Nom : <?php echo $profil_visite['nom']; ?>
        </div>
        <div class='donnees'>
            Prénom : <?php echo $profil_visite['prenom']; ?>
        </div>
        <div class='donnees'>
            Genre : <?php echo $profil_visite['genre']; ?>
        </div>
        <input type="button" value="Signaler" name="reportbutton" onclick="boutonaction(0, 'report', this, '<?php echo $emailuser; ?>')" />
    </div>
</div>
 
<script src="script.js" type="text/javascript"></script>
<script src="scripts/admin.js" type="text/javascript"></script>
</body>

</html>
