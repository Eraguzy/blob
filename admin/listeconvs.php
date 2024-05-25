<?php
session_start();
if (!isset($_SESSION['statut']) || $_SESSION['statut'] != 'admin'){ // tej si c'est pas un admin
    header("Location: ../php/accueil.php");
    exit();
}

// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if (!isset($_GET['id_cible'])) {
        header("Location: ../php/accueil.php");
        exit;
    }
} else {
    header("Location: ../php/page_connexion.php");
    exit;
}

$id_utilisateur = $_GET['id_cible'];

$fichier = "../database/compte.json";
if (file_exists($fichier)) {
    $json_contenue = file_get_contents($fichier);
    $data = json_decode($json_contenue, true);
} else {
    $data = ["discussions" => [], "profils" => []];
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/listeconvs.css">
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
</head>

<body>
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Messages</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('../php/abonne.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <div class="conteneur">
                <?php   
                    foreach ($data['discussions'] as $discussion){ // parcourt toute la bdd à la recherche de toutes les conv impliquant l'id sélectionné
                        if ($discussion['id_utilisateur1'] == $id_utilisateur){



                            // à compléter
                        }
                        if ($discussion['id_utilisateur2'] == $id_utilisateur){



                            // à compléter
                        }
                        echo "<p onclick=\"linkopener('../php/page_discussion.php?id_cible=" . $id_utilisateur . "&id_main=" .time(). "')\" class='lienversdiscu'>test</p>";
                    }
                ?>
            </div>
        </div>
    </div>

    <script src="../scripts/script.js" type="text/javascript"></script>
</body>

</html>
