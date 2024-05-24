<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if (!isset($_GET['id_cible'])) {
        header("Location: accueil.php");
        exit;
    }
} else {
    header("Location: page_connexion.php");
    exit;
}

$id_destinataire = $_GET['id_cible'];
$id_utilisateur = $_COOKIE['user_id'];

$fichier = "compte.json";
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
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_discussion.css">
    <link rel="icon" href="logo.png">
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
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Messages</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('abonne.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <div class="Messages-boite">
                <?php
                $fichier = "compte.json";

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

                foreach ($data['discussions'] as $discussion) {
                    if (
                        ($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) ||
                        ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)
                    ) {
                        foreach ($discussion['messages'] as $message) {
                            if ($count % 2 == 0) {
                                echo "<a id=\"res\" href=\"suppression_message.php?id_utilisateur=$id_utilisateur&id_destinataire=$id_destinataire&id_message=$count\"> $message : ";
                            } else {
                                echo "$message</a>";
                            }
                            $count++;
                        }
                    }
                }
                ?>
            </div>
            <form action="#" method="post" class="recherche">
                <input type="text" name="message" id="recherche" placeholder="Envoyer un message" required>
                <button type="submit">Envoyer</button>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nouveau_message = $_POST["message"];
                $discussion_existe = false;

                foreach ($data['discussions'] as &$discussion) {
                    if (($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) || ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)) {
                        $discussion['messages'][] = $_SESSION['pseudo'];
                        $discussion['messages'][] = $nouveau_message;
                        $discussion_existe = true;
                        break;
                    }
                }

                if (!$discussion_existe) {
                    $nouvel_discussion = [
                        'id_utilisateur1' => $id_utilisateur,
                        'id_utilisateur2' => $id_destinataire,
                        'messages' => [$_SESSION['pseudo'], $nouveau_message],
                    ];
                    $data['discussions'][] = $nouvel_discussion;
                }

                file_put_contents($fichier, json_encode($data));

                header("Location: page_discussion.php?id_cible=$id_destinataire");
                exit;
            }
            ?>
        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>
