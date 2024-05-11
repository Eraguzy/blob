<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
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
                $mdp_incorrecte = true;

                if (!file_exists($fichier)) {
                    $data = ["utilisateurs" => []];
                } else {
                    $json_content = file_get_contents($fichier);
                    $data = json_decode($json_content, true);
                }

                foreach ($data['utilisateurs'] as $utilisateur) {
                    if ($utilisateur['email'] == $email) {
                        $utilisateur_trouve = true;
                        $id_utilisateur = $utilisateur['id'];
                        break;
                        if($utilisateur['mdp'] == $mdp){
                            $mdp_incorrecte = false;
                        }
                    }
                }

                if ($utilisateur_trouve == true && $mdp_incorrecte == false) {
                    setcookie("user_id", $id_utilisateur, time() + (30 * 24 * 3600), "/");
                    header("Location: accueil.php");
                    exit;
                } else if ($utilisateur_trouve == false) {
                    header("Location: page_inscription.php");
                    exit;
                } else {
                    echo '</br><div class="message-erreur">Mot de passe incorrecte.</div>';
                    exit;
                }
            }
            ?>
        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>