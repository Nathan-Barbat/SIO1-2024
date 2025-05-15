QuizApp - Application Web de Quiz

Présentation
QuizApp est une application web de quiz développée en PHP selon l'architecture MVC (Modèle-Vue-Contrôleur) pour les centres de formation. Cette plateforme permet aux formateurs de créer des quiz interactifs et aux stagiaires de tester leurs connaissances en temps réel.

Fonctionnalités principales

Pour les formateurs
.Gestion des quiz : Création, modification et suppression de quiz personnalisés
.Gestion des questions : Ajout de questions avec niveaux de difficulté variables (facile, moyen, difficile)
.Organisation par catégories : Classement des questions par thèmes pour faciliter la recherche
.Suivi des performances : Visualisation des résultats et statistiques des stagiaires

Pour les stagiaires
.Participation interactive : Interface intuitive avec timer de 10 secondes par question
.Feedback instantané : Retour immédiat sur les réponses données
.Suivi de progression : Historique des scores et statistiques personnelles
.Classement : Comparaison des résultats avec les autres stagiaires

Technologies utilisées
.Backend : PHP 7.4+ (POO, architecture MVC)
.Frontend : HTML5, CSS3, JavaScript, Bootstrap 5
.Base de données : MySQL 5.7+
.Sécurité : Protection contre les injections SQL, validation des données, gestion des sessions
.Responsive design : Interface adaptée à tous les formats d'écran

Structure du projet
quiz-app/
│
├── config/                  # Configuration de l'application
│   ├── config.php           # Configuration générale
│   └── database.php         # Configuration de la BDD
│
├── models/                  # Modèles (interactions avec la BDD)
│   ├── Database.php         # Classe de connexion à la BDD
│   ├── User.php             # Gestion des utilisateurs
│   ├── Category.php         # Gestion des catégories
│   ├── Question.php         # Gestion des questions
│   ├── Quiz.php             # Gestion des quiz
│   └── Score.php            # Gestion des scores
│
├── controllers/             # Contrôleurs (logique métier)
│   ├── UserController.php   # Contrôleur des utilisateurs
│   ├── CategoryController.php # Contrôleur des catégories
│   ├── QuestionController.php # Contrôleur des questions
│   ├── QuizController.php   # Contrôleur des quiz
│   ├── ScoreController.php  # Contrôleur des scores
│   └── DashboardController.php # Contrôleur des tableaux de bord
│
├── views/                   # Vues (interfaces utilisateur)
│   ├── layout/              # Layout général (header, footer)
│   │   ├── header.php       # En-tête commun
│   │   └── footer.php       # Pied de page commun
│   │
│   ├── home/                # Pages d'accueil
│   │   └── index.php        # Page d'accueil principale
│   │
│   ├── auth/                # Pages d'authentification
│   │   ├── login.php        # Formulaire de connexion
│   │   └── register.php     # Formulaire d'inscription
│   │
│   ├── user/                # Pages utilisateurs
│   │   └── profile.php      # Profil utilisateur
│   │
│   ├── dashboard/           # Tableaux de bord
│   │   ├── trainer.php      # Tableau de bord formateur
│   │   └── student.php      # Tableau de bord stagiaire
│   │
│   ├── quiz/                # Pages de quiz
│   │   ├── index.php        # Liste des quiz
│   │   ├── create.php       # Création de quiz
│   │   ├── edit.php         # Modification de quiz
│   │   ├── view.php         # Détails d'un quiz
│   │   └── play.php         # Interface de jeu
│   │
│   ├── question/            # Pages de questions
│   │   ├── index.php        # Liste des questions
│   │   ├── create.php       # Création de question
│   │   └── edit.php         # Modification de question
│   │
│   ├── category/            # Pages de catégories
│   │   ├── index.php        # Liste des catégories
│   │   ├── create.php       # Création de catégorie
│   │   └── edit.php         # Modification de catégorie
│   │
│   └── scores/              # Pages de scores
│       ├── ranking.php      # Classement général
│       ├── student.php      # Scores d'un stagiaire
│       └── quiz.php         # Scores d'un quiz spécifique
│
├── assets/                  # Ressources statiques
│   ├── css/                 # Feuilles de style
│   │   ├── style.css        # Styles principaux
│   │   └── responsive.css   # Styles responsives
│   │
│   ├── js/                  # Scripts JavaScript
│   │   ├── main.js          # Script principal
│   │   └── quiz.js          # Script pour le jeu de quiz
│   │
│   └── img/                 # Images et icônes
│
├── utils/                   # Classes utilitaires
│   ├── Auth.php             # Gestion de l'authentification
│   ├── Session.php          # Gestion des sessions
│   └── Validator.php        # Validation des données
│
├── index.php                # Point d'entrée de l'application
└── README.md                # Documentation du projet

