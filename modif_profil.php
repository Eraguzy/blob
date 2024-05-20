<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    if ($_COOKIE['creation_profil'] == 0) {
        header("Location: creation_profil.php");
        exit;
    }
    $id_utilisateur = $_COOKIE['user_id'];
} else {
    header("Location: page_connexion.php");
    exit;
}

session_start();

$fichier = "compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

if (!isset($_SESSION['nom'])) {
    foreach ($data['profils'] as $profil) {
        if ($profil['id'] == $id_utilisateur) {
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

function changement_info($nom, $information)
{
    $nom2 = ucfirst($nom);
    echo "<div class='donnees'>
            $nom2 : $information
            </br>
            <input class='entree' type='text' name='$nom' value='{$information}' placeholder=$information>
          </div>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/modif_profil.css">
    <link rel="icon" href="logo.png">
    <title>Blob</title>
    <script>
        function linkopener(a) {
            window.open(a, '_self');
        }
    </script>
</head>

<nav class="bandeau">
    <img src="logo.png" class="img">
    <div class="bandeautitle">BLOB</div>
    <div class="titrebandeau">Mon Profil</div>
    <input type="button" class="bouton" value="Accueil" onclick="linkopener('accueil.php')" />
</nav>

<div class="Connexion-page">
    <div class="Connexion-boite">
        <img id="profil" src="photo_profil_utilisateurs/<?php echo $id_utilisateur; ?>.jpg" alt="Photo de profil" onclick="linkopener('changement_image.php')">
        <form method="post" action="modif_profil.php" enctype="multipart/form-data">
            <?php
            changement_info("nom", $_SESSION['nom']);
            changement_info("prenom", $_SESSION['prenom']);
            changement_info("pseudo", $_SESSION['pseudo']);
            changement_info("adresse", $_SESSION['adresse']);
            changement_info("ville", $_SESSION['ville']);
            changement_info("pays", $_SESSION['pays']);
            ?>
            <div class="donnees">
                <label for="date">Date de naissance :</label>
                <input type="date" name="date" class="date" placeholder="Date" required min="1900-01-01"
                    max="<?php echo date('Y-m-d'); ?>" value=<?php echo $_SESSION['date'] ?>>
            </div>
            <div class="donnees">
                <label for="genre">Sexe :</label>
                <label for="situation">Situation familiale :</label>
                <label for="couleur_des_cheveux">Couleur des cheveux :</label>
                <label for="couleur_des_yeux">Couleur des yeux :</label>
            </div>
            <div class="donnees">
                <select name="genre" id="genre" value=<?php echo $_SESSION['genre'] ?> required>
                    <option value="femme">Femme</option>
                    <option value="homme">Homme</option>
                    <option value="autre">Autre</option>
                </select>
                <select name="situation" id="situation" value=<?php echo $_SESSION['situation'] ?> required>
                    <option value="celib">Célibataire</option>
                    <option value="en couple">En couple</option>
                    <option value="marié(e)">Marié(e)</option>
                    <option value="Veuf(ve)">Veuf(ve)</option>
                    <option value="autre">Autre</option>
                </select>
                <select name="couleur_des_cheveux" id="couleur_des_cheveux" value=<?php echo $_SESSION['couleur_des_cheveux'] ?> required>
                    <option value="brun">Brun</option>
                    <option value="blond">Blond</option>
                    <option value="roux">Roux</option>
                    <option value="noir">Noir</option>
                    <option value="autre">Autre</option>
                </select>
                <select name="couleur_des_yeux" id="couleur_des_yeux" value=<?php echo $_SESSION['couleur_des_yeux'] ?>
                    required>
                    <option value="bleu">Bleu</option>
                    <option value="vert">Vert</option>
                    <option value="marron">Marron</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            
            <div class="donnees">
            <label for="statut">Offre d'abonnement :</label>
            </div>
            <div class="donnees">
            <select name="statut" id="statut" value=<?php echo $_SESSION['statut'] ?> required>
                    <option value="decouverte">Découverte</option>
                    <option value="Classique">Classique</option>
                    <option value="VIP">VIP</option>
                    <option value="utilisateur">Utilisateur (sans offre)</option>
                </select>
            </div>
            <div class="donnees">
                <label for="poids">Poids :</label>
                <input type="number" id="poids" name="poids" placeholder="Poids" value=<?php echo $_SESSION['poids'] ?>
                    required min="30" max="250">
            </div>
            <div class="donnees">
                <label for="taille">Taille :</label>
                <input type="number" id="taille" name="taille" placeholder="Taille" value=<?php echo $_SESSION['taille'] ?> required min="100" max="250">
            </div>
            <input type="submit" value="Enregistrer" />
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $fichier = "compte.json";
                $json_contenue = file_get_contents($fichier);
                $data = json_decode($json_contenue, true);
                function mise_a_jour($nom)
                {
                    if (isset($_POST[$nom]) && !empty($_POST[$nom])) {
                        $_SESSION[$nom] = $_POST[$nom];
                    }
                }

                mise_a_jour('nom');
                mise_a_jour('prenom');
                mise_a_jour('date');
                mise_a_jour('genre');
                mise_a_jour('pseudo');
                mise_a_jour('situation');
                mise_a_jour('adresse');
                mise_a_jour('ville');
                mise_a_jour('pays');
                mise_a_jour('couleur_des_cheveux');
                mise_a_jour('couleur_des_yeux');
                mise_a_jour('taille');
                mise_a_jour('poids');
                mise_a_jour('statut');

                foreach ($data['profils'] as &$profil) {
                    if ($profil['id'] == $id_utilisateur) {
                        $profil['nom'] = $_SESSION['nom'];
                        $profil['prenom'] = $_SESSION['prenom'];
                        $profil['date'] = $_SESSION['date'];
                        $profil['genre'] = $_SESSION['genre'];
                        $profil['pseudo'] = $_SESSION['pseudo'];
                        $profil['situation'] = $_SESSION['situation'];
                        $profil['adresse'] = $_SESSION['adresse'];
                        $profil['ville'] = $_SESSION['ville'];
                        $profil['pays'] = $_SESSION['pays'];
                        $profil['couleur_des_cheveux'] = $_SESSION['couleur_des_cheveux'];
                        $profil['couleur_des_yeux'] = $_SESSION['couleur_des_yeux'];
                        $profil['taille'] = $_SESSION['taille'];
                        $profil['poids'] = $_SESSION['poids'];
                        $profil['statut'] = $_SESSION['statut'];
                        break;
                    }
                }

                $json_modifie = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents($fichier, $json_modifie);
                exit;
            }
            ?>       
        </form>
    </div>
</div>

</body>

</html>
