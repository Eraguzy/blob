<?php
ob_start();
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    // Authentification automatique de l'utilisateur
    $user_id = $_COOKIE['user_id'];
    $utilisateur = explode(";", $user_id);
} else {
    // Redirection vers la page de connexion si le cookie n'est pas présent
    header("Location: page_connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_profil.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Création du profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')">
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2 class="legende">Modification profil</h2>
            <form name="creation_profil" method="post" action="#" enctype="multipart/form-data">
                <div class="donnees">
                    <label for="date">Date de naissance :</label>
                    <input type="date" name="date" class="date" placeholder="Date" required>
                </div>
                <div class="donnees">
                    <label for="genre">Sexe :</label>
                    <select name="genre" id="genre" required>
                        <option value="femme">Femme</option>
                        <option value="homme">Homme</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="donnees">
                    <label for="pseudo">Pseudonyme :</label>
                    <input type="text" name="pseudo" class="champ" placeholder="Pseudonyme" required>
                </div>
                <div class="donnees">
                    <label for="photo">Photo :</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg, image/png" required>
                </div>
                <div class="donnees">
                    <label for="situation">Situation familiale :</label>
                    <select name="situation" id="situation" required>
                        <option value="celib">Célibataire</option>
                        <option value="en couple">En couple</option>
                        <option value="marié(e)">Marié(e)</option>
                        <option value="Veuf(ve)">Veuf(ve)</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="donnees">
                    <label for="adresse">Adresse :</label>
                    <input type="text" id="adresse" name="adresse" placeholder="Adresse" required>
                </div>
                <div class="donnees">
                    <label for="ville">Ville :</label>
                    <input type="text" id="ville" name="ville" placeholder="Ville" required>
                </div>
                <div class="donnees">
                    <label for="pays">Pays :</label>
                    <input type="text" id="pays" name="pays" placeholder="Pays" required>
                </div>
                <div class="donnees">
                    <label for="couleur_des_cheveux">Couleur des cheveux :</label>
                    <select name="couleur_des_cheveux" id="couleur_des_cheveux" required>
                        <option value="brun">Brun</option>
                        <option value="blond">Blond</option>
                        <option value="roux">Roux</option>
                        <option value="noir">Noir</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="donnees">
                    <label for="couleur_des_yeux">Couleur des yeux :</label>
                    <select name="couleur_des_yeux" id="couleur_des_yeux" required>
                        <option value="bleu">Bleu</option>
                        <option value="vert">Vert</option>
                        <option value="marron">Marron</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="donnees">
                    <label for="poids">Poids :</label>
                    <input type="number" id="poids" name="poids" placeholder="Poids" required>
                </div>
                <div class="donnees">
                    <label for="taille">Taille :</label>
                    <input type="number" id="taille" name="taille" placeholder="Taille" required>
                </div>
                <input type="submit" value="Création du profil" />
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $date = $_POST["date"];
                $genre = $_POST["genre"];
                $pseudo = $_POST["pseudo"];
                $situation = $_POST["situation"];
                $adresse = $_POST["adresse"];
                $ville = $_POST["ville"];
                $pays = $_POST["pays"];
                $couleur_des_cheveux = $_POST["couleur_des_cheveux"];
                $couleur_des_yeux = $_POST["couleur_des_yeux"];
                $poids = $_POST["poids"];
                $taille = $_POST["taille"];
                $fichier = "profil.txt";

                $extension = ".jpg";
                $chemin_destination = "photo_profil_utilisateurs/" . $utilisateur[0] . $extension;
                if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {

                    $fichier_temporaire = $_FILES["photo"]["tmp_name"];

                    // Créer le dossier de destination s'il n'existe pas
                    if (!file_exists("photo_profil_utilisateurs/")) {
                        mkdir("photo_profil_utilisateurs/", 0777, true); // Créez le dossier récursivement
                    }

                    // Déplacer le fichier temporaire vers le dossier de destination avec le nom approprié
                    if (move_uploaded_file($fichier_temporaire, $chemin_destination)) {
                        // Succès : fichier téléchargé et déplacé avec succès
                        echo '</br><div class="message-erreur">Fichier téléchargé avec succès.</div>';
                    } else {
                        // Erreur : échec du déplacement du fichier
                        echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                    }

                } else {
                    // Erreur : fichier non téléchargé ou erreur lors du téléchargement
                    echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                }

                $file = fopen($fichier, "r");

                if ($file) {
                    $donnees = $utilisateur[0] . ";" . $utilisateur[1] . ";" . $utilisateur[2] . ";" . $date . ";" . $genre . ";" . $pseudo . ";" . $situation . ";" . $adresse . ";" . $ville . ";" . $pays . ";" . $couleur_des_cheveux . ";" . $couleur_des_yeux . ";" . $poids . ";" . $taille . ";" . "\n";
                    file_put_contents("profil.txt", $donnees, FILE_APPEND);
                    header("Location: accueil.php");
                    fclose($file);
                    exit();
                } else {
                    // Gestion des erreurs de fichier
                    echo "Une erreur est survenue lors de l'ouverture du fichier.";
                }
            }
            ?>
        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>