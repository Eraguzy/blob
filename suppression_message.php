<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if (!isset($_GET["id_utilisateur"]) || !isset($_GET["id_destinataire"]) || !isset($_GET["id_message"])) {
        header("Location: accueil.php");
        exit;
    }
} else {
    header("Location: page_connexion.php");
    exit;
}

$id_utilisateur = $_GET['id_utilisateur'];
$id_destinataire = $_GET['id_destinataire'];
$indexMessage = $_GET['id_message'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_connexion.css">
    <link rel="icon" href="logo.png">
    <title>Blob</title>
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Suppression</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <?php
            //Chemin de la base de donnée
            $fichier = 'compte.json';
            // Charger le contenu du fichier JSON
            $json_content = file_get_contents($fichier);

            // Décoder les données JSON en tableau PHP
            $data = json_decode($json_content, true);

            //Le message est à l'index+1
            $indexMessage2 = $_GET['id_message'] + 1;

            foreach ($data['discussions'] as &$discussion) {
                if (($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) || ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)) {
                    // Vérifier si l'index du message est valide
                    if ($indexMessage2 >= 0 && $indexMessage2 < count($discussion['messages'])) {
                        echo '<div class="message-erreur">' . $discussion['messages'][$indexMessage2] . '</div>';
                    }
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $suppression = false;
                // Fonction pour supprimer un message
                foreach ($data['discussions'] as &$discussion) {
                    if (($discussion['id_utilisateur1'] == $id_utilisateur && $discussion['id_utilisateur2'] == $id_destinataire) || ($discussion['id_utilisateur2'] == $id_utilisateur && $discussion['id_utilisateur1'] == $id_destinataire)) {
                        // Vérifier si l'index du message est valide
                        if ($indexMessage >= 0 && $indexMessage < count($discussion['messages'])) {
                            // Supprimer le message à l'index donné
                            array_splice($discussion['messages'], $indexMessage, 2);
                            $suppression = true; // Message supprimé avec succès
                        }
                        break;
                    }
                }

                if ($suppression) {
                    // Encoder les données mises à jour en JSON
                    $newJsonData = json_encode($data, JSON_PRETTY_PRINT);

                    // Sauvegarder les données mises à jour dans le fichier JSON
                    file_put_contents($fichier, $newJsonData);

                    echo '<div class="message-erreur">Message supprimé avec succès.</div>';
                } else {
                    echo '<div class="message-erreur">Erreur lors de la suppression du message.</div>';
                }
            }
            ?>
            <form name="suppression" method="post" action="">
                <input type="hidden" name="id_utilisateur" value="<?php echo $id_utilisateur; ?>">
                <input type="hidden" name="id_destinataire" value="<?php echo $id_destinataire; ?>">
                <input type="hidden" name="id_message" value="<?php echo $indexMessage; ?>">
                <input type="submit" value="Supprimer" />
            </form>

            <input type="submit" value="Retour à la discussion" onclick="linkopener('page_discussion.php?id_cible=<?php echo $id_destinataire; ?>')" />

        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>