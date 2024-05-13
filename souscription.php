<?php
// Vérification si le cookie existe
if (isset($_COOKIE['user_id'])) {
    header("Location: accueil.php");
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
                    <label for="decouverte" class="decouverte">Offre découverte : 9,90 euros par mois</label>
                    <div class="encart1" >Avec cette offre découverte, vous aurez accès aux fonctionnalités de Blob, mais limitées en temps</div>
                    </div>
                </div>
                <div class="donnees">
                    <div class="container">
                    <input type="radio" id="classique" name="abo" value="classique" />
                    <label for="classique" class="classique">Offre classique : 14,90 euros par mois</label>
                    <div class="encart2">Avec l'offre classique, vous bénéficiez de toutes les fonctionnalités de Blob, mais pour une durée limitée.</div>
                    </div>
                </div>
                <div class="donnees">
                    <div class="container">
                    <input type="radio" class="vip" name="abo" value="vip" />
                    <label for="vip" id="vip">Offre VIP : 29,90 euros par mois id</label>
                    <div class="encart3">Avec cette super offre VIP, vous bénéficiez de toutes les fonctionnalités de Blob pendant un maximum de temps !!!! Vous allez pécher du poisson !!!</div>
                    </div>
                </div>
                <input type="submit" value="Souscrire" />
            </form>
        </div>
    </div>

    <script src="script.js" type="text/javascript"></script>
</body>

</html>