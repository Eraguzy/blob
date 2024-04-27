<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="page_connexion.css">
</head>

<body>
    <!-- Bandeau de navigation -->
    <nav class="bandeau">
        <a href="index.html" class="bouton-connexion">Accueil</a>
    </nav>

    <!-- Contenu de la page -->
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
</body>

</html>