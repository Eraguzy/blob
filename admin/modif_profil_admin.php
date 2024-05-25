<?php
// Vérification si le cookie existe
session_start();
if (!isset($_SESSION['statut']) || $_SESSION['statut'] != 'admin'){
    // si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: ../php/accueil.php");
    exit();
}

$id_utilisateur = $_GET['id_utilisateur']; // appel du code depuis modif profil classique (redirection admin)

if($id_utilisateur == ""){ // cas où l'admin souhaiterait modifier son propre profil : alors on met son identifiant si l'appel n'a pas d'identifiant
    if (isset($_COOKIE['user_id'])){
        if ($_COOKIE['creation_profil'] == 0) {
            header("Location: ../php/creation_profil.php");
            exit;
        }
        $id_utilisateur = $_COOKIE['user_id'];
    } else {
        header("Location: ../php/page_connexion.php");
        exit;
    }
}

$fichier = "../database/compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

// data du profil visionné
global $profilutilisateur;
$profilutilisateur = [
    'nom' => "",
    'prenom' => "",
    'date' => "",
    'genre' => "",
    'pseudo' => "",
    'situation' => "",
    'adresse' => "",
    'ville' => "",
    'pays' => "",
    'couleur_des_yeux' => "",
    'couleur_des_cheveux' => "",
    'taille' => "",
    'poids' => "",
    'statut' => "",
];

foreach ($data['profils'] as &$profil){ // parcours base de données de profil
    if ($profil['id'] == $id_utilisateur){ // profil trouvé ?
        $profilutilisateur['nom'] = $profil['nom'];
        $profilutilisateur['prenom'] = $profil['prenom'];
        $profilutilisateur['date'] = $profil['date'];
        $profilutilisateur['genre'] = $profil['genre'];
        $profilutilisateur['pseudo'] = $profil['pseudo'];
        $profilutilisateur['situation'] = $profil['situation'];
        $profilutilisateur['adresse'] = $profil['adresse'];
        $profilutilisateur['ville'] = $profil['ville'];
        $profilutilisateur['pays'] = $profil['pays'];
        $profilutilisateur['couleur_des_yeux'] = $profil['couleur_des_yeux'];
        $profilutilisateur['couleur_des_cheveux'] = $profil['couleur_des_cheveux'];
        $profilutilisateur['taille'] = $profil['taille'];
        $profilutilisateur['poids'] = $profil['poids'];
        $profilutilisateur['statut'] = $profil['statut'];
        break;
    }
}


function changement_info($nom, $information){
    $nom2 = ucfirst($nom);
    echo "<div class='donnees'>
            $nom2 : $information
            </br>
            <input class='entree' type='text' name='$nom' value='{$information}'>
          </div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fichier = "../database/compte.json";
    $json_contenue = file_get_contents($fichier);
    $data = json_decode($json_contenue, true);

    function mise_a_jour($nom, &$profilutilisateur){
                        
        global $id_utilisateur; // Utilisation de la variable $id_utilisateur dans la fonction
        if (isset($_POST[$nom]) && !empty($_POST[$nom])) {
            $profilutilisateur[$nom] = $_POST[$nom];
            
            // Mise à jour des données dans le tableau $data
            $fichier = "../database/compte.json";
            $json_contenue = file_get_contents($fichier);
            $data = json_decode($json_contenue, true);

            foreach ($data['profils'] as &$profil){
                if ($profil['id'] == $id_utilisateur){
                    $profil[$nom] = $_POST[$nom];
                    break;
                }
            }

            $json_modifie = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($fichier, $json_modifie);
        }
    }

    mise_a_jour('nom', $profilutilisateur); // modifie la variable profilutilisateurs[]
    mise_a_jour('prenom', $profilutilisateur);
    mise_a_jour('date', $profilutilisateur);
    mise_a_jour('genre', $profilutilisateur);
    mise_a_jour('pseudo', $profilutilisateur);
    mise_a_jour('situation', $profilutilisateur);
    mise_a_jour('adresse', $profilutilisateur);
    mise_a_jour('ville', $profilutilisateur);
    mise_a_jour('pays', $profilutilisateur);
    mise_a_jour('couleur_des_cheveux', $profilutilisateur);
    mise_a_jour('couleur_des_yeux', $profilutilisateur);
    mise_a_jour('taille', $profilutilisateur);
    mise_a_jour('poids', $profilutilisateur);
    mise_a_jour('statut', $profilutilisateur);

    foreach ($data['profils'] as &$profil){ // modification de la base de données
        if ($profil['id'] == $id_utilisateur){
            $profil['nom'] = $profilutilisateur['nom'];
            $profil['prenom'] = $profilutilisateur['prenom'];
            $profil['date'] = $profilutilisateur['date'];
            $profil['genre'] = $profilutilisateur['genre'];
            $profil['pseudo'] = $profilutilisateur['pseudo'];
            $profil['situation'] = $profilutilisateur['situation'];
            $profil['adresse'] = $profilutilisateur['adresse'];
            $profil['ville'] = $profilutilisateur['ville'];
            $profil['pays'] = $profilutilisateur['pays'];
            $profil['couleur_des_cheveux'] = $profilutilisateur['couleur_des_cheveux'];
            $profil['couleur_des_yeux'] = $profilutilisateur['couleur_des_yeux'];
            $profil['taille'] = $profilutilisateur['taille'];
            $profil['poids'] = $profilutilisateur['poids'];
            $profil['statut'] = $profilutilisateur['statut'];
            break;
        }
    }

    $json_modifie = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($fichier, $json_modifie);

    header("Location: adminmenu.php");
}
?>



