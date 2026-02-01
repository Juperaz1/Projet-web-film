Projet Web Films - Symfony

Pré-requis avant d'installer le projet

PHP 8.4
Composer
MySQL ou MariaDB


Installation
1.Cloner le projet 
git clone [URL_DU_DEPOT]
cd Projet_Web_Films

2. Configurer l'environnement
Copier le fichier d'environnement
cp .env.example .env

Installer les dépendances PHP
composer install

Installer les dépendances JavaScript
npm install

Compiler les assets
npm run dev
ou pour la production : npm run build

Lancement de l'application
docker-compose up

Pour fermer l'application
docker-compose down


Pour se connecter a la bdd la route est http://localhost:8080/

Pour voir notre site Film voici la route http://localhost:8084/ (Le mot de passe de l'admin est dans le rapport)