Installation

Prérequis
.Serveur web (Apache, Nginx)
.PHP 7.4 ou supérieur
.MySQL 5.7 ou supérieur
.Configuration
.bash
# Cloner le dépôt
git clone https://github.com/votre-utilisateur/quiz-app.git
cd quiz-app

# Créer la base de données
mysql -u username -p -e "CREATE DATABASE quiz_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

# Importer la structure de la base de données
mysql -u username -p quiz_app < database/quiz_app.sql

Configuration de l'application
.Modifiez le fichier config/database.php pour y insérer vos identifiants de connexion à la base de données
.Ajustez les paramètres dans config/config.php selon votre environnement

Déploiement
.Configurez votre serveur web pour pointer vers le dossier du projet
.Assurez-vous que les permissions des fichiers et dossiers sont correctement définies

Guide d'utilisation
En tant que formateur
.Création de compte
.Inscrivez-vous en tant que formateur depuis la page d'inscription
.Connectez-vous avec vos identifiants
.Gestion des catégories
.Créez des catégories pour organiser vos questions
.Modifiez ou supprimez les catégories existantes
.Création de questions
.Ajoutez des questions en spécifiant :
.Le texte de la question
.La catégorie associée
.Le niveau de difficulté
.Les choix de réponses (minimum 2)
.La réponse correcte
.Création de quiz
.Créez un nouveau quiz en lui donnant un titre et une description
.Sélectionnez les questions à inclure dans le quiz
.Publiez le quiz pour le rendre accessible aux stagiaires
.Suivi des résultats
.Consultez les scores des stagiaires pour chaque quiz
.Analysez les statistiques de performance

En tant que stagiaire
.Création de compte
.Inscrivez-vous en tant que stagiaire depuis la page d'inscription
.Connectez-vous avec vos identifiants
.Participation aux quiz
.Parcourez la liste des quiz disponibles
.Sélectionnez un quiz pour commencer à jouer
.Répondez aux questions dans le temps imparti (10 secondes par question)
.Visualisez vos résultats à la fin du quiz
.Suivi de progression
.Consultez votre historique de scores
.Analysez vos statistiques personnelles
.Comparez vos résultats avec le classement général
Sécurité
.L'application intègre plusieurs mesures de sécurité :

Protection contre les injections SQL : Utilisation de requêtes préparées PDO

Validation des données : Filtrage et validation de toutes les entrées utilisateur

Hachage des mots de passe : Utilisation de la fonction password_hash pour stocker les mots de passe

Gestion des sessions : Mécanismes de protection contre la fixation de session

Contrôle d'accès : Vérification des droits d'accès pour chaque action

Développement et contribution

Bonnes pratiques
.Architecture MVC pour une séparation claire des responsabilités
.Programmation orientée objet pour une meilleure réutilisation du code
.Code commenté pour faciliter la compréhension et la maintenance
.Responsive design pour une expérience utilisateur optimale sur tous les appareils
.Gestion des erreurs adaptée aux différents environnements (développement/production)

Contribution
.Fork le dépôt
.Créez une branche pour votre fonctionnalité (git checkout -b feature/amazing-feature)
.Committez vos changements (git commit -m 'Add some amazing feature')
.Push sur la branche (git push origin feature/amazing-feature)
.Ouvrez une Pull Request

Crédits
Développé dans le cadre d'un projet BTS SIO SLAM.

Licence
Ce projet est développé à des fins éducatives uniquement.

