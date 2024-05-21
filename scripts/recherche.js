function adjustContentPadding(resultsCount) {
    console.log("Nombre de résultats:", resultsCount);
    var contentElement = document.querySelector(".contenu");
    var paddingTop = 30 + resultsCount * 40;
    console.log("Padding top calculé:", paddingTop);
    contentElement.style.paddingTop = paddingTop + "px";
}

function getResultsCount() {
    var profileElements = document.querySelectorAll(".profile");
    return profileElements.length;
}

function viewProfile(id_utilisateur) {
    window.location.href = '../page_resume.php?id_utilisateur=' + id_utilisateur;
}

function Suggestions(str, cheminimg = 'photo_profil_utilisateurs/') { 
    //cheminimg = chemin vers l'image depuis le document appelant (permet d'afficher les pdp correctement lors de la recherche en changeant le chemin dans recherche.php)
    // valeur par défaut de cheminimg = quand le document appelant est à la racine
    var xhttp;

    if (str.length == 0) {
        document.getElementById("res").innerHTML = "";
        adjustContentPadding(0); // Pas de résultats
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("res").innerHTML = this.responseText;
            // adjustContentPadding(getResultsCount());

            // Ajouter un gestionnaire d'événements de clic pour chaque profil
            var profileElements = document.querySelectorAll(".profile");
            profileElements.forEach(function (element) {
                element.addEventListener('click', function () {
                    var id_utilisateur = element.getAttribute('data-user-id');
                    viewProfile(id_utilisateur);
                });
            });
        }
    };
    xhttp.open("GET", "../recherche.php?q=" + str + "&limit=true&cheminimg=" + cheminimg, true);
    xhttp.send();
}