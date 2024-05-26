<?php
//Si l'utilisateur n'est pas connecté il est redirigé vers la page de connexion
if (isset($_COOKIE['user_id'])) {
    //On vérifie si on a un paramètre avec l'id de l'utilisateur à qui on souhaite envoyer un message sinon on le renvoie vers la page d'accueil
    if (!isset($_GET['id_cible'])) {
        header("Location: accueil.php");
        exit;
    }
} else {
    header("Location: page_connexion.php");
    exit;
}

//On prend l'id cible qu'on donne à une variable
$id_destinataire = $_GET['id_cible'];

if(isset($GET['user_id']) && $_GET['id_main'] != ""){ // vérifie si on a appelé avec un deuxième id pour éviter message d'erreur
    $id_utilisateur = $_GET['id_main'];
}
else{
    $id_utilisateur = $_COOKIE['user_id'];
}
//ici on a bien défini un destinataire et un sender pour tous les cas (soit il y a un id_main dans GET et l'appel a été fait depuis listeconvs.php, soit ça vient de la page de profil classique et y'a pas de id_main)

//On récupère les données de la base de donnée si elle existe pas on l'a crée
$fichier = "../database/compte.json";
if (file_exists($fichier)) {
    $json_contenue = file_get_contents($fichier);
    $data = json_decode($json_contenue, true);
} else {
    $data = ["discussions" => [], "profils" => []];
}
//verifier si l'utilisateur nous a bloqué, si oui redirection vers une page de message d'erreur
foreach ($data['profils'] as $profil) {
    if ($profil['id'] == $id_destinataire) {
        if (isset($profil['utilisateurs_bloques']) && in_array($id_utilisateur, $profil['utilisateurs_bloques'])) {
            header("Location: message_bloque.php");
            exit;
        }
        break;
    }
}
//On démarre la session pour avoir accès aux variables de session
session_start();
//On regarde si l'utilisateur a une session avec des variables de profil définies en vérifiant si la variable 'nom' de la session existe
if (!isset($_SESSION['nom'])) {
    //Sinon on cherche dans la base de données le profil de l'utilisateur
    foreach ($data['profils'] as $profil) {
        if ($profil['id'] == $id_utilisateur) {
            //Et on récupère ses informations qu'on donne à des variables
            $_SESSION['nom'] = $profil['nom'];
            $_SESSION['prenom'] = $profil['prenom'];
            $_SESSION['date'] = $profil['date'];
            $_SESSION['genre'] = $profil['genre'];
            $_SESSION['pseudo'] = $profil['pseudo'];
            $_SESSION['situation'] = $profil['situation'];
            $_SESSION['adresse'] = $profil['adresse'];
            $_SESSION['ville'] = $profil['ville'];
            $_SESSION['pays'] = $profil['pays'];
            $_SESSION['couleur_des_yeux'] = $profil['couleur_des_yeux'];
            $_SESSION['couleur_des_cheveux'] = $profil['couleur_des_cheveux'];
            $_SESSION['taille'] = $profil['taille'];
            $_SESSION['poids'] = $profil['poids'];
            $_SESSION['statut'] = $profil['statut'];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/page_discussion.css">
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
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
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Messages</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('abonne.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <div class="Messages-boite">
                <?php
                $fichier = "../database/compte.json";
                //On vérifie que la base de données existe et qu'elle contient la liste 'discussions', on crée le fichier et/ou la liste suivant les besoins
                if (file_exists($fichier)) {
                    $json_contenue = file_get_contents($fichier);
                    $data = json_decode($json_contenue, true);
                    if (!isset($data['discussions'])) {
                        $data['discussions'] = [];
                    }
                } else {
                    $data = ["discussions" => []];
                }

                $count = 0;

                //On affiche les messages de la discussion entre l'utilisateur et l'utilisateur ciblé
                foreach ($data['discussions'] as $discussion) {
                    if (
                        ($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) ||
                        ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)
                    ) {
                        foreach ($discussion['messages'] as $message) {
                            if ($count % 2 == 0) {
                                echo "<a id=\"res\" href=\"suppression_message.php?id_utilisateur=$id_utilisateur&id_destinataire=$id_destinataire&id_message=$count\"> $message : ";
                            } else {
                                echo "$message</a></br>";
                            }
                            $count++;
                        }
                    }
                }
                ?>
            </div>
            <!-- Espace pour écrire un nouveau message -->
            <form action="#" method="post" class="recherche">
                <input type="text" name="message" id="recherche" placeholder="Envoyer un message" required>
                <button type="submit">Envoyer</button>
            </form>
            <?php
            //Après soumission du formulaire
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                //On récupère le message saisi
                $nouveau_message = $_POST["message"];
                $discussion_existe = false;

                //On recherche la discussion et si elle existe on ajoute le pseudo de l'utilisateur et le message envoyé
                foreach ($data['discussions'] as &$discussion) {
                    if (($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) || ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)) {
                        $discussion['messages'][] = $_SESSION['pseudo'];
                        $discussion['messages'][] = $nouveau_message;
                        $discussion_existe = true;
                        break;
                    }
                }

                //Si la discussion n'existe pas on ajoute le pseudo de l'utilisateur et le message envoyé dans une structure 'nouvel_discussion' et on crée la discussion
                if (!$discussion_existe) {
                    $nouvel_discussion = [
                        'id_utilisateur1' => $id_utilisateur,
                        'id_utilisateur2' => $id_destinataire,
                        'messages' => [$_SESSION['pseudo'], $nouveau_message],
                    ];
                    $data['discussions'][] = $nouvel_discussion;
                }

                //Ensuite on met à jour la base de données avec le nouveau message ou la nouvelle discussion
                $json_modifie = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents($fichier, $json_modifie);

                //On rafraichit la page pour afficher le nouveau message
                header("Location: page_discussion.php?id_cible=$id_destinataire");
                exit;
            }
            ?>
        </div>
    </div>

    <!-- Le script pour les boutons de redirectino vers les pages annexes -->
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>