<!DOCTYPE html>
<html>

<head>
    <script src="../scripts/script.js" type="text/javascript"></script> <!--l'inclusion est mise au début car sinon les inkopener ne focntionnent pas-->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/modif_profil.css"> <!-- on garde le même affichage que la modif profil classique -->
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
</head>

<nav class="bandeau">
    <img src="../images/logo.png" class="img">
    <div class="bandeautitle">BLOB</div>
    <div class="titrebandeau">Modifier un profil (admin)</div>
    <input type="button" class="bouton" value="Accueil" onclick="linkopener('../accueil.php')" />
</nav>

<div class="Connexion-page">
    <div class="Connexion-boite">
        <img id="profil" src="../photo_profil_utilisateurs/<?php echo $id_utilisateur; ?>.jpg" alt="Photo de profil" onclick="linkopener('../changement_image.php?id_utilisateur=<?php echo $id_utilisateur; ?>')">
        <form method="post" action="../php/modif_profil_admin.php?id_utilisateur=<?php echo urlencode($id_utilisateur); ?>" enctype="multipart/form-data">
            <?php
            changement_info("nom", $profilutilisateur['nom']);
            changement_info("prenom", $profilutilisateur['prenom']);
            changement_info("pseudo", $profilutilisateur['pseudo']);
            changement_info("adresse", $profilutilisateur['adresse']);
            changement_info("ville", $profilutilisateur['ville']);
            changement_info("pays", $profilutilisateur['pays']);
            ?>
            <div class="donnees">
                <label for="date">Date de naissance :</label>
                <input type="date" name="date" class="date" placeholder="Date" required min="1900-01-01"
                    max="<?php echo date('Y-m-d'); ?>" value=<?php echo $profilutilisateur['date'] ?>>
            </div>
            <div class="donnees">
                <label for="genre">Sexe :</label>
                <label for="situation">Situation familiale :</label>
                <label for="couleur_des_cheveux">Couleur des cheveux :</label>
                <label for="couleur_des_yeux">Couleur des yeux :</label>
            </div>
            <div class="donnees">
                <select name="genre" id="genre" required>
                    <option value="femme" <?php echo $profilutilisateur['genre'] == 'femme' ? 'selected' : ''; ?>>Femme</option>
                    <option value="homme" <?php echo $profilutilisateur['genre'] == 'homme' ? 'selected' : ''; ?>>Homme</option>
                    <option value="autre" <?php echo $profilutilisateur['genre'] == 'autre' ? 'selected' : ''; ?>>Autre</option>
                </select>
                <select name="situation" id="situation" required>
                    <option value="celib" <?php echo $profilutilisateur['situation'] == 'celib' ? 'selected' : ''; ?>>Célibataire</option>
                    <option value="en couple" <?php echo $profilutilisateur['situation'] == 'en couple' ? 'selected' : ''; ?>>En couple</option>
                    <option value="marié(e)" <?php echo $profilutilisateur['situation'] == 'marié(e)' ? 'selected' : ''; ?>>Marié(e)</option>
                    <option value="Veuf(ve)" <?php echo $profilutilisateur['situation'] == 'Veuf(ve)' ? 'selected' : ''; ?>>Veuf(ve)</option>
                    <option value="autre" <?php echo $profilutilisateur['situation'] == 'autre' ? 'selected' : ''; ?>>Autre</option>
                </select>
                <select name="couleur_des_cheveux" id="couleur_des_cheveux" required>
                    <option value="brun" <?php echo $profilutilisateur['couleur_des_cheveux'] == 'brun' ? 'selected' : ''; ?>>Brun</option>
                    <option value="blond" <?php echo $profilutilisateur['couleur_des_cheveux'] == 'blond' ? 'selected' : ''; ?>>Blond</option>
                    <option value="roux" <?php echo $profilutilisateur['couleur_des_cheveux'] == 'roux' ? 'selected' : ''; ?>>Roux</option>
                    <option value="noir" <?php echo $profilutilisateur['couleur_des_cheveux'] == 'noir' ? 'selected' : ''; ?>>Noir</option>
                    <option value="autre" <?php echo $profilutilisateur['couleur_des_cheveux'] == 'autre' ? 'selected' : ''; ?>>Autre</option>
                </select>
                <select name="couleur_des_yeux" id="couleur_des_yeux" required>
                    <option value="bleu" <?php echo $profilutilisateur['couleur_des_yeux'] == 'bleu' ? 'selected' : ''; ?>>Bleu</option>
                    <option value="vert" <?php echo $profilutilisateur['couleur_des_yeux'] == 'vert' ? 'selected' : ''; ?>>Vert</option>
                    <option value="marron" <?php echo $profilutilisateur['couleur_des_yeux'] == 'marron' ? 'selected' : ''; ?>>Marron</option>
                    <option value="autre" <?php echo $profilutilisateur['couleur_des_yeux'] == 'autre' ? 'selected' : ''; ?>>Autre</option>
                </select>
            </div>
            
           <div class="donnees">
            <label for="statut">Offre d'abonnement :</label>
            </div>
            <div class="donnees">
            <select name="statut" id="statut" value=<?php echo $profilutilisateur['statut'] ?> required>
                    <option value="decouverte" <?php echo $profilutilisateur['statut'] == 'decouverte' ? 'selected' : ''; ?>>Découverte</option>
                    <option value="classique" <?php echo $profilutilisateur['statut'] == 'classique' ? 'selected' : ''; ?>>Classique</option>
                    <option value="VIP" <?php echo $profilutilisateur['statut'] == 'VIP' ? 'selected' : ''; ?>>VIP</option>
                    <option value="Utilisateur" <?php echo $profilutilisateur['statut'] == 'Utilisateur' ? 'selected' : ''; ?>>Utilisateur (sans offre)</option>
                    <option value="admin" <?php echo $profilutilisateur['statut'] == 'admin' ? 'selected' : ''; ?>>admin</option>
                </select>
            </div>
            <div class="donnees">
                <label for="poids">Poids :</label>
                <input type="number" id="poids" name="poids" placeholder="Poids" value=<?php echo $profilutilisateur['poids'] ?>
                    required min="30" max="250">
            </div>
            <div class="donnees">
                <label for="taille">Taille :</label>
                <input type="number" id="taille" name="taille" placeholder="Taille" value=<?php echo $profilutilisateur['taille'] ?> required min="100" max="250">
            </div>
            <div class="conteneurflex">
                <input type='button' class='bouton' value='Envoyer un message' onclick="linkopener('../page_discussion.php?id_cible=<?php echo $id_utilisateur;?>')" />  
                <input type='button' class='bouton' value='Accéder aux conversations' onclick="linkopener('listeconvs.php?id_cible=<?php echo $id_utilisateur;?>')" />  
            </div>
            <br>
            <input type="submit" value="Enregistrer et retourner à l'interface admin" />     
        </form>
    </div>
</div>

 </body>

</html>
