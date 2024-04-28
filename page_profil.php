<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>
<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau" >Création du profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')"/>
    </nav>
    <div class="contenu">
        <form name="création" method="post" action="#">
            <fieldset>
                <legend>Création du profil</legend>
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
                    Age :
                </div>
                <div class="div2">
                    <input type="number" name="age" class="age" placeholder="Age"/>
                </div><br />
                <div class="div1">
                    Pseudonyme :
                </div>
                <div class="div2">
                    <input type="text" name="pseudo" class="champ" placeholder="Pseudonyme"/>
                </div><br />
                <div class="div1">
                    Photo : 
                </div>
                <div class="div2">
                <input type="file" id="photo" name="photo" accept="image/jpeg, image/png" required><br><br>
                </div><br />
                <div class="div1">
                    Couleur des cheveux : 
                </div>
                <div class="div2">
                <select name="couleur_des_cheveux" id="couleur_des_cheveux" required>
                    <option value="brun">Brun</option>
                    <option value="blond">Blond</option>
                    <option value="roux">Roux</option>
                    <option value="noir">Noir</option>
                    <option value="autre">Autre</option>
                </select></div><br><br>
                <div class="div1">
                    Couleur des yeux : 
                </div>
                <div class="div2">
                <select name="couleur_des_yeux" id="couleur_des_yeux" required>
                    <option value="bleu">Bleu</option>
                    <option value="vert">Vert</option>
                    <option value="marron">Marron</option>
                    <option value="autre">Autre</option>
                </select><br><br>

                </div><br />
                <div class="div1">

                </div>
                <div class="div2">
                <input type="button" class="bouton" value="Création du profil" onclick="linkopener('page_profil.php')"/>
                </div><br />
            </fieldset>
        </form>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>
</html>
