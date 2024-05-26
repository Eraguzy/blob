<?php
//On démarre la session
session_start();

//On vérifie que l'utilisateur est connecté grâce à la présence du cookie contenant l'id de l'utilisateur
if (isset($_COOKIE['user_id'])) {
    //On récupère l'id de l'utilisateur
    $id_utilisateur = $_COOKIE['user_id'];
    //On vérifie si on a la variable de session 'nom' qui prouve qu'on a bien toutes les variables de session, si ce n'est pas le cas il est redirigé vers la page de modofication de profil
    if (!isset($_SESSION['nom'])) {
        header('Location: modif_profil.php');
        exit;
    }
} else {
    //L'utilisateur n'est pas connecté et est redirigé vers la page de connexion
    header("Location: ../php/page_connexion.php");
    exit;
}

//Si l'utilisateur est un administrateur, on regarde si en paramètre il y a un id utilisateur si c'est le cas on remplace l'id de l'admin par cet id
if (isset($_SESSION['statut']) && $_SESSION['statut'] == 'admin'){
    if (isset($_GET['id_utilisateur'])) {
        $id_utilisateur = $_GET['id_utilisateur'];
    }
} else {
    //On vérifie si on a la variable de session 'nom' qui prouve qu'on a bien toutes les variables de session, si ce n'est pas le cas il est redirigé vers la page de modofication de profil
    if (!isset($_SESSION['nom'])) {
        header('Location: modif_profil.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/changement_image.css">
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Image de profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <!-- On affiche l'image de profil actuelle de l'utilisateur -->
            <img id="profil" src="../photo_profil_utilisateurs/<?php echo $id_utilisateur; ?>.jpg?<?php echo time(); ?>" alt="Photo de profil">
            <!-- On propose à l'utilisateur d'envoyer une nouvelle image -->
            <form method="post" action="#" enctype="multipart/form-data">
                <div class="donnees">
                    <label for="photo">Photo :</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg, image/png" required>
                    <input type="submit" value="Modifier la photo de profil" />
                    <?php
                    //Après soumission de l'image
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        //On crée dynamique le chemin de l'image
                        $extension = ".jpg";
                        $chemin_destination = "../photo_profil_utilisateurs/" . $id_utilisateur . $extension;
                        //On vérifie que le téléchargement de l'image s'est bien passé et si le fichier avec les images existe sinon on le crée
                        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
                            $fichier_temporaire = $_FILES["photo"]["tmp_name"];
                            if (!file_exists("../photo_profil_utilisateurs/")) {
                                mkdir("../photo_profil_utilisateurs/", 0777, true);
                            }

                            //Si l'utilisateur est un admin on affiche un message de succès si l'image s'est bien télécharger
                            if ($_SESSION['statut'] == 'admin' && move_uploaded_file($fichier_temporaire, $chemin_destination)) {
                                echo '</br><div class="message-erreur">Fichier téléchargé avec succès.</div>';
                                //Et on redirige vers la page de modification de profil pour admin
                                header("Location: ../admin/modif_profil_admin.php?id_utilisateur=" . urlencode($id_utilisateur));
                                exit;
                            }
                            else if (move_uploaded_file($fichier_temporaire, $chemin_destination)) {
                                //Si c'est un utilisateur lambda alors il a aussi un message de succès si le téléchargement s'est bien passé
                                echo '</br><div class="message-erreur">Fichier téléchargé avec succès.</div>';
                                //Et il est rediriger vers la page pour modifier son propre profil
                                header('Location: modif_profil.php?' . time());
                                exit;
                            } else {
                                //En cas d'erreur de téléchargement on a un message d'erreur
                                echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                            }
                        } else {
                            //Si le fichier ne correspondait pas ou qu'il y avait un problème avant le téléchargement serveur de l'image, on affiche un message d'erreur également
                            echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                        }
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Le script pour le bouton d'accueil pour rediriger vers la page -->
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>
