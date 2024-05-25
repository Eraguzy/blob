<?php
session_start();
if (!isset($_SESSION['statut']) || $_SESSION['statut'] != 'admin'){ // tej si c'est pas un admin
    header("Location: ../accueil.php");
    exit();
}

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
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/listeconvs.css">
    <link rel="icon" href="../logo.png">
    <title>Blob</title>
</head>

<body>
    <nav class="bandeau">
        <img src="../logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Messages</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('../abonne.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            
        </div>
    </div>

    <script src="../script.js" type="text/javascript"></script>
</body>

</html>
