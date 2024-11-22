# scores
Scores management system API with Symfony
<hr />

Installer l'application

    - Clonez le repository GitHub
    
    - Configurez vos variables d'environnement dans le fichier .env :    
      
      => DATABASE_URL="mysql://username:password@127.0.0.1:3306/scores?serverVersion=8.0.32&charset=utf8mb4"
      
    - Téléchargez et installez les dépendances du projet avec la commande Composer suivante : composer install
    
    - Créez la base de données en utilisant la commande suivante : php bin/console doctrine:database:create
    
    - Créez la structure de la base de données en utilisant la commande : php bin/console doctrine:migrations:migrate
    
    - Installer les fixtures pour avoir un jeu de données fictives avec la commande suivante : php bin/console doctrine:fixtures:load


<hr />

Genérer les clés SSH

    Utiliser la commande suivante : php bin/console lexik:jwt:generate-keypair

<hr />

Lancer L'application
	    
    - Lancez le serveur Symfony : 
    
    - Vous pouvez désormais commencer à utiliser l'appication Bilemo sur http://localhost:8000/api
    
    - Vous pouvez effectuer les requetes HTTP à l'aide du logiciel Postman  
    
<hr />

Documentation API - Swagger
	    
    - http://localhost:8000/api/doc
<hr />

Utilisateur par défaut
	    
    {
  	"username": "user@scores-api.com",
 	"password": "password"
	}

    {
  	"username": "user@scores-api.com",
 	"password": "password"
	}



