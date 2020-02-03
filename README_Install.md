# Projet_S6_Decamps_Zitouni2

Bonjour a tous :)

#Instalation du projet

Tout d'abord assurer-vous que votre pc dispose de php 7.1.* au plus, de composer.phar, et un serveur tel que Wamp ou Xamp

1/ parameters.yml

Tout d'abord il faudra modifier ses champs dans votre fichier parameters.yml qui se trouve dans app/cofig/ 
 ````yaml
    database_host: 127.0.0.1
    database_port: null
    database_name: projet_test
    database_user: root
    database_password: null
````
2/  Mettre à jours la base de donnée 

pour mettre à jour la base de donnée il faudra ouvrir la consol dans la racine de projet puis exécuter la command suivant :
````bash
    php bin/console doctrine:schema:update --force  
````

3/Remplir la base de donnée

Cette commande permet de crée un jus de donnée initiale de l'application il crée par défaut un utilisateur admin  avec un mots de passe root 

````bash
    php bin/console doctrine:fixtures:load  
````
4/ lancer votre serveur :

````bash````
    php bin/console server:start 
````

#Fin d'installation vous pouvez maintenant commencer naviguer sur le site ^^ 
