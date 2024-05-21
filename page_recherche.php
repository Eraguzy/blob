<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if (!isset($_GET['q'])) {
        header("Location: accueil.php");
        exit;
    }
} else {
    header("Location: page_connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_recherche.css">
    <link rel="icon" href="logo.png">
    <title>Blob</title>
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Recherche</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <div class="conteneur">
                <form action="#" method="get" class="recherche">
                    <input type="text" name="q" id="recherche" placeholder="<?php echo $_GET['q']; ?>"
                        onkeyup="Suggestions(this.value)">
                    <button type="submit">Rechercher</button>
                </form>
                <div id="res"></div>
            </div>
        </div>
    </div>

    <script>
        function viewProfile(id_utilisateur) {
            window.location.href = 'page_profil.php?id_utilisateur=' + id_utilisateur;
        }

        function Suggestions(str) {
            var xhttp;
            var cheminimg = "photo_profil_utilisateurs/";
            if (str.length == 0) {
                document.getElementById("res").innerHTML = "";
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("res").innerHTML = this.responseText;

                    // Ajouter un gestionnaire d'événements de clic pour chaque profil
                    var profileElements = document.querySelectorAll(".profile");
                    profileElements.forEach(function (element) {
                        element.addEventListener('click', function () {
                            var id_utilisateur = element.getAttribute('data-user-id');
                            viewProfile(id_utilisateur);
                        });
                    });
                }
            };
            xhttp.open("GET", "../recherche.php?q=" + str + "&cheminimg=" + cheminimg, true);
            xhttp.send();
        }

        window.addEventListener('load', function () {
            var Taille_boite = document.querySelector('.Connexion-boite').offsetHeight;
            var Taille_barre = document.querySelector('.recherche').offsetHeight;
            var Taille_boite_resultats = Taille_boite - Taille_barre - 40;
            document.getElementById('res').style.maxHeight = Taille_boite_resultats + 'px';
            var Largeur_boite = document.querySelector('.Connexion-boite').offsetWidth;
            var Largeur_boite_resultats = Largeur_boite - 115.15 - 40 ; // 40 px pour la marge 115.15px largeur profil
            document.getElementById('res').style.paddingRight = Largeur_boite_resultats + 'px';
        });
    </script>
    </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>