<?php
$q = $_REQUEST["q"];
if(isset($_REQUEST["filtre"]) && $_REQUEST["filtre"] != ""){
    $filter = $_REQUEST["filtre"];
} else {
    $filter = "pseudo";
}

$res = "";
$data = json_decode(file_get_contents('compte.json'), true);
$limitResults = isset($_GET['limit']) ? $_GET['limit'] : false;

$count = 0;

if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach ($data['profils'] as $profil) {
        // Vérifier si le profil correspond au filtre sélectionné
        if (stristr($profil[$filter], $q)) {
            if (!$limitResults || ($limitResults && $count < 5)) {
                if ($res === "") {
                    $res = '<div class="profile" data-user-id="' . $profil['id'] . '">' . 
                           '<img src="photo_profil_utilisateurs/' . $profil['id'] . '.jpg" alt="Photo de profil">' . 
                           '<span>' . $profil['pseudo'] . '</span>' . 
                           '</div>';
                } else {
                    $res .= '<div class="profile" data-user-id="' . $profil['id'] . '">' . 
                            '<img src="photo_profil_utilisateurs/' . $profil['id'] . '.jpg" alt="Photo de profil">' . 
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

echo $res === "" ? "Pas de profil correspondant" : $res;
?>
