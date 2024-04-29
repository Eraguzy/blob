<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
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
        <form name="connexion" method="post" action="#">
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
                <input type="button" class="bouton" value="Connexion" onclick="linkopener('page_connexion.php')"/>
                </div><br />
            </fieldset>
        </form>
    </div>
    <script src="script.js" type="text/javascript"></script>
</body>

</html>
