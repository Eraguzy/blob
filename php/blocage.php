<?php
// on vérifie si le cookie existe
if (!isset($_COOKIE['user_id'])) {
    header("Location: ../php/page_connexion.php");
    exit;
}
//renvoyer vers accueil.php si on a pas réussi à récup l'id avec get
if (!isset($_GET['id_utilisateur'])) {
    header("Location: accueil.php");
    exit;
}
//on récupère l'id de l'utilisateur cible avec la méthode get
$id_utilisateur = $_GET['id_utilisateur'];
$fichier = "../database/compte.json";

// récupération du contenu du fichier JSON
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

// récupération de l'ID de l'utilisateur actuel à partir du cookie
$current_user_id = $_COOKIE['user_id'];

// vérification si l'utilisateur actuel existe dans le fichier JSON
if (isset($data['utilisateurs']) && isset($data['profils'])) {
    $current_user_profile = null;
    foreach ($data['profils'] as &$profil) {
        if ($profil['id'] === $current_user_id) {
            $current_user_profile = &$profil;
            break;
        }
    }

    if ($current_user_profile !== null) {
        // ajout de l'utilisateur à la liste des utilisateurs bloqués
        if (!in_array($id_utilisateur, $current_user_profile['utilisateurs_bloques'])) {
            $current_user_profile['utilisateurs_bloques'][] = $id_utilisateur;

            // sauvegarde des modifications dans le fichier JSON
            file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT));
        }

        echo "Utilisateur bloqué avec succès !";
    } 
    else {
        echo "Utilisateur actuel non trouvé.";
    }
} else {
    echo "Données JSON invalides.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/abonne.css">
    <link rel="icon" href="../images/logo.png">
    <title>Bloquer cet utilisateur</title>
</head>
<body>
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Bloquer cet utilisateur</div>
        <input type="button" class="bouton" value="Retour" onclick="linkopener('abonne.php')" />
    </nav>
    <h1>Utilisateur bloqué avec succès !</h1>
    <script>
    function linkopener(a) {
        window.open(a, '_self');
    }
    </script>
</body>
</html>
