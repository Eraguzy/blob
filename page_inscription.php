<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
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

    <div class="contenu">
        <form name="connexion" method="post" action="#">
            <fieldset>
                <legend>Inscription</legend>
                <div class="div1">
                    Nom :
                </div>
                <div class="div2">
                    <input type="text" name="nom" class="champ" placeholder="Nom"/>
                </div><br />
                <div class="div1">
                    Prénom :
                </div>
                <div class="div2">
                    <input type="text" name="prenom" class="champ" placeholder="Prénom"/>
                </div><br />
                <div class="div1">
                    Email :
                </div>
                <div class="div2">
                    <input type="text" name="email" class="champ" placeholder="Email"/>
                </div><br />
                <div class="div1">
                    Confirmer l'email :
                </div>
                <div class="div2">
                    <input type="text" name="email" class="champ" placeholder="Email"/>
                </div><br />
                <div class="div1">
                    Créer un mot de passe : 
                </div>
                <div class="div2">
                    <input type="text" name="mdp" class="champ" placeholder="Mot de passe"/>
                </div><br />
                <div class="div1">
                    Confirmer le mot de passe : 
                </div>
                <div class="div2">
                    <input type="text" name="mdp" class="champ" placeholder="Mot de passe"/>
                </div><br />
                <div class="div1">

                </div>
                <div class="div2">
                <input type="button" class="bouton" value="Inscription" onclick="linkopener('page_profil.php')"/>
                </div><br />
            </fieldset>
        </form>
    </div>
    <script src="script.js" type="text/javascript"></script>
</body>

</html>
