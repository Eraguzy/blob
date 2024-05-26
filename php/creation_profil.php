<?php
ob_start();

//Si l'utilisateur est connecté on récupère son id
if (isset($_COOKIE['user_id'])) {
    $id_utilisateur = $_COOKIE['user_id'];
} else {
    //Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../php/page_connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/creation_profil.css">
    <title>Blob</title>
    <link rel="icon" href="../images/logo.png">
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Création du profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')">
    </nav>

    <!-- Formulaire de création de profil avec demandes de multiples champs à remplir ainsi que d'une image de profil, les champs ne peuvent rester vides -->
    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2 class="legende">Création du profil</h2>
            <form name="creation_profil" method="post" action="#" enctype="multipart/form-data">
                <div class="donnees">
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" class="champ" placeholder="Nom" required>
                </div>
                <div class="donnees">
                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" class="champ" placeholder="Prénom" required>
                </div>
                <div class="donnees">
                    <label for="date">Date de naissance :</label>
                    <input type="date" name="date" class="date" placeholder="Date" required min="1900-01-01"
                        max="<?php echo date('Y-m-d'); ?>">
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
                    <input type="number" id="poids" name="poids" placeholder="Poids" required min="30" max="250">
                </div>
                <div class="donnees">
                    <label for="taille">Taille :</label>
                    <input type="number" id="taille" name="taille" placeholder="Taille" required min="100" max="250">
                </div>
                <input type="submit" value="Création du profil" />
            </form>
            <?php

            //On attend l'envoi du formulaire avant d'éxécuter le code
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                //On récupère les informations saisies qu'on stock dans des variables
                $nom = $_POST["nom"];
                $prenom = $_POST["prenom"];
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
                $fichier = "../database/compte.json";

                //On crée une chemin dynamique pour stocker l'image
                $extension = ".jpg";
                $chemin_destination = "../photo_profil_utilisateurs/" . $id_utilisateur . $extension;
                //On vérifie qu'une image a bien été téléchargé
                if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
                    $fichier_temporaire = $_FILES["photo"]["tmp_name"];
                    //Si le dossier contenant les images n'existe pas, on le crée
                    if (!file_exists("../photo_profil_utilisateurs/")) {
                        mkdir("../photo_profil_utilisateurs/", 0777, true);
                    }
                    //On le télécharge dans le dossier et on vérifie que le téléchargement c'est bien fait sinon on affiche un message d'erreur à l'utilisateur
                    if (move_uploaded_file($fichier_temporaire, $chemin_destination)) {
                        echo '</br><div class="message-erreur">Fichier téléchargé avec succès.</div>';
                    } else {
                        echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                    }
                } else {
                    echo '</br><div class="message-erreur">Une erreur s\'est produite lors du téléchargement du fichier.</div>';
                }

                //On vérifie que le fichier json servant de base de données existe avec une liste de profils sinon on le crée
                if (file_exists($fichier)) {
                    $json_contenue = file_get_contents($fichier);
                    $data = json_decode($json_contenue, true);
                    if (!isset($data['profils'])) {
                        //Seul la liste des profils n'existe pas
                        $data['profils'] = [];
                    }
                } else {
                    //On crée le fichier car il n'existe pas
                    $data = ["profils" => []];
                }

                //On crée une structure profil avec toutes les informations saisies par l'utilisateur des variables initialiser précédemment
                $nouveau_profil = [
                    'id' => $id_utilisateur,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'date' => $date,
                    'genre' => $genre,
                    'pseudo' => $pseudo,
                    'situation' => $situation,
                    'adresse' => $adresse,
                    'ville' => $ville,
                    'pays' => $pays,
                    'couleur_des_cheveux' => $couleur_des_cheveux,
                    'couleur_des_yeux' => $couleur_des_yeux,
                    'taille' => $taille,
                    'poids' => $poids,
                    'statut' => 'utilisateur',  
                    'utilisateurs_bloques' => [],
                    'stalkers' => [],
                ];

                //On ajoute le nouveau profil dans la liste des profils
                $data['profils'][] = $nouveau_profil;

                //On injecte la liste de profil actualisé dans la base de donnée json
                $json_new_profil = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents($fichier, $json_new_profil);

                //On crée un cookie prouvant que l'utilisateur a bien crée son compte et on le redirige vers l'accueil pour les utilisateurs connectés.
                $expiration = time() + (30 * 24 * 60 * 60);
                setcookie("creation_profil", 1, $expiration, "/");
                header("Location: accueil.php");
                exit();
            }
            ?>
        </div>
    </div>

    <!-- Le script pour le bouton d'accueil pour rediriger vers la page -->
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>
