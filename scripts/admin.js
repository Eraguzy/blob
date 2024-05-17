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
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            
            var json = JSON.parse(this.responseText);
            console.log(json);
            var bannissements = json.bannissements;
            var tempdiv = document.getElementById('temporarycontent');
            tempdiv.innerHTML = "";

            for(var i=0; i<bannissements.length; i++){
                var conteneur = bannissements[i];

                var cas = conteneur.case;
                var auteur = conteneur.auteur.id;
                var description = conteneur.description;
                var email = conteneur.email;
                var html = `
                <div class="bannissements">
                    <h3>cas numéro ${cas}</h3>
                    email : ${email}<br>
                    banni par : ${auteur}<br>
                    description : ${description}
                </div>`;
                tempdiv.innerHTML += html;
            }
        }
    }

    ajax.open("GET", "json/bannissements.json", true);
    ajax.send();
}

function signalements(bouton){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            
            var json = JSON.parse(this.responseText);
            console.log(json);
            var signalements = json.signalements;
            var tempdiv = document.getElementById('temporarycontent');
            tempdiv.innerHTML = "";

            for(var i=0; i<signalements.length; i++){
                var conteneur = signalements[i];
                if(conteneur.statut == 0){ // vérifie que le cas n'est pas encore résolu
                    var cas = conteneur.case;
                    var description = conteneur.description;
                    var suspect = conteneur.suspect.id;
                    var auteur = conteneur.auteur.id;
                    var html = `
                    <div class="signalements">
                        <h3>cas numéro ${cas}</h3>
                        ${suspect} signalé par : ${auteur}<br>
                        description : ${description}
                    </div>`;
                    tempdiv.innerHTML += html;
                }
            }
        }
    }

    ajax.open("GET", "json/signalements.json", true);
    ajax.send();
}