<?php
//On récupère le paramètre q qui est la recherche
$q = $_REQUEST["q"];
//On regarde si il y a un filtre et on le récupère, le filtre par défaut est pseudo
if(isset($_REQUEST["filtre"]) && $_REQUEST["filtre"] != ""){
    $filter = $_REQUEST["filtre"];
} else {
    $filter = "pseudo";
}

//On récupère les données de la base de donnée et on regarde si on limite le nombre de résultats
$res = "";
$data = json_decode(file_get_contents('../database/compte.json'), true);
$limitResults = isset($_GET['limit']) ? $_GET['limit'] : false;

$count = 0;

//Si la barre de recherche est vide on ne fait rien
if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    //On regarde tout les profils
    foreach ($data['profils'] as $profil) {
        //On regarde si le profil correspond à la recherche effectué et si oui on l'affiche avec son pseudo et son image de profil renvoyant vers sa page de profil
        if (stristr($profil[$filter], $q)) {
            if (!$limitResults || ($limitResults && $count < 5)) {
                if ($res === "") {
                    $res = '<div class="profile" data-user-id="' . $profil['id'] . '">' . 
                           '<img src="../photo_profil_utilisateurs/' . $profil['id'] . '.jpg" alt="Photo de profil">' . 
                           '<span>' . $profil['pseudo'] . '</span>' . 
                           '</div>';
                } else {
                    $res .= '<div class="profile" data-user-id="' . $profil['id'] . '">' . 
                            '<img src="../photo_profil_utilisateurs/' . $profil['id'] . '.jpg" alt="Photo de profil">' . 
                            '<span>' . $profil['pseudo'] . '</span>' . 
                            '</div>';
                }
                $count++;
            } else {
                break;
            }
        }
    }
}

//Pas de profil trouvé
echo $res === "" ? "Pas de profil correspondant" : $res;
?>
