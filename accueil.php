<?php
//Vérification si le cookie de connexion existe et si l'utilisateur s'est crée son profil
if (isset($_COOKIE['user_id'])) {
    if (!isset($_COOKIE['creation_profil']) || $_COOKIE['creation_profil'] == 0) {
        $profil_cree = 0;
        //On récupère l'id utilisateur
        $id_utilisateur = $_COOKIE['user_id'];
        //On vérifie dans la base de donnée si le profil est crée si on a pas de cookie prouvant que l'utilisateur s'est crée un profil
        $fichier = "compte.json";
        $json_content = file_get_contents($fichier);
        $data = json_decode($json_content, true);
        foreach ($data['profils'] as $profile) {
            // Profil trouvé, on crée un cookie avec la valeur 1
            if ($profile['id'] == $id_utilisateur) {
                $profil_cree = 1;
                setcookie("creation_profil", 1, time() + (30 * 24 * 3600), "/");
                break;
            }
        }
        //Pas de profil trouvé donc on crée le cookie avec la valeur 0
        if ($profil_cree == 0) {
            setcookie("creation_profil", 0, time() + (30 * 24 * 3600), "/");
            header("Location: creation_profil.php");
            exit;
        }
    }
} else {
    //Redirection vers la page de connexion si le cookie n'est pas présent
    header("Location: page_connexion.php");
    exit;
}

//Charger les données du fichier JSON
$fichier = "compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

//Si on veut accéder à accueil.php, on ne doit pas être un abonné 
session_start();
if (isset($_SESSION['statut']) && ($_SESSION['statut'] == 'decouverte' || $_SESSION['statut'] == 'vip' || $_SESSION['statut'] == 'classique')) {
    //Redirection vers la page abonne.php si l'utilisateur est abonné
    header("Location: abonne.php");
    exit;
}

//Trier les profils par ID en supposant que les IDs sont ordonnés chronologiquement
usort($data['profils'], function ($a, $b) {
    return strcmp($a['id'], $b['id']);
});

// Extraire les trois derniers profils
$derniers_utilisateurs = array_slice($data['profils'], -3);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/accueil.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>

<body>
    <!-- Bandeau de page avec les boutons de redirection pour se déconnecter et modifier son profil -->
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Bonjour</div>
        <input id="boutonmodif" type="button" class="bouton" value="Modifier mon profil"
            onclick="linkopener('modif_profil.php')" />
        <input type="button" class="bouton" value="Déconnexion" onclick="linkopener('deconnexion.php')" />
    </nav>
    <p class="para">Vous êtes maintenant inscrit sur Blob, vous pouvez rechercher dès à présent des personnes en tapant
        des mots-clés sur la barre de recherche.</p>

    <div class="conteneur">
        <!-- Barre de recherche avec filtre -->
        <form action="page_recherche.php" method="get" class="recherche">
            <input type="text" name="q" id="recherche" placeholder="Rechercher..." onkeyup="Suggestions(this.value)">
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
        <div id="res"></div>
    </div>
    <script>
        //On ajuste le padding en fonction du nombre de résultats
        function adjustContentPadding(resultsCount) {
            console.log("Nombre de résultats:", resultsCount);
            var contentElement = document.querySelector(".contenu");
            var paddingTop = 30 + resultsCount * 40;
            console.log("Padding top calculé:", paddingTop);
            contentElement.style.paddingTop = paddingTop + "px";
        }

        //Le nombre de profil affiché
        function getResultsCount() {
            var profileElements = document.querySelectorAll(".profile");
            return profileElements.length;
        }

        //Redirection vers la page du profil ciblé en question
        function viewProfile(id_utilisateur) {
            window.location.href = 'page_resume.php?id_utilisateur=' + id_utilisateur;
        }

        function Suggestions(str) {
            var filtre = document.querySelector('select[name="filtre"]').value; //Récupérer la valeur sélectionnée du champ select
            var xhttp;
            //Si la barre de recherche est vide ça n'affiche rien dans le cas contraire ça 
            if (str.length == 0) {
                document.getElementById("res").innerHTML = "";
                adjustContentPadding(0); //Pas de résultats
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("res").innerHTML = this.responseText;
                    //On ajuste le padding en fonction du nombre de résultats
                    adjustContentPadding(getResultsCount());
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
            xhttp.open("GET", "recherche.php?q=" + str + "&filtre=" + filtre + "&limit=true", true);
            xhttp.send();
        }
    </script>

    <div class="contenu">

        <!-- On affiche les 3 derniers profils inscrits -->
        <p>Les trois derniers profils inscrits sur Blob :</p><br>
        <ul id="utilisateurs">
            <?php foreach ($derniers_utilisateurs as $utilisateur): ?>
                <li><?php echo htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']); ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="para">Afin d'échanger avec tous les utilisateurs de Blob, cliquez sur le bouton en bas afin de
            découvrir toutes nos offres d'abonnement !</p>
    </div>

    <!-- Bouton pour souscrire aux divers abonnements proposés -->
    <div class="conteneurdubas">
        <input type="button" class="bouton souscription" value="Souscrire" onclick="linkopener('souscription.php')" />

        <!-- Si l'utilisateur est un administrateur il est renvoyé vers le menu administrateur -->
        <?php 
        if (isset($_SESSION['statut']) && $_SESSION['statut'] == 'admin'){
            echo '<input type="button" class="bouton souscription" value="Interface admin" onclick="linkopener(`admin/adminmenu.php`)" />'; // faut mettre le `à l'intérieur pas pour les guillemets extérieurs jsp pq sinon ça marche pas
        }
        ?>
    </div>

    <!-- Le script pour les boutons de redirection vers une page annexe -->
    <script src="script.js" type="text/javascript"></script>
</body>

</html>