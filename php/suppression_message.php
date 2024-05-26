<?php
//Si l'utilisateur n'est pas connecté il est redirigé vers la page de connexion
if (isset($_COOKIE['user_id'])) {
    //On vérifie qu'on a en paramètres l'id de l'utilisateur, du destinataire et du message sinon on redirige vers la page d'accueil
    if (!isset($_GET["id_utilisateur"]) || !isset($_GET["id_destinataire"]) || !isset($_GET["id_message"])) {
        header("Location: accueil.php");
        exit;
    }
} else {
    header("Location: ../php/page_connexion.php");
    exit;
}

//On assigne les paramètres à des variables
$id_utilisateur = $_GET['id_utilisateur'];
$id_destinataire = $_GET['id_destinataire'];
$indexMessage = $_GET['id_message'];
?>

<!DOCTYPE html>
<html>

<head>
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/page_connexion.css">
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
</head>

<body>
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Suppression</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <?php
            //Chemin de la base de donnée
            $fichier = '../database/compte.json';
            //On charge le contenu du fichier JSON
            $json_content = file_get_contents($fichier);

            //On décode les données JSON en tableau PHP
            $data = json_decode($json_content, true);

            //Le message est à l'index+1
            $indexMessage2 = $_GET['id_message'] + 1;

            //On cherche la discussion du message à supprimer
            foreach ($data['discussions'] as &$discussion) {
                if (($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) || ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)) {
                    //On vérifie si l'index du message est valide
                    if ($indexMessage2 >= 0 && $indexMessage2 < count($discussion['messages'])) {
                        //Si le message est trouvé on l'affiche
                        echo '<div class="message-erreur">' . $discussion['messages'][$indexMessage2] . '</div>';
                    }
                }
            }

            //Lors de la soumission du formulaire
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $suppression = false;
                //Fonction pour supprimer un message
                //On cherche la discussion du message à supprimer
                foreach ($data['discussions'] as &$discussion) {
                    if (($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) || ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)) {
                        //On vérifie si l'index du message est valide
                        if ($indexMessage >= 0 && $indexMessage < count($discussion['messages'])) {
                            //On supprime le message une fois trouvé
                            array_splice($discussion['messages'], $indexMessage, 2);
                            $suppression = true; //Message supprimé avec succès
                        }
                        break;
                    }
                }

                //Si la suppression c'est fait avec succès
                if ($suppression) {
                    //On encode les données mises à jour en JSON
                    $newJsonData = json_encode($data, JSON_PRETTY_PRINT);

                    //On sauvegarde les données mises à jour dans le fichier JSON
                    file_put_contents($fichier, $newJsonData);

                    //Message de succès
                    echo '<div class="message-erreur">Message supprimé avec succès.</div>';
                } else {
                    //Sinon message d'erreur
                    echo '<div class="message-erreur">Erreur lors de la suppression du message.</div>';
                }
            }
            ?>

            <!-- On passe l'id message au formulaire et le bouton de suppression -->
            <form name="suppression" method="post" action="">
                <input type="hidden" name="id_message" value="<?php echo $indexMessage; ?>">
                <input type="submit" value="Supprimer" />
            </form>

            <!-- Redirection vers la page de discussion -->
            <input type="submit" value="Retour à la discussion" onclick="linkopener('page_discussion.php?id_cible=<?php echo $id_destinataire; ?>&id_main=<?php echo $id_utilisateur; ?>')" />

        </div>
    </div>

    <!-- Le script pour les boutons de redirectino vers les pages annexes -->
    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>