<?php
//Démarrage de la session
session_start();

if (isset($_SESSION['statut']) && $_SESSION['statut'] == 'admin') {
    //redirige l'admin vers la page dédiée aux admins pour l'affichage des profils
    $id_utilisateur = $_GET['id_utilisateur'];
    header("Location: ../admin/modif_profil_admin.php?id_utilisateur=" . urlencode($id_utilisateur)); //header peut pas directement inclure du code php
    exit;
}

//Si l'utilisateur n'est pas connecté il est redirigé vers la page de connexion
if (isset($_COOKIE['user_id'])) {
    //Si l'url ne contient pas l'id d'un utilisateur, l'utilisateur est redirigé vers l'accueil
    if (!isset($_GET['id_utilisateur'])) {
        header("Location: accueil.php");
        exit;
    }
} else {
    header("Location: page_connexion.php");
    exit;
}

//On récupère le paramètre de l'url et le cookie pour avoir l'id de l'utilisateur
$id_utilisateur_visite = $_GET['id_utilisateur'];
$id_utilisateur_courant = $_COOKIE['user_id'];
$fichier = "../database/compte.json";

//On récupère les données de la base de données
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

$profil_visite = [];
$emailuser = "";
$utilisateur_courant_bloque = false;

//On cherche le profil de l'utilisateur visité
foreach ($data['profils'] as $profil) {
    if ($profil['id'] == $id_utilisateur_visite) {
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
        foreach ($data['utilisateurs'] as $user) { //recup email de l'utilisateur visité pour signalement et pour bloquer
            if ($user['id'] == $id_utilisateur_visite) {
                $emailuser = $user['email'];
            }
        }
        break;
    }
}

//
foreach ($data['profils'] as $profil) {
    if ($profil['id'] == $id_utilisateur_courant && isset($profil['utilisateurs_bloques'])) {
        if (in_array($id_utilisateur_visite, $profil['utilisateurs_bloques'])) {
            $utilisateur_courant_bloque = true;
        }
        break;
    }
}

//Calcul d'âge avec la date de naissance
function calculerAge($dateNaissance)
{
    $dateNaissance = new DateTime($dateNaissance);
    $maintenant = new DateTime();
    $difference = $maintenant->diff($dateNaissance);
    return $difference->y; // La propriété "y" de l'objet DateInterval contient l'âge en années
}

//On affiche l'information souhaitée
function affichage_info($nom, $information, $id_utilisateur)
{
    $nom = ucfirst($nom);
    echo "<div class='donnees'>
            $nom : $information
          </div>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/page_profil.css">
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
    <script>
        //fonction qui vérifie le statut toutes les 5 secondes
        function checkStatut() {
            fetch('verif_statut.php')
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.error) {
                        console.error('Erreur:', data.error);
                    } else if (data.valid) {
                        console.log(data.message);
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
        setInterval(checkStatut, 5000);
    </script>
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->    
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('abonne.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <!-- On affiche la photo de profil -->
            <img id="profil" src="../photo_profil_utilisateurs/<?php echo $id_utilisateur_visite; ?>.jpg" alt="Photo de profil">
            <?php
            //On affiche les divers infos du profil
            affichage_info("nom", $profil_visite['pseudo'], $id_utilisateur_visite);
            affichage_info("prenom", $profil_visite['nom'], $id_utilisateur_visite);
            affichage_info("pseudo", $profil_visite['prenom'], $id_utilisateur_visite);
            affichage_info("age", calculerAge($profil_visite['date']), $id_utilisateur_visite);
            affichage_info("genre", $profil_visite['genre'], $id_utilisateur_visite);
            affichage_info("situation", $profil_visite['situation'], $id_utilisateur_visite);
            affichage_info("adresse", $profil_visite['adresse'], $id_utilisateur_visite);
            affichage_info("ville", $profil_visite['ville'], $id_utilisateur_visite);
            affichage_info("pays", $profil_visite['pays'], $id_utilisateur_visite);
            affichage_info("couleur_des_cheveux", $profil_visite['couleur_des_cheveux'], $id_utilisateur_visite);
            affichage_info("couleur_des_yeux", $profil_visite['couleur_des_yeux'], $id_utilisateur_visite);
            affichage_info("taille", $profil_visite['taille'], $id_utilisateur_visite);
            affichage_info("poids", $profil_visite['poids'], $id_utilisateur_visite);
            ?>
            <!-- Les différents boutons -->
            <input type='button' class='bouton' value='Envoyer un message' onclick="linkopener('page_discussion.php?id_cible=<?php echo $id_utilisateur_visite; ?>')" />
            <input type="button" value="Signaler" name="reportbutton" onclick="boutonaction(0, 'report', this, '<?php echo $emailuser; ?>')" />
            <?php if (!$utilisateur_courant_bloque): ?>
                <!-- Bouton pour bloquer l'utilisateur -->
                <form action="blocage.php" method="get">
                    <input type="hidden" name="id_utilisateur" value="<?php echo htmlspecialchars($id_utilisateur_visite); ?>">
                    <input type="submit" value="Bloquer cet utilisateur">
                </form>
            <?php endif; ?>
               <!-- Bouton pour débloquer l'utilisateur --> 
            <?php if ($utilisateur_courant_bloque): ?>
                <form action="debloquer.php" method="get">
                    <input type="hidden" name="id_utilisateur" value="<?php echo htmlspecialchars($id_utilisateur_visite); ?>">
                    <input type="submit" value="Débloquer cet utilisateur">
                </form>
            <?php endif; ?>

        </div>
    </div>

    <!-- Le script pour les boutons de redirection vers une page annexe et la partie admin -->
    <script src="../scripts/script.js" type="text/javascript"></script>
    <script src="../scripts/admin.js" type="text/javascript"></script>
</body>

</html>
