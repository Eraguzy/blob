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
                <div class="encadre">
                    <div class="encadreheader">
                        <h3>cas numéro ${cas}</h3>
                        <div>
                            <input type="button" value="Unban" onclick="boutonaction('${cas}', 'unban', this)"/>
                        </div>
                    </div>
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
                var cas = conteneur.case;
                var description = conteneur.description;
                var suspect = conteneur.suspect.id;
                var auteur = conteneur.auteur.id;
                var html = `
                <div class="encadre">
                    <div class="encadreheader">
                        <h3>cas numéro ${cas}</h3>
                        <div>
                            <input type="button" value="Supprimer" onclick="boutonaction('${cas}','supp', this)"/>
                            <input type="button" value="Bannir" onclick="boutonaction('${cas}','ban', this)"/>
                        </div>
                    </div>
                    ${suspect} signalé par : ${auteur}<br>
                    description : ${description}
                </div>`;
                tempdiv.innerHTML += html;
            }
        }
    }

    ajax.open("GET", "json/signalements.json", true);
    ajax.send();
}

//  actions
function boutonaction(caseid, event, boutonchoisi){
    var req = new XMLHttpRequest();
    req.open("POST","../admin/adminmenu.php", true); // définition du fichier php contenant l'action à realiser
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    req.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            var newText = document.createTextNode("Rafraîchissez la page."); 
            boutonchoisi.parentNode.replaceChild(newText, boutonchoisi);
        }
    }
    
    param = "action="+ event +"&case=" + caseid; //envoi au php du cas à supp
    req.send(param);
}