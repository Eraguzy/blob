<?php
     
    function highestcase($jsonpath, $key){ //renvoie le numéro du cas actuel le plus élevé + 1 d'un fichier json
        // $key contient le nom du tableau (signalements ou bans)
        $jsonin = file_get_contents($jsonpath);
        $data = json_decode($jsonin, true);
    
        // Trouver le plus grand numéro de cas actuel
        $max_case = 0;
        foreach ($data[$key] as $item){
            if ($item['case'] > $max_case){
                $max_case = $item['case'];
            }
        }

        // Nouveau numéro de cas = plus grand numéro de cas + 1
        return $max_case + 1;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){ // actions ban/unban etc
        if (isset($_POST['action']) && $_POST['action'] === 'unban'){ //vérif de l'action voulue
            $json_content = file_get_contents('json/bannissements.json'); //fichier json lui même
            $data = json_decode($json_content, true); //extrait le tableau de tableaux contenant la hiérarchie du fichier json

            foreach($data['bannissements'] as $key => $bans){ //parcours du fichier json
                if($bans['case'] == $_POST['case']){ // comparaison du numéro de cas
                    array_splice($data['bannissements'], $key, 1); //permet une suppression propre d'un ban (1 pour 1 élément)
                    break;
                }
            }
            file_put_contents('json/bannissements.json', json_encode($data, JSON_PRETTY_PRINT));
        }


        if (isset($_POST['action']) && $_POST['action'] === 'ban'){ // 3 actions : ajout du ban, suppression des signalements liés à cet email, suppression du compte
            $json_content = file_get_contents('json/bannissements.json', 'bannissements');
            $data = json_decode($json_content, true);
            
            $new_ban = [
                "case" => highestcase('json/bannissements.json', 'bannissements'), 
                "email" => $_POST['email'], 
                "description" => $_POST['description'],
            ];
            
            $data['bannissements'][] = $new_ban; //ajout dans la variable puis dans la BDD
            file_put_contents('json/bannissements.json', json_encode($data, JSON_PRETTY_PRINT));
            

            // supp tous les signalements liés à ce mail
            $json_content = file_get_contents('json/signalements.json');
            $data = json_decode($json_content, true);

            foreach($data['signalements'] as $key => $sign){
                if($sign['suspect']['id'] == $_POST['email']){
                    array_splice($data['signalements'], $key, 1);
                }
            }
            file_put_contents('json/signalements.json', json_encode($data, JSON_PRETTY_PRINT));


            // suppression du compte
            $json_content = file_get_contents('../compte.json', 'bannissements');
            $data = json_decode($json_content, true);

            foreach($data['utilisateurs'] as $key => $user){ // recherche et suppression du compte associé
                if($user['email'] == $_POST['email']){
                    $idsupp = $user['id'];
                    array_splice($data['utilisateurs'], $key, 1);

                    foreach($data['profils'] as $profile_key => $profile){ // recherche et suppression du profil associé
                        if($profile['id'] == $idsupp){
                            array_splice($data['profils'], $profile_key, 1);
                        }
                    }
                }
            }
            file_put_contents('../compte.json', json_encode($data, JSON_PRETTY_PRINT));
        }


        if (isset($_POST['action']) && $_POST['action'] === 'supp'){ //supp un signalement
            $json_content = file_get_contents('json/signalements.json');
            $data = json_decode($json_content, true);
            
            foreach($data['signalements'] as $key => $sign){
                if($sign['case'] == $_POST['case']){
                    array_splice($data['signalements'], $key, 1);
                    break;
                }
            }
            file_put_contents('json/signalements.json', json_encode($data, JSON_PRETTY_PRINT));   
        }

        if (isset($_POST['action']) && $_POST['action'] === 'report'){ // créer un signalement
            $json_content = file_get_contents('json/signalements.json');
            $data = json_decode($json_content, true);
            
            $new_report = [
                "case" => highestcase('json/signalements.json', 'signalements'), 
                "suspect" => ["id" => $_POST['email']], 
                "description" => $_POST['description'],
            ];
            
            $data['signalements'][] = $new_report; //ajout dans la variable puis dans la BDD
            file_put_contents('json/signalements.json', json_encode($data, JSON_PRETTY_PRINT));
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../styles/admin.css">
        <title>Administrateur</title>
        <link rel="icon" href="../logo.png">
    </head>
    <body>
        <nav class="bandeau">
            <img src="../logo.png" class="img" onclick="linkopener('../accueil.php')">
            <div class="bandeautitle">BLOB</div>
            <div class="titrebandeau">Tableau de bord administrateur</div>
            <input type="button" class="bouton" value="Accueil" onclick="linkopener('../index.php')"/>
        </nav>

        <div class="titre">
        <input type="button" class="bouton boutonadmin" value="Signalements" onclick="setopacitybutton(this); signalements(this);"/>
        <input type="button" class="bouton boutonadmin" value="Bannissements" onclick="setopacitybutton(this); bannissements(this);"/>
        <input type="button" class="bouton boutonadmin" value="Modifier/Supprimer un profil" onclick="setopacitybutton(this); modifprofils(this)"/>
        </div>
        <div id="temporarycontent">
        </div>

        <script src="../script.js" type="text/javascript"></script>
        <script src="../scripts/admin.js" type="text/javascript"></script>
    </body>
</html>
