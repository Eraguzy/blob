<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    header("Location: index.php");
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
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Déjà membre</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

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
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST["email"];
                $mdp = $_POST["mdp"];
                $fichier = "compte.txt";
                $file = fopen($fichier, "r");
                $utilisateur_trouve = false;

                if ($file) {
                    while (($ligne = fgets($file)) !== false) {
                        $utilisateur = explode(";", $ligne);
                        if(isset($utilisateur[3])){
                            $email_data = $utilisateur[3];
                        } else{
                            $email_data = null;
                        }
                        if(isset($utilisateur[4])){
                            $mdp_data = $utilisateur[4];
                        } else{
                            $mdp_data = null;
                        }

                        // Vérification du mot de passe avec password_verify()
                        if ($email == $email_data) {
                            if (password_verify($mdp, $mdp_data)) {
                                $utilisateur_trouve = true;
                                break;
                            } else {
                                $mdp_incorrecte = true;
                                echo '</br><div class="message-erreur">Mot de passe incorrecte</div>';
                                break;
                            }
                        }
                    }
                    fclose($file);

                    if ($utilisateur_trouve == true) {
                        // Création d'un cookie avec une durée de vie de 30 jours
                        setcookie("user_id", $ligne, time() + (30 * 24 * 3600), "/");
                        // Redirection vers la page d'accueil
                        header("Location: accueil.php");
                        exit;
                    } else if ($mdp_incorrecte == false) {
                        // Redirection vers la page d'inscription
                        header("Location: page_inscription.php");
                        exit;
                    }
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