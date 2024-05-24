<?php
$fichier = "compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);
$user_id = $_COOKIE['user_id'];

// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if (!isset($_COOKIE['creation_profil']) || $_COOKIE['creation_profil'] == 0) {
        $profil_cree = 0;
        $id_utilisateur = $_COOKIE['user_id'];
        $fichier = "compte.json";
        $json_content = file_get_contents($fichier);
        $data = json_decode($json_content, true);
        foreach ($data['profils'] as $profile) {
            if ($profile['id'] == $id_utilisateur) {
                $profil_cree = 1;
                setcookie("creation_profil", 1, time() + (30 * 24 * 3600), "/");
                break;
            }
        }
        if ($profil_cree == 0) {
            setcookie("creation_profil", 0, time() + (30 * 24 * 3600), "/");
            header("Location: creation_profil.php");
            exit;
        }
    }
} else {
    // Redirection vers la page de connexion si le cookie n'est pas présent
    header("Location: page_connexion.php");
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
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/abonne.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
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
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Bonjour</div>
        <input id="boutonmodif" type="button" class="bouton" value="Modifier mon profil" onclick="linkopener('modif_profil.php')" />
        <input type="button" class="bouton" value="Déconnexion" onclick="linkopener('deconnexion.php')" />
    </nav>
    <p class="para">Cher abonné Blob, vous pouvez rechercher dès à présent des personnes en tapant le pseudo sur la barre de recherche, visualiser le profil complet, envoyer un mesage à quelqu'un, et bloquer une personne !</p>

    <div class="conteneur">
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
            xhttp.open("GET", "recherche.php?q=" + str + "&filtre=" + filtre + "&limit=true", true);
            xhttp.send();
        }
    </script>

    <div class="contenu">
        
<p>Les trois derniers profils inscrits sur Blob :</p><br>
<ul id="utilisateurs">
            <?php foreach ($derniers_utilisateurs as $utilisateur) : ?>
                <li><?php echo htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']); ?></li>
            <?php endforeach; ?>
            </ul>
            <div class="bouton3">
        <input type="button" class="bouton" value="Liste des bloqués" onclick="linkopener('liste_bloque.php')" />
        <input type="button" class="bouton" value="Vues de mon profil" onclick="linkopener('liste_vues.php')" />
        <input type="button" class="bouton" value="Extension du statut" onclick="linkopener('extension_statut.php')" />
        </div>
    <script src="script.js" type="text/javascript"></script>
</body>

</html>
