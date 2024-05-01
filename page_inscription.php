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
        <div class="titrebandeau" >Nouveau membre</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')"/>
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2 class="legende">Inscription</h2>
            <form name="connexion" method="post" action="#">
                <div class="donnees">
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" class="champ" placeholder="Nom" required>
                </div>
                <div class="donnees">
                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" class="champ" placeholder="Prénom" required>
                </div>
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
        </div>
    </div>
    
    <?php
    // récupération des données du form, écriture dans un fichier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $confirm_email = $_POST["confirm_email"]; 
    $mdp = $_POST["mdp"];
    $confirm_mdp = $_POST["confirm_mdp"]; 
    $fichier = "profil.txt";
    $handle = fopen($fichier, "r");
    $utilisateur_trouve = false;
    if ($email != $confirm_email || $mdp != $confirm_mdp) {
        echo "Les champs de confirmation ne correspondent pas.";
        exit; 
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Merci de saisir un email valide.";
    }
    if ($handle) {
        while (($ligne = fgets($handle)) !== false) {
            $utilisateur = explode(",", $ligne);
            if ($utilisateur[2] == $email && $utilisateur[3] == $mdp) {
                $utilisateur_trouve = true; 
                break; 
                fclose($handle); 
            }
        }
        if ($utilisateur_trouve) {
            header("Location: accueil.php"); 
            exit; 
        } 
        else {
            $donnees = $nom . "," . $prenom . "," . $email . "," . $mdp . "," . "\n";
            file_put_contents("profil.txt", $donnees, FILE_APPEND);
            header("Location: page_profil.php");
            exit(); 
        }
    }
}
    ?>
    <script src="script.js" type="text/javascript"></script>
</body>

</html>
