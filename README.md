# 🐡 Blob
Site de rencontre pour propriétaires de poissons rouges.

>Créé par Elias, Lucas et Louèva pour CY Tech 

Pour utiliser notre projet, configurez la racine de votre serveur local (MAMP, WAMP...) à la racine de ce projet. Depuis votre navigateur, allez ensuite dans le dossier `php`. Vous serez alors redirigé vers `index.php`. Ça y est, vous pouvez vous inscrire !

# 🔧 Fonctionnalités
Voici un aperçu de certaines fonctionnalités proposées par notre site.

### Différents niveaux de souscription
- **Non inscrit** : Accès à un preview gratuit du site, avec explication des fonctionnalités et témoignages d'utilisateurs
- **Utilisateur gratuit** : Accès restreint à la recherche/affichage de profils. Possible de modifier tous les champs de son profil, de signaler un utilisateur, de souscrire à un abonnement, ou encore de voir les noms des derniers inscrits.
- **Abonné** : Affichage des profils complets. Possible de voir qui a vu notre profil, de bloquer un utilisateur, d'accéder à la messagerie...
> Différents niveaux d'abonnement : **Offre découverte** (1 minute), **Offre classique** (3 minutes), **Offre VIP** (5 minutes). Durées appliquées à des fins de tests.

### Module administrateur
Il existe un statut spécial pour les administrateurs. En plus des privilèges abonnés, ils ont :
- La possibilité de signaler et de bannir/unban des utilisateurs
- La possibilité de modifier tous les profils
- Un accès à toutes les discussions des utilisateurs de Blob (comme Instagram d'ailleurs !) et la possibilité de supprimer n'importe quel message
- Un panel admin qui leur est propre, qui leur donne notamment accès à la liste des bans et des signalements

> Pour tester le module admin : 
>
> **email** : `laura.johnson@example.com`, **mot de passe** : `cc`

### Stockage des données
Par souci de simplicité, nous avons fait le choix de stocker nos données dans des fichiers JSON, l'objectif du projet n'étant pas d'apprendre à gérer une base de données. 
Il y a :
- Un fichier contenant les [profils](./database/compte.json)
    
    - Chaque '`utilisateur`' contient les données "importantes" : id unique, email, mot de passe **hashé**.

    - Chaque '`profil`' contient les données secondaires : l'id (pour lier profil et utilisateur), données du profil, stalkers et utilisateurs bloqués.

    - Chaque '`discussion`' contient l'id des deux personnes dont c'est la conversation + le contenu des messages.


- Un fichier contenant les [signalements](./admin/json/signalements.json)

    Chaque signalement contient une description, un numéro de cas et l'email de la personne signalée.

- Un fichier contenant les [bannissements](./admin/json/bannissements.json)

    Chaque bannissement contient une description, un numéro de cas et l'email de la personne bannie.

Par ailleurs, notre site utilise des cookies pour sauvegarder localement les données de ses utilisateurs.

### Recherche d'un utilisateur
La recherche est dynamique : il suffit de taper quelques lettres pour obtenir une liste de résultats proposés. Pour une recherche plus poussée. Cliquer sur un profil redirige directement vers sa page de profil, complet ou non selon le statut du client.

Il y a une page dédiée à la recherche : on peut effectuer une recherche selon différents critères, comme le pseudo, la ville ou encore le prénom. 

# ❌ Bugs et limitations

Des bugs d'affichages peuvent arriver. Le site a été développé avec des écrans 16:9 avec Chrome et Mozilla, sous MacOS Monterey, Linux Debian et Windows 11.

Il manque des restrictions lors de la création du profil, notamment sur l'âge.

Quelques rares bugs de redirection selon le statut de l'utilisateur, même si la quasi majorité des redirections sont correctement gérées.

...