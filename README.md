Projet Web Films - Symfony

Pré-requis avant d'installer le projet

PHP 8.4
Composer
MySQL ou MariaDB

Installation
1.Cloner le projet 
git clone [URL_DU_DEPOT]
cd Projet_web_film

2.Configurer l'environnement
Copier le fichier d'environnement
cp .env.example .env

Installer les dépendances PHP
composer install

Installer les dépendances JavaScript
npm install

Compiler les assets
npm run dev
ou pour la production : npm run build

3.Import de la base de données
Accédez à http://localhost:8080 et connectez-vous avec les identifiants (voir rapport)
Créez une base films_db et importez le fichier films.sql situé à la racine du projet

Lancement de l'application
docker-compose up

Pour arreter l'application
docker-compose down

Voir les logs 
docker-compose logs -f

Pour se connecter a la bdd la route est http://localhost:8080/
Pour voir notre site Film voici la route http://localhost:8084/ (le mot de passe de l'admin et son mail sont dans le rapport)
