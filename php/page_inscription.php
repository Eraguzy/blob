<?php
//Si l'utilisateur est connecté il est redirigé vers accueil.php
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/page_inscription.css">
    <title>Blob</title>
    <link rel="icon" href="../images/logo.png">
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil -->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Nouveau membre</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <!-- Boite contenant le formulaire d'inscription -->
    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2 class="legende">Inscription</h2>
            <form name="connexion" method="post" action="#">
                <div class="donnees">
                    <label for="email">Email :</label>
                    <input type="email" name="email" class="champ" placeholder="Email" required>
                </div>
                <div class="donnees">
                    <label for="email2">Confirmer l'email :</label>
                    <input type="text" name="confirm_email" class="champ" placeholder="Email" required>
                </div>
                <div class="donnees">
                    <label for="motdepasse">Mot de passe :</label>
                    <input type="password" name="mdp" class="champ" placeholder="Mot de passe" required>
                </div>
                <div class="donnees">
                    <label for="motdepasse2">Confirmer le mot de passe :</label>
                    <input type="password" name="confirm_mdp" class="champ" placeholder="Mot de passe" required>
                </div>
                <input type="submit" value="Inscription" />
            </form>
            <?php

            //Lors de la soumission du formulaire
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                //Initialisation des variables
                $email = $_POST["email"];
                $confirm_email = $_POST["confirm_email"];
                $mdp = $_POST["mdp"];
                $confirm_mdp = $_POST["confirm_mdp"];
                $fichier = "../database/compte.json";

                //On vérifie si les adresses mails et mdp correspondent à ceux des champs de confirmation
                if ($email != $confirm_email || $mdp != $confirm_mdp) {
                    echo '</br><div class="message-erreur">Les champs de confirmation ne correspondent pas.</div>';
                    exit;
                }

                //On vérifie si l'email est un email valide
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo '</br><div class="message-erreur">Merci de saisir un email valide.</div>';
                    exit;
                }

                //On vérifie si le fichier existe si c'est le cas on charge le données dans une variables sinon on crée le fichier
                if (!file_exists($fichier)) {
                    $data = ["utilisateurs" => []];
                } else {
                    $json_content = file_get_contents($fichier);
                    $data = json_decode($json_content, true);
                }

                //On vérifie si le compte est bannie t si oui on affiche un message d'erreur et empêche l'inscription
                $jsonbans = file_get_contents('../admin/json/bannissements.json'); //vérifie si l'email est banni lors de l'inscription
                $datajson = json_decode($jsonbans, true);
                foreach($datajson['bannissements'] as $banni){
                    if($banni['email'] == $email){
                        echo '</br><div class="message-erreur">Cet email est banni.</div>';
                        exit;
                    }
                }

                //On vérifie si l'email est déjà utilisé et si oui on affiche un message d'erreur et empêche l'inscription
                foreach ($data['utilisateurs'] as $utilisateur) {
                    if ($utilisateur['email'] == $email) {
                        echo '</br><div class="message-erreur">Cet email est déjà utilisé.</div>';
                        exit;
                    }
                }

                //On crypte le mot de passe
                $hash = password_hash($mdp, PASSWORD_BCRYPT);

                //On crée un id unique
                $id_utilisateur = uniqid();

                //On crée un tableau associatif avec l'id nouvellement généré, l'email saisi et le mot de passe saisi crypté
                $nouvel_utilisateur = [
                    'id' => $id_utilisateur,
                    'email' => $email,
                    'mot_de_passe' => $hash
                ];

                //On ajoute l'utilisateur et ses données dans la base de données en php
                $data['utilisateurs'][] = $nouvel_utilisateur;

                //Passage en json
                $json_data = json_encode($data, JSON_PRETTY_PRINT);

                //Mise à jour dans le fichier json
                file_put_contents($fichier, $json_data);

                //Création d'un cookie avec l'id utilisateur pour montrer qu'il est connecté et l'identifier, ainsi qu'un cookie montrant que l'utilisateur n'a pas encore crée son profil
                setcookie("user_id", $id_utilisateur, time() + (30 * 24 * 3600), "/");
                setcookie("creation_profil", 0, time() + (30 * 24 * 3600), "/");

                //Redirection vers la page de création de profil
                header("Location: ../php/creation_profil.php");
                exit();
            }
            ?>
        </div>
    </div>

    <!-- Le script pour le bouton d'accueil pour rediriger vers la page -->
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>
