<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <nav class="bandeau">
        <input type="button" class="bouton" value="Acceuil" onclick="linkopener('index.php')"/>
    </nav>

    <div class="contenu">
        <form name="connexion" method="post" action="#">
            <fieldset>
                <legend>Connexion</legend>
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

                </div>
                <div class="div2">
                    <input type="submit" name="connexion" value="Se connecter" class="champ" />
                </div><br />
            </fieldset>
        </form>
    </div>
    <script src="script.js" type="text/javascript"></script>
</body>

</html>