# Merci Nicomak - Application de Remerciements

Cette application Symfony permet aux salariés de Nicomak de se remercier mutuellement pour les services rendus. L’objectif est de favoriser une culture de gratitude au sein de l'équipe. L’application inclut des fonctionnalités pour gérer les messages de remerciements entre collègues.

## Prérequis

Avant de démarrer, assurez-vous d’avoir les éléments suivants installés :
- **PHP** (version 7.4.26 ou supérieure)
- **Composer** (version 2.1 ou supérieure)
- **Symfony CLI** (version 5.10.4 ou supérieure)
- **MySQL** pour la gestion de la base de données

## Configuration et Installation

1. **Cloner le dépôt GitHub :**
   ```bash
   git clone https://github.com/yedeshamda/Merci-Nicomak.git
   cd Merci-Nicomak
Configurer les variables d'environnement
Créez un fichier .env.local en dupliquant le fichier .env :

bash
Copier le code
cp .env .env.local
Modifiez le fichier .env.local pour configurer la connexion à la base de données avec la bonne URL :

env
Copier le code
DATABASE_URL="mysql://root:@127.0.0.1:3306/nicomak_merci_app?charset=utf8mb4"
Commandes de Mise en Place de l'Application
Pour configurer et mettre en place l'application, exécutez les commandes suivantes :

1. Créer la base de données
bash
Copier le code
php bin/console doctrine:database:create
2. Générer et exécuter les migrations
bash
Copier le code
php bin/console make:migration
php bin/console doctrine:migrations:migrate
3. Charger les données de test
bash
Copier le code
php bin/console doctrine:fixtures:load
4. Mettre à jour les dépendances requises
bash
Copier le code
composer update phpstan/phpdoc-parser
composer require sensio/framework-extra-bundle
5. Lancer le serveur Symfony
bash
Copier le code
symfony server:start
Accédez à l’application en ouvrant http://localhost:8000 dans votre navigateur.

Fonctionnalités de l'Application
L'application "Merci Nicomak" offre les fonctionnalités suivantes :

Fonctionnalités Principales (Must Have)
Liste des Merci : Visualiser la liste des remerciements envoyés. Chaque message affiche :

Le nom de l’expéditeur.
Le nom du salarié remercié.
La raison du remerciement.
La date du remerciement.
Envoyer un Merci : Les utilisateurs peuvent envoyer un merci à un autre salarié via un formulaire simple. Ils choisissent le destinataire, saisissent une raison et envoient le message.

Fonctionnalités Supplémentaires (Nice to Have)
Interface Améliorée avec Avatars : Les avatars des salariés sont affichés pour chaque message, ajoutant une dimension visuelle agréable à l'application.

Modification d'un Merci : Les utilisateurs peuvent modifier un merci qu'ils ont créé précédemment.

Suppression d'un Merci : Les utilisateurs peuvent supprimer un merci qu'ils ont créé.

Filtrage des Merci : Les utilisateurs peuvent filtrer les messages pour ne voir que ceux qui les concernent directement.

Connexion Utilisateur : Les utilisateurs peuvent se connecter via un nom d'utilisateur et un mot de passe pour accéder aux fonctionnalités de l'application.

Structure du Projet
Le projet suit l'architecture MVC (Modèle-Vue-Contrôleur) de Symfony. Voici la structure principale du projet :

src/Entity : Définit les entités de base de données pour les Merci et les Utilisateurs.
src/Controller : Contient les contrôleurs qui gèrent la logique métier et les interactions avec l’utilisateur.
src/Repository : Contient les classes qui interagissent avec la base de données pour effectuer des requêtes.
config/ : Contient la configuration générale du projet.
templates/ : Contient les vues Twig pour l'interface utilisateur.
public/ : Dossier contenant les fichiers publics accessibles comme les images, les fichiers CSS et JS.
Utilisation de l'Application
Se Connecter
Pour accéder aux fonctionnalités, vous devez d’abord vous connecter. Les utilisateurs doivent entrer un nom d'utilisateur et un mot de passe. Des comptes de test peuvent être utilisés.

Envoyer un Merci
Pour envoyer un merci à un collègue :

Naviguez vers la page "Envoyer un Merci".
Sélectionnez un destinataire parmi les salariés.
Entrez la raison du remerciement.
Cliquez sur "Envoyer" pour soumettre le message.
Modifier ou Supprimer un Merci
Les utilisateurs peuvent modifier ou supprimer un merci qu'ils ont eux-mêmes créé en cliquant sur les options correspondantes à côté de chaque message.

Filtrer les Merci
Un filtre permet de voir uniquement les remerciements qui concernent l'utilisateur connecté.

Améliorations Futures
Tests Unitaires : Ajouter des tests pour garantir la stabilité de l'application.
Notifications par Email : Ajouter des notifications par email lorsque quelqu'un est remercié.
Statistiques : Ajouter un tableau de bord pour afficher des statistiques sur les remerciements, comme les salariés les plus remerciés.
Multilingue : Ajouter des traductions pour rendre l'application accessible à plus d'utilisateurs.
