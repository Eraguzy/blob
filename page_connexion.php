<?php

//Démarrage de la session
session_start();

//Si l'utilisateur est connecté il est redirigé vers accueil.php
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
    exit;
}

//Lors de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Initialisation des variables
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];
    $fichier = "compte.json";

    //On vérifie si le fichier existe si c'est le cas on charge le données dans une variables sinon on crée le fichier 
    if (!file_exists($fichier)) {
        $data = ["utilisateurs" => []];
    } else {
        $json_content = file_get_contents($fichier);
        $data = json_decode($json_content, true);
    }

    $utilisateur_trouve = false;
    $mdp_correct = false;
    $statut_utilisateur = null;

    //On cherche le profil dans la base de données en regardant l'email saisi ensuite on regarde si le mdp associé à l'adresse mail est identique à celui saisi
    foreach ($data['utilisateurs'] as $utilisateur) {
        if ($utilisateur['email'] == $email) {
            $utilisateur_trouve = true;
            if (password_verify($mdp, $utilisateur['mot_de_passe'])) {
                $mdp_correct = true;
                $id_utilisateur = $utilisateur['id'];
                foreach ($data['profils'] as $profil) {
                    if ($profil['id'] == $id_utilisateur) {
                        $statut_utilisateur = $profil['statut'];
                        break;
                    }
                }
                break;
            }
        }
    }

    //Si on a bien trouvé l'utilisateur on crée un cookie avec l'id de l'utilisateur et une session qui stock le statut et l'id de l'utilisateur
    if ($utilisateur_trouve && $mdp_correct) {
        setcookie("user_id", $id_utilisateur, time() + (30 * 24 * 3600), "/");
        $_SESSION['user_id'] = $id_utilisateur;
        $_SESSION['statut'] = $statut_utilisateur;
        
        //En fonction de son statut l'utilisateur est redirigé vers sa page d'accueil
        if ($statut_utilisateur == 'classique' || $statut_utilisateur == 'vip' || $statut_utilisateur == 'decouverte') {
            header("Location: abonne.php");
        } else if ($statut_utilisateur == 'admin') {
            header("Location: admin/adminmenu.php");
        }
        else if($statut_utilisateur == 'utilisateur'){
            header("Location: accueil.php");
        }
        exit;
        //Si l'utilisateur est pas trouvé il est redirigé vers la page d'inscription
    } else if (!$utilisateur_trouve) {
        header("Location: page_inscription.php");
        exit;
    } else {
        //Message d'erreur car mot de passe incorrect
        $error_message = "Mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_connexion.css">
    <link rel="icon" href="logo.png">
    <title>Blob</title>
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Déjà membre</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <!-- Boite contenant le formulaire de connexion -->
    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2 class="legende">Connexion</h2>
            <form name="connexion" method="post" action="page_connexion.php">
                <div class="donnees">
                    <label for="email">Email :</label>
                    <input type="text" name="email" class="champ" placeholder="Email" required>
                </div>
                <div class="donnees">
                    <label for="mdp">Mot de passe :</label>
                    <input type="password" name="mdp" class="champ" placeholder="Mot de passe" required>
                </div>
                <input type="submit" value="Connexion" />
            </form>
            <?php
            //C'est là qu'on affiche les messages d'erreurs dans la boite
            if (isset($error_message)) {
                echo '<div class="message-erreur">' . $error_message . '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Le script pour le bouton d'accueil pour rediriger vers la page -->
    <script src="script.js" type="text/javascript"></script>
</body>

</html>
