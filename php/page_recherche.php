<?php
//On regarde si l'utilisateur est connecté grâce au cookie avec l'id utilisateur
if (isset($_COOKIE['user_id'])) {
    if (!isset($_GET['q'])) {
        //Si il n'y a pas d'argument q on redirige vers la page d'accueil
        header("Location: accueil.php");
        exit;
    }
} else {
    //Si il n'est pas connecté il est redirigé vers la page de connexion
    header("Location: ../php/page_connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/page_recherche.css">
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Recherche</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <div class="conteneur">
                <!-- Barre de recherche avec filtre -->
                <form action="#" method="get" class="recherche">
                    <input type="text" name="q" id="recherche" placeholder="Recherche" value="<?php echo $_GET['q']; ?>" onkeyup="Suggestions(this.value)">
                    <select name="filtre">
                        <option value="">Pseudo</option>
                        <option value="nom">Nom</option>
                        <option value="prenom">Prenom</option>
                        <option value="date">Date de naissance</option>
                        <option value="genre">Genre</option>
                        <option value="ville">Ville</option>
                        <option value="pays">Pays</option>
                        <option value="situation">situation</option>
                        <option value="couleur_des_yeux">Couleur des yeux</option>
                        <option value="couleur_des_cheveux">Couleur des cheveux</option>
                        <option value="taille">Taille</option>
                        <option value="poids">Poids</option>
                    </select>
                    <button type="submit">Rechercher</button>
                </form>
                <!-- C'est la où les résultats s'affichent -->
                <div id="res"></div>
            </div>
        </div>
    </div>

    <script>
        //Redirige l'utilisateur vers la page de profil de l'utilisateur ciblé
        function viewProfile(id_utilisateur) {
            window.location.href = 'page_profil.php?id_utilisateur=' + id_utilisateur;
        }

        //Récupère le filtre et recherche des profils correspondant à ce qui est tappé dans la barre de recherche avec le filtre choisi
        function Suggestions(str) {
            var filtre = document.querySelector('select[name="filtre"]').value; // Récupérer la valeur sélectionnée du champ select
            var xhttp;
            if (str.length == 0) {
                document.getElementById("res").innerHTML = "";
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //Remplace le vide par le résultat de la recherche
                    document.getElementById("res").innerHTML = this.responseText;
                    //Gestionnaire d'événements de clic pour chaque profil
                    //Lorsque l'utilisateur clique sur un profil, il est redirigé vers la page du profil en question
                    var profileElements = document.querySelectorAll(".profile");
                    profileElements.forEach(function (element) {
                        element.addEventListener('click', function () {
                            var id_utilisateur = element.getAttribute('data-user-id');
                            viewProfile(id_utilisateur);
                        });
                    });
                }
            };
            //On envoie le champ de la barre de recherche, le filtre et si il y a une limite au nombre de résultats ou non à un algorithme de recherche
            xhttp.open("GET", "recherche.php?q=" + str + "&filtre=" + filtre);
            xhttp.send();
        }

        //Ajuste la taille de la boite en fonction des résultats
        window.addEventListener('load', function () {
            var Taille_boite = document.querySelector('.Connexion-boite').offsetHeight;
            var Taille_barre = document.querySelector('.recherche').offsetHeight;
            var Taille_boite_resultats = Taille_boite - Taille_barre - 40;
            document.getElementById('res').style.maxHeight = Taille_boite_resultats + 'px';
            var Largeur_boite = document.querySelector('.Connexion-boite').offsetWidth;
            var Largeur_boite_resultats = Largeur_boite - 115.15 - 40; // 40 px pour la marge 115.15px largeur profil
            document.getElementById('res').style.paddingRight = Largeur_boite_resultats + 'px';
        });
    </script>

    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>
