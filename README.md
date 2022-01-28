projet : Musical-Monk

le site fonctionne avec MySQL et PHP et est accessible à l'adresse suivante.
https://musical-monk.alwaysdata.net

-----------------
installation en local
------------------
telecharger et installez MAMP: https://www.mamp.info/fr/downloads/


telecharger le dossier du site web sur : https://github.com/rackhamledev/Musical-Monk.git
placez le dossier dans le dossier MAMP.
utilisez le fichier "musical_monk.sql" pour installer la base donnée avec phpMyAdmin (ou autre)

la ligne 3 du fichier "view/backend/connectDB.php" est utilisé pour relier la base de donnée à PHP. à modifier si vous souhaitez changer la façon que PHP se connecte à la base de donnée par défaut.


----------------
FONCTIONNEMENT DU SITE WEB
----------------

Musical Monk est un site réalisé pour une entreprise(fictif) de vente dans le domaine musical et propose des évènements réalisé par la communauté.

la page d'accueil "home" resume le concept du site. un produit aléatoire est mis en avant sur le coté gauche. le prochain évènement réalisé par la communauté est affiché sur le coté droit.
une barre de recherche pour les produits est mis à disposition et permet de faire des recherche par mot clés (suivant le titre et/ou la description du produit) et il est possible d'affiner la recherche avec des catégories.

la page "produit" permet de voir une photo du produit, son titre, sa description, son prix (par pièce et en euro) ainsi que la quantité disponnible en magasin. si l'utilisateur est connecté, il à la possibilité de reserver un ou plusieurs exemplaires du produit. si il n'y a plus de quantité disponible, le produit n'apparait plus dans les recherches.

la page "planning" affiche tout les prochains évènements, classés par date à partir du moment actuel (les évènements passés ne sont pas affichés). si l'utilisateur est connecté, il peux creer et organiser un nouvel évènement.

la page "event" permet de voir le titre de l'évènement, la description, la date/durée de l'évènement, le nombre de place reservé/libre, et les pseudos des personnes qui ont resevé une place. si l'utilisateur est connecté, il peux reserver une place pour l'évènement avec une option suplémentaire si il est accompagné (il reservera donc 2 place au lieux de 1). si il n'y a pas assez de place pour venir à 2, l'utilisaeur ne pourra reserver que pour lui même. si il ne reste plus de place, l'utilisateur ne pourra pas reserver.

la page "creer un compte / connexion" permet de se connecter ou de creer un nouveau compte utilisateur. après avoir creer un compte, l'utilisateur doit valider son adresse mail avec le lien envoyé sur l'adresse mail indiqué. à chaque connection, il sera seulement demandé le pseudo et le mot de passe.

seuls les utilisateurs ADMIN connectés peuvent avoir accès à la page "ajouter un produit" qui sert à rajouter de nouveaux produits à reservé.

tout les utilisateurs connectés peuvent avoir accès à la page "mon compte" qui permet d'avoir un resumé de son compte et des produits reservés.
si il le souhaite, l'utilisateur pourra voir les details du/des produits/évènements qu'il à reservés.
si il le souhaite, l'utilisateur pourra supprimer ses reservations. le nombre de places des évènements et la quantités des produits disponnibles seront mis à jour à la suppression des reservations.
