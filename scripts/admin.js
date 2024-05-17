function setopacitybutton(bouton){ // il faut que le conteneur en question ait la classe "titre" pour pouvoir utiliser ceci correctement
    var allboutons = document.querySelectorAll(".titre input[type='button']");

    for (var i=0; i<allboutons.length; i++) {
        allboutons[i].style.opacity = "30%";
    }
    bouton.style.opacity = "100%";
}

function modifprofils(bouton){ // il y a un div vide dans le html, on remplace le html à l'intérieur à chaque pression de bouton par ce que l'on souhaite
    var html =
        `<form action="/search" method="get" class="recherche">
            <input type="text" name="query" placeholder="Rechercher..."/>
            <button type="submit">Rechercher</button>
        </form>;`

    let tempdiv = document.getElementById('temporarycontent');
    tempdiv.innerHTML = html;
    document.body.appendChild(tempdiv);
}

function bannissements(bouton){
    var html =
        ``
    
    let tempdiv = document.getElementById('temporarycontent');
    tempdiv.innerHTML = html;
    document.body.appendChild(tempdiv);
}

function signalements(bouton){
    var html =
        `<p id="report">test signalement 1</p>`;
        
    let tempdiv = document.getElementById('temporarycontent');
    tempdiv.innerHTML = html;

    let tempreport = document.getElementById('report');
    tempreport.style.borderRadius = "2px";
    tempreport.style.border = "1px solid";
    tempreport.style.backgroundColor = "#f4f4f4";
    tempreport.style.margin = "20px";
}