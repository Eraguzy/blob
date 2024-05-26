<?php
//On démarre la session
session_start();

//Si l'utilisateur a un statut et est un admin on récupère son id et on le redirige vers la page de profil de l'utilisateur qu'il souhaite modifier
if (isset($_SESSION['statut']) && $_SESSION['statut'] == 'admin') {
    $id_utilisateur = $_GET['id_utilisateur'];
    header("Location: ../admin/modif_profil_admin.php?id_utilisateur=" . urlencode($id_utilisateur)); //header peut pas directement inclure du code php
    exit;
}

//Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion
if (isset($_COOKIE['user_id'])) {
    //Si il n'a pas crée de profil on le redirige vers la page adéquate sinon on récupère son identifiant
    if ($_COOKIE['creation_profil'] == 0) {
        header("Location: ../php/creation_profil.php");
        exit;
    }
    $id_utilisateur = $_COOKIE['user_id'];
} else {
    header("Location: ../php/page_connexion.php");
    exit;
}


if (!isset($_SESSION['statut']) && $_SESSION['statut'] == 'admin') {
    // si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: ../accueil.php");
    exit();
}

//On récupère les données de la base de données
$fichier = "../database/compte.json";
$json_content = file_get_contents($fichier);
$data = json_decode($json_content, true);

//On regarde si l'utilisateur a une session active en vérifiant si la variable 'nom' de la session existe
if (!isset($_SESSION['nom'])) {
    //Sinon on cherche dans la base de données le profil de l'utilisateur
    foreach ($data['profils'] as $profil) {
        if ($profil['id'] == $id_utilisateur) {
            //Et on récupère ses informations qu'on donne à des variables
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

//Pour éviter les répétitions
//On donne les infos du profil actuellement et on a un espace pour écrire si l'utilisateur souhaite changer ses informations avec les informations en mémoire pré-remplies
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
    <!-- CSS, icône, titre de page -->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/modif_profil.css">
    <link rel="icon" href="../images/logo.png">
    <title>Blob</title>
    <script>
        function linkopener(a) {
            window.open(a, '_self');
        }
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
    <!-- Bandeau de page avec le bouton de redirection pour l'accueil-->
    <nav class="bandeau">
        <img src="../images/logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Mon Profil</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('accueil.php')" />
    </nav>

    <!-- Affichage des informations en mémoire et emplacements pour les changer -->
    <div class="Connexion-page">
        <div class="Connexion-boite">
            <!-- L'image de profil est cliquable si on clique on est redirigé vers une page pour la modifier -->
            <img id="profil" src="../photo_profil_utilisateurs/<?php echo $id_utilisateur; ?>.jpg?<?php echo time(); ?>" alt="Photo de profil" onclick="linkopener('changement_image.php')">
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
                    <input type="date" name="date" class="date" placeholder="Date" required min="1900-01-01" max="<?php echo date('Y-m-d'); ?>" value=<?php echo $_SESSION['date'] ?>>
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
                    <select name="couleur_des_yeux" id="couleur_des_yeux" value=<?php echo $_SESSION['couleur_des_yeux'] ?> required>
                        <option value="bleu">Bleu</option>
                        <option value="vert">Vert</option>
                        <option value="marron">Marron</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="donnees">
                    <label for="poids">Poids :</label>
                    <input type="number" id="poids" name="poids" placeholder="Poids" value=<?php echo $_SESSION['poids'] ?> required min="30" max="250">
                </div>
                <div class="donnees">
                    <label for="taille">Taille :</label>
                    <input type="number" id="taille" name="taille" placeholder="Taille" value=<?php echo $_SESSION['taille'] ?> required min="100" max="250">
                </div>
                <input type="submit" value="Enregistrer" />
                <?php
                //On attend l'envoi du formulaire avant d'éxécuter le code
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    //On récupère les informations de la base de données json
                    $fichier = "../database/compte.json";
                    $json_contenue = file_get_contents($fichier);
                    $data = json_decode($json_contenue, true);
                    //On vérifie si les champs du formulaire existent et sont remplies si oui on met à jour les variables de session
                    function mise_a_jour($nom)
                    {
                        if (isset($_POST[$nom]) && !empty($_POST[$nom])) {
                            $_SESSION[$nom] = $_POST[$nom];
                        }
                    }

                    //On réitère l'opération pour toutes les informations
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

                    //On cherche le profil de l'utilisateur dans la base de donnée pour mettre à jour ses informations avec celles nouvellement saisies
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

                    //On met à jour la base de donnée
                    $json_modifie = json_encode($data, JSON_PRETTY_PRINT);
                    file_put_contents($fichier, $json_modifie);
                    //On rafraichit la page pour voir les nouvelles informations
                    echo "<meta http-equiv='refresh' content='0'>";
                    exit;
                }
                ?>
            </form>
        </div>
    </div>
</body>

</html>