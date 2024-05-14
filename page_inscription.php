<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_inscription.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Nouveau membre</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

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
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST["email"];
                $confirm_email = $_POST["confirm_email"];
                $mdp = $_POST["mdp"];
                $confirm_mdp = $_POST["confirm_mdp"];
                $fichier = "compte.json";

                if ($email != $confirm_email || $mdp != $confirm_mdp) {
                    echo '</br><div class="message-erreur">Les champs de confirmation ne correspondent pas.</div>';
                    exit;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo '</br><div class="message-erreur">Merci de saisir un email valide.</div>';
                    exit;
                }

                if (!file_exists($fichier)) {
                    $data = ["utilisateurs" => []];
                } else {
                    $json_content = file_get_contents($fichier);
                    $data = json_decode($json_content, true);
                }

                foreach ($data['utilisateurs'] as $utilisateur) {
                    if ($utilisateur['email'] == $email) {
                        echo '</br><div class="message-erreur">Cet email est déjà utilisé.</div>';
                        exit;
                    }
                }

                $hash = password_hash($mdp, PASSWORD_BCRYPT);

                $id_utilisateur = uniqid();

                $nouvel_utilisateur = [
                    'id' => $id_utilisateur,
                    'email' => $email,
                    'mot_de_passe' => $hash
                ];

                $data['utilisateurs'][] = $nouvel_utilisateur;

                $json_data = json_encode($data, JSON_PRETTY_PRINT);

                file_put_contents($fichier, $json_data);

                setcookie("user_id", $id_utilisateur, time() + (30 * 24 * 3600), "/");
                setcookie("creation_profil", 0, time() + (30 * 24 * 3600), "/");

                header("Location: creation_profil.php");
                exit();
            }
            ?>
        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>