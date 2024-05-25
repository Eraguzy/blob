<?php
// on vérifie si la personne est connecté
if (!isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
    exit;
}
//on récup l'user, ainsi que l'abonnement choisi par la méthode post, ouverture du fichier json
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_utilisateur = $_COOKIE['user_id'];
    $abo = $_POST['abo'];
    $fichier = "compte.json";

    if (file_exists($fichier)) {
        $json_contenue = file_get_contents($fichier);
        $data = json_decode($json_contenue, true);
    } else {
        $data = ["profils" => []];
    }
//on remplace le statut par défaut par l'offre choisie, on lance le compteur avec la fonction time()
    foreach ($data['profils'] as &$profil) {
        if ($profil['id'] == $id_utilisateur) {
            $profil['statut'] = $abo;
            $profil['statut_starter_time'] = time();
            break;
        }
    }
//on remet tous les changements dans le fichier json
    $json_new_contenue = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($fichier, $json_new_contenue);

    header("Location: abonne.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/page_souscription.css">
    <link rel="icon" href="logo.png">
    <title>Blob</title>
</head>

<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Souscription</div>
        <input type="button" class="bouton" value="Accueil" onclick="linkopener('index.php')" />
    </nav>

    <div class="Connexion-page">
        <div class="Connexion-boite">
            <h2 class="legende">Souscription</h2>
            <form name="souscription" method="post" action="souscription.php">
                <div class="donnees">
                    <div class="container">
                    <input type="radio" id="decouverte" name="abo" value="decouverte" checked />
                    <label for="decouverte" class="discover">Offre découverte : 1 minute</label>
                    <div class="encart1" >Avec cette offre découverte, vous aurez accès aux fonctionnalités de Blob, pendant 1 minute</div>
                    </div>
                </div>
                <div class="donnees">
                    <div class="container">
                    <input type="radio" id="classique" name="abo" value="classique" />
                    <label for="classique" class="classic">Offre classique : 3 minutes</label>
                    <div class="encart2">Avec l'offre classique, vous bénéficiez de toutes les fonctionnalités de Blob, pendant 3 minutes</div>
                    </div>
                </div>
                <div class="donnees">
                    <div class="container">
                    <input type="radio" id="vip" name="abo" value="vip" />
                    <label for="vip" class="privilege">Offre VIP : 5 minutes</label>
                    <div class="encart3">Avec cette super offre VIP, vous bénéficiez de toutes les fonctionnalités de Blob pendant un maximum de temps (5 minutes)!!!!</div>
                    </div>
                </div>
                <input type="submit" value="Souscrire" />
            </form>
        </div>
    </div>

    <script>
          //fonction qui permet la redirection vers les autres pages
    function linkopener(a){
    window.open(a,'_self');
}
        //permet d'afficher un encart quand on survole la proposition d'abonnement
const element1 = document.querySelector('.discover');
const encart1 = document.querySelector('.encart1');

const element2 = document.querySelector('.classic');
const encart2 = document.querySelector('.encart2');

const element3 = document.querySelector('.privilege');
const encart3 = document.querySelector('.encart3');

element1.addEventListener('mouseover', () => {
  encart1.style.display = 'block';
});

element1.addEventListener('mouseout', () => {
  encart1.style.display = 'none';
});

element2.addEventListener('mouseover', () => {
    encart2.style.display = 'block';
  });
  
element2.addEventListener('mouseout', () => {
    encart2.style.display = 'none';
  });


element3.addEventListener('mouseover', () => {
    encart3.style.display = 'block';
  });
  
element3.addEventListener('mouseout', () => {
    encart3.style.display = 'none';
  });

  
</script>
    
</body>

</html>
