<?php
session_start();

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
$profil_visite = [];
$id_utilisateur = $_GET['id_utilisateur'];
$fichier = "compte.json";
$isAdmin = false;
$isabonne = false;

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
//si on est admin, rediriger vers le profil complet
$user_id = $_COOKIE['user_id'];
if(isset($_SESSION['statut']) && ($_SESSION['statut'] == 'admin')) {
    $isAdmin = true;
}
else if(isset($_SESSION['statut']) && ($_SESSION['statut'] == 'vip' || ($_SESSION['statut'] == 'decouverte' || $_SESSION['statut'] == 'classique'))) {
    $isabonne = true;
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
    <input type="button" class="bouton" value="Accueil" onclick="linkopener('accueil.php')" />
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
        <?php if ($isAdmin || $isabonne) : ?>
            <input type="button" class="bouton" value="Profil complet" onclick="linkopener('page_profil.php?id_utilisateur=<?php echo $id_utilisateur; ?>')" />
        <?php else : ?>
            <input type="button" class="bouton" value="Voir le profil complet" onclick="linkopener('souscription.php')" />
        <?php endif; ?>
        </div>
        </div>
    
<script src="script.js" type="text/javascript"></script>
<script src="scripts/admin.js" type="text/javascript"></script>
</body>

</html>
