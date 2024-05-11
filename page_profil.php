<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    // Authentification automatique de l'utilisateur
    $user_id = $_COOKIE['user_id'];
    $utilisateur = explode(";", $user_id);
} else {
    // Redirection vers la page de connexion si le cookie n'est pas présent
    header("Location: page_connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/modif.css">
    <title>Blob</title>
    <link rel="icon" href="logo.png">
</head>
<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau" >Modification du profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('accueil.php')"/>
    </nav>

    <img src="<?php ?>.png">

    <script src="script.js" type="text/javascript"></script>
</body>
</html>
