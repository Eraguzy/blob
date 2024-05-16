

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/freetour.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Free Tour</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('deconnexion.php')" />
    </nav>
    <p class="para">Vous n'êtes pas encore abonné ? Voici un petit aperçu de ce qui pourrait vous attendre si vous nous rejoignez ! Découvrez quelques couples qui se sont rencontrés sur Blob !!</p>

    <div class="conteneur">
        <div class="couple1">
            <img src="couple1.jpg" class="image1">
            <div class="text1">
            Passionés d'animaux aquatiques, Laeticia et Thibault se sont rencontrés en 2019 sur Blob, et depuis, ils filent le parfait amour ! <br>
            <div class="temoi1">"Bonjour à tous,

Je m'appelle Thibault et je voulais partager avec vous une expérience incroyable. Grâce à Blob, un site de rencontre pour propriétaires de poissons rouges, j'ai rencontré Laeticia. Nos discussions sur nos poissons ont rapidement évolué en une histoire d'amour sincère. Blob nous a donné l'opportunité de nous rencontrer et de construire quelque chose de beau ensemble. Si vous cherchez l'amour et que vous êtes passionné par les poissons rouges, ne perdez pas espoir. Votre âme sœur pourrait bien être là, sur Blob, prête à vous rencontrer.

Thibault"</div>
            </div>
        </div>
        <div class="couple2">
            <img src="couple2.jpg" class="image2">
            <div class="text2">Pour Rayan et Audrey, ça a été le coup de foudre dès le premier date... Et cela pourrait vous arriver aussi ! </div>
            <div class="temoi2">Je suis Audrey, et je suis là pour partager une petite histoire qui a changé ma vie. Sur Blob, ce site de rencontre original pour les amoureux des poissons rouges, j'ai rencontré Rayan. Nos premiers échanges sur nos compagnons aquatiques ont rapidement évolué en une connexion spéciale. Grâce à Blob, nous nous sommes trouvés, et depuis, notre relation ne cesse de s'épanouir. Alors, si vous êtes passionné par les poissons rouges et que vous recherchez l'amour, n'hésitez pas à plonger dans l'aventure. Votre futur compagnon, comme le mien, pourrait bien être là, attendant juste de vous rencontrer sur Blob.

Audrey"</div>
        </div>
    </div>
    <script>
        function adjustContentPadding(resultsCount) {
            console.log("Nombre de résultats:", resultsCount);
            var contentElement = document.querySelector(".contenu");
            var paddingTop = 30 + resultsCount * 40;
            console.log("Padding top calculé:", paddingTop);
            contentElement.style.paddingTop = paddingTop + "px";
        }

        function getResultsCount() {
            var profileElements = document.querySelectorAll(".profile");
            return profileElements.length;
        }

        function viewProfile(id_utilisateur) {
            window.location.href = 'page_profil.php?id_utilisateur=' + id_utilisateur;
        }

        function Suggestions(str) {
            var xhttp;
            if (str.length == 0) {
                document.getElementById("res").innerHTML = "";
                adjustContentPadding(0); // Pas de résultats
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("res").innerHTML = this.responseText;
                    adjustContentPadding(getResultsCount());

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
            xhttp.open("GET", "recherche.php?q=" + str + "&limit=true", true);
            xhttp.send();
        }
    </script>

    <div class="contenu">
        <p class="para">Afin d'échanger avec tous les utilisateurs de Blob, cliquez sur le bouton en bas afin de
            découvrir toutes nos offres d'abonnement !</p>
    </div>
    <input type="button" class="bouton" id="souscription" value="Souscrire" onclick="linkopener('souscription.php')" />

    <script src="script.js" type="text/javascript"></script>
</body>

</html>