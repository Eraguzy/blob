<?php
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
    exit;
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
                $fichier = "compte.json";

                if (!file_exists($fichier)) {
                    $data = ["utilisateurs" => []];
                } else {
                    $json_content = file_get_contents($fichier);
                    $data = json_decode($json_content, true);
                }

                $utilisateur_trouve = false;
                $mdp_correct = false;
                $statut_utilisateur = null;

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
            

                if ($utilisateur_trouve && $mdp_correct) {
                    setcookie("user_id", $id_utilisateur, time() + (30 * 24 * 3600), "/");
                    // Changement : Redirection basée sur le statut de l'utilisateur
                    if ($statut_utilisateur == 'classique' || $statut_utilisateur == 'vip' || $statut_utilisateur == 'decouverte') {
                        header("Location: abonne.php");
                    }
                    else if($statut_utilisateur == 'admin'){ //redirection de l'admin
                        header("Location: admin/adminmenu.php");
                    }
                    exit;
                } else if (!$utilisateur_trouve) {
                    echo '</br><div class="message-erreur">Email incorrect.</div>';
                    header("Location: page_inscription.php");
                } else {
                    echo '</br><div class="message-erreur">Mot de passe incorrect.</div>';
                }
            }
            ?>
        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>
