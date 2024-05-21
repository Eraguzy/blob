function setopacitybutton(bouton){ // il faut que le conteneur en question ait la classe "titre" pour pouvoir utiliser ceci correctement
    var allboutons = document.querySelectorAll(".titre input[type='button']");

    for (var i=0; i<allboutons.length; i++) {
        allboutons[i].style.opacity = "30%";
    }
    bouton.style.opacity = "100%";
}

function modifprofils(bouton){ // il y a un div vide dans le html, on remplace le html à l'intérieur à chaque pression de bouton par ce que l'on souhaite
    var html =
        `
        <div class="conteneur">
            <form action="page_recherche.php" method="get" class="recherche">
                <input type="text" name="q" id="recherche" placeholder="Rechercher..." onkeyup="Suggestions(this.value, '../photo_profil_utilisateurs/')">
            </form>
        </div>
        <div id="res"></div>
        <div class="vuescontainer">
            <input type="button" class="bouton boutonvue" value="Accès interface non-abonné" onclick="linkopener('../accueil.php')"/>
            <input type="button" class="bouton boutonvue" value="Accès interface abonné" onclick="linkopener('../abonne.php')"/>
        </div>`

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
                    description : ${description}
                </div>`;
                tempdiv.innerHTML += html;
            }
            tempdiv.innerHTML += "Les administrateurs ne peuvent pas être bannis.";
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
            var signalements = json.signalements;
            var tempdiv = document.getElementById('temporarycontent');
            tempdiv.innerHTML = "";

            for(var i=0; i<signalements.length; i++){
                var conteneur = signalements[i];
                var cas = conteneur.case;
                var description = conteneur.description;
                var suspect = conteneur.suspect.id;
                var html = `
                <div class="encadre">
                    <div class="encadreheader">
                        <h3>cas numéro ${cas}</h3>
                        <div>
                            <input type="button" value="Supprimer le signalement" onclick="boutonaction('${cas}','supp', this)"/>
                            <input type="button" value="Bannir" onclick="boutonaction('${cas}','ban', this,'${suspect}')"/>
                        </div>
                    </div>
                    ${suspect} signalé<br>
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
// les bans et signalements se feront selon email car beaucoup plus simple pour bannir
function boutonaction(caseid, event, boutonchoisi, email = ""){ //boutonchoisi pour remplacer le bouton par le texte souhaité pour afficher sur le client
    var req = new XMLHttpRequest();
    req.open("POST","../admin/adminmenu.php", true); // définition du fichier php contenant l'action à realiser
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    req.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            var newText = document.createTextNode("Patientez et rafraîchissez la page, cela peut prendre un certain temps..."); 
            boutonchoisi.parentNode.replaceChild(newText, boutonchoisi);
        }
    }

    if(event == "ban" || event == "report"){ // demande de description unique lorsque que c'est pertinent
        var description = prompt("Veuillez entrer une description pour cette action");
    }

    param = "action="+ event +"&case=" + caseid +"&email=" + email +"&description=" + description; //envoi au php du cas à supp et de l'email concerné si besoin
    req.send(param);
}