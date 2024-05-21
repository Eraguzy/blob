<?php
// Vérification si le cookie existe
if (!isset($_COOKIE['user_id'])) {
    header("Location: page_connexion.php");
    exit;
}

if (!isset($_GET['id_utilisateur'])) {
    header("Location: accueil.php");
    exit;
}

$id_utilisateur = $_GET['id_utilisateur'];
$fichier = "compte.json";

// Récupération du contenu du fichier JSON
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

// Récupération de l'ID de l'utilisateur actuel à partir du cookie
$current_user_id = $_COOKIE['user_id'];

// Vérification si l'utilisateur actuel existe dans le fichier JSON
if (isset($data['utilisateurs']) && isset($data['profils'])) {
    $current_user_profile = null;
    foreach ($data['profils'] as &$profil) {
        if ($profil['id'] === $current_user_id) {
            $current_user_profile = &$profil;
            break;
        }
    }

    if ($current_user_profile !== null) {
        // Suppression de l'utilisateur de la liste des utilisateurs bloqués
        if (in_array($id_utilisateur, $current_user_profile['utilisateurs_bloques'])) {
            $index = array_search($id_utilisateur, $current_user_profile['utilisateurs_bloques']);
            if ($index !== false) {
                unset($current_user_profile['utilisateurs_bloques'][$index]);
                // Réindexation du tableau après suppression
                $current_user_profile['utilisateurs_bloques'] = array_values($current_user_profile['utilisateurs_bloques']);
            }

            // Sauvegarde des modifications dans le fichier JSON
            file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT));

            echo "Utilisateur débloqué avec succès !";
        } else {
            echo "L'utilisateur n'est pas dans la liste des utilisateurs bloqués.";
        }
    } else {
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
    <link rel="stylesheet" type="text/css" href="styles/bloque.css">
    <link rel="icon" href="logo.png">
    <title>Débloquer cet utilisateur</title>
</head>
<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Débloquer cet utilisateur</div>
        <input type="button" class="bouton" value="Retour" onclick="linkopener('abonne.php')" />
    </nav>
    <h1>Utilisateur débloqué avec succès !</h1>
    <script>
    function linkopener(a) {
        window.open(a, '_self');
    }
    </script>
</body>
</html>