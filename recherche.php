<?php
$q = $_REQUEST["q"];
$res = "";
$data = json_decode(file_get_contents('compte.json'), true);
$limitResults = isset($_GET['limit']) ? $_GET['limit'] : false; // Vérifie si le paramètre 'limit' est défini

$count = 0;

if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach ($data['profils'] as $profil) {
        if (stristr($q, substr($profil['pseudo'], 0, $len))) {
            // Vérifie si la limite des résultats est activée et si le nombre maximum de résultats n'est pas atteint
            if (!$limitResults || ($limitResults && $count < 5)) {
                if ($res === "") {
                    $res = '<div class="profile" data-user-id="' . $profil['id'] . '">' . '<img src="photo_profil_utilisateurs/' . $profil['id'] . '.jpg" alt="Photo de profil">' . '<span>' . $profil['pseudo'] . '</span>' . '</div>';
                } else {
                    $res .= '<div class="profile" data-user-id="' . $profil['id'] . '">' . '<img src="photo_profil_utilisateurs/' . $profil['id'] . '.jpg" alt="Photo de profil">' . '<span>' . $profil['pseudo'] . '</span>' . '</div>';
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
