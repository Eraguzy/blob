<?php
session_start();

if (isset($_COOKIE['user_id'])) {
    $id_utilisateur = $_COOKIE['user_id'];
    if (!isset($_SESSION['nom'])) {
        header('Location: modif_profil.php');
        exit;
    }
} else {
    header("Location: page_connexion.php");
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/changement_image.css">
    <link rel="icon" href="logo.png">
    <title>Blob</title>
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Image de profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <img id="profil" src="photo_profil_utilisateurs/<?php echo $id_utilisateur; ?>.jpg" alt="Photo de profil">
            <form method="post" action="#" enctype="multipart/form-data">
                <div class="donnees">
                    <label for="photo">Photo :</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg, image/png" required>
                    <input type="submit" value="Modifier la photo de profil" />
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $extension = ".jpg";
                        $chemin_destination = "photo_profil_utilisateurs/" . $id_utilisateur . $extension;
                        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
                            $fichier_temporaire = $_FILES["photo"]["tmp_name"];
                            if (!file_exists("photo_profil_utilisateurs/")) {
                                mkdir("photo_profil_utilisateurs/", 0777, true);
                            }
                            if (move_uploaded_file($fichier_temporaire, $chemin_destination)) {
                                echo '</br><div class="message-erreur">Fichier téléchargé avec succès.</div>';
                                header('Location: modif_profil.php');
                            } else {
                                echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                            }
                        } else {
                            echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                        }
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>