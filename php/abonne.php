<?php

//Récupération des informations de la base de données
$fichier = "../database/compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);
//On récupère l'id utilisateur
$user_id = $_COOKIE['user_id'];

//Vérification si le cookie de connexion existe et si l'utilisateur s'est crée son profil
if (isset($_COOKIE['user_id'])) {
    if (!isset($_COOKIE['creation_profil']) || $_COOKIE['creation_profil'] == 0) {
        $profil_cree = 0;
        $json_content = file_get_contents($fichier);
        $data = json_decode($json_content, true);
        //On vérifie dans la base de donnée si le profil est crée si on a pas de cookie prouvant que l'utilisateur s'est crée un profil
        foreach ($data['profils'] as $profile) {
            //Profil trouvé, on crée un cookie avec la valeur 1
            if ($profile['id'] == $user_id) {
                $profil_cree = 1;
                setcookie("creation_profil", 1, time() + (30 * 24 * 3600), "/");
                break;
            }
        }
        //Pas de profil trouvé donc on crée le cookie avec la valeur 0
        if ($profil_cree == 0) {
            setcookie("creation_profil", 0, time() + (30 * 24 * 3600), "/");
            header("Location: ../php/creation_profil.php");
            exit;
        }
    }
} else {
    //Redirection vers la page de connexion si le cookie prouvant que l'utilisateur est connecté n'est pas présent
    header("Location: ../php/page_connexion.php");
    exit;
}

//On démarre la session pour récupérer les variables de la session
session_start();
if (isset($_SESSION['statut']) && $_SESSION['statut'] == 'utilisateur') {
    //L'utilisateur est redirigé vers accueil.php si il n'est pas abonné
    header("Location: accueil.php");
    exit;
}

// Trier les profils par ID en supposant que les IDs sont ordonnés chronologiquement
usort($data['profils'], function ($a, $b) {
    return strcmp($a['id'], $b['id']);
});

// Extraire les trois derniers profils
$derniers_utilisateurs = array_slice($data['profils'], -3);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/abonne.css">
    <title>Blob</title>
    <link rel="icon" href="../images/logo.png">
    <script>
        //fonction qui vérifie le statut toutes les 5 secondes
        function checkStatut() {
            fetch('verif_statut.php')
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.error) {
                        console.error('Erreur:', data.error);
                    } else if (data.valid) {
                        console.log(data.message);
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
        setInterval(checkStatut, 5000);
    </script>
</head>

<body>
    <!-- Bandeau de page avec les boutons de redirection pour se déconnecter et modifier son profil -->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Bonjour</div>
        <input id="boutonmodif" type="button" class="bouton" value="Modifier mon profil"
            onclick="linkopener('modif_profil.php')" />
        <input type="button" class="bouton" value="Déconnexion" onclick="linkopener('deconnexion.php')" />
    </nav>
    <p class="para">Cher abonné Blob, vous pouvez rechercher dès à présent des personnes en tapant un critère sur la
        barre de recherche, visualiser le profil complet, envoyer un mesage à quelqu'un, et bloquer une personne !</p>

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

        //On récupère le nombre de profil affiché
        function getResultsCount() {
            var profileElements = document.querySelectorAll(".profile");
            return profileElements.length;
        }

        //Redirection vers la page du profil ciblé en question et enregistrement de l'id dans la liste des stalkers du profil visité
        function viewProfile(id_utilisateur) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        console.log("ID enregistré avec succès");
                        window.location.href = 'page_profil.php?id_utilisateur=' + id_utilisateur;
                    }
                    else {
                        console.error("Erreur lors de l'enregistrement de l'ID: " + this.status);
                    }
                }
            };
            xhttp.open("POST", "stalkers.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("target_id=" + id_utilisateur);
            console.log("Requête envoyée pour enregistrer l'ID");
        }

        
        function Suggestions(str) {
            var filtre = document.querySelector('select[name="filtre"]').value; // Récupérer la valeur sélectionnée du champ select
            var xhttp;
            //Si la barre de recherche est vide ça n'affiche rien
            if (str.length == 0) {
                document.getElementById("res").innerHTML = "";
                adjustContentPadding(0); // Pas de résultats
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("res").innerHTML = this.responseText;
                    //On ajuste le padding en fonction du nombre de résultats
                    adjustContentPadding(getResultsCount());
                    // Ajouter un gestionnaire d'événements de clic pour chaque profil
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
        <!-- Privilèges des abonnés, ils peuvent cliquer sur ces boutons qui les redirigent vers des pages exclusives -->
        <div class="bouton3">
            <input type="button" class="bouton" value="Liste des bloqués" onclick="linkopener('liste_bloque.php')" />
            <input type="button" class="bouton" value="Vues de mon profil" onclick="linkopener('liste_vues.php')" />
            <input type="button" class="bouton" value="Extension du statut"
                onclick="linkopener('extension_statut.php')" />
        </div>
    </div>

    <!-- Les admins ont un bouton pour les rediriger vers une page dédiée -->
    <div class="conteneurdubas">
        <?php
        if (isset($_SESSION['statut']) && $_SESSION['statut'] == 'admin') {
            echo '<input type="button" class="bouton" id="interfaceadmin" value="Interface admin" onclick="linkopener(`../admin/adminmenu.php`)" />'; // faut mettre le `à l'intérieur pas pour les guillemets extérieurs jsp pq sinon ça marche pas
        }
        ?>
    </div>

    <!-- Le script pour les boutons de redirection vers une page annexe -->
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>
