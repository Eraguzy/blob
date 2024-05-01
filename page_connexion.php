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
        <div class="titrebandeau" >Déjà membre</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')"/>
    </nav>

    <div class="contenu">
        <form name="connexion" method="post" action="page_connexion.php">
            <fieldset>
                <legend>Connexion</legend>
                <div class="div1">
                    Email :
                </div>
                <div class="div2">
                    <input type="text" name="email" class="champ" placeholder="Email"/>
                </div><br />
                <div class="div1">
                    Mot de passe :
                </div>
                <div class="div2">
                    <input type="text" name="mdp" class="champ" placeholder="Mot de passe"/>
                </div><br />
                <div class="div1">

                </div>
                <div class="div2">
                    <input type="submit" class="bouton" value="Connexion"/>
                </div><br />
            </fieldset>
        </form>
    </div>
    <?php
// ouverture du fichier pour vérifier si l'user existe ou non
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];
    $fichier = "profil.txt";
    $handle = fopen($fichier, "r");
    $utilisateur_trouve = false; 
    if ($handle) {
        while (($ligne = fgets($handle)) !== false) {
            $utilisateur = explode(",", $ligne);
            if ($utilisateur[2] == $email && $utilisateur[3] == $mdp) {
                $utilisateur_trouve = true; 
                break;
            }
        }
        fclose($handle); 
        if ($utilisateur_trouve) {
            header("Location: accueil.php"); 
            exit; 
        } else {
            echo "Vous n'êtes pas encore inscrit, vous allez être redirigier vers la page d'inscription";
            header("Location: page_inscription.php"); 
            exit;
        }
    }
}
?>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>
