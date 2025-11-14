Bibliothèque en Ligne - Documentation
📚 Description du Projet
Site web interactif pour une bibliothèque en ligne permettant aux utilisateurs de rechercher, consulter et gérer des livres. Développé avec HTML, CSS, JavaScript, PHP et MySQL dans le cadre d'un cours de développement web niveau intermédiaire.

🎯 Fonctionnalités
👤 Utilisateurs
Recherche de livres par titre, auteur ou description

Consultation des détails complets des livres

Liste de lecture personnelle pour sauvegarder ses livres préférés

Interface responsive et intuitive

🛠️ Administration
Gestion complète des livres (CRUD : Créer, Lire, Mettre à jour, Supprimer)

Tableau de bord avec statistiques

Recherche avancée avec multiples filtres

🗂️ Structure des Fichiers
text
bibliotheque/
├── index.php                  # Page d'accueil
├── search.php                 # Recherche simple
├── search_advanced.php        # Recherche avancée
├── details.php                # Détails d'un livre
├── wishlist.php               # Liste de lecture
├── admin/                     # Section administration
│   ├── books_management.php   # Gestion des livres
│   └── edit_book.php          # Modification d'un livre
├── includes/                  # Fichiers d'inclusion
│   ├── header.php             # En-tête du site
│   ├── footer.php             # Pied de page
│   ├── navigation.php         # Menu de navigation
│   ├── database.php           # Connexion à la BDD
│   ├── functions.php          # Fonctions utilitaires
│   └── crud.php               # Opérations CRUD
├── css/
│   └── style.css              # Styles principaux
└── js/
    └── script.js              # Scripts JavaScript
🗄️ Structure de la Base de Données
Table livres
Colonne	Type	Description
id	INT	Identifiant unique du livre
titre	VARCHAR(100)	Titre du livre
auteur	VARCHAR(100)	Auteur du livre
description	TEXT	Description du livre
maison_edition	VARCHAR(100)	Maison d'édition
nombre_exemplaire	INT	Nombre d'exemplaires disponibles
Table lecteurs
Colonne	Type	Description
id	INT	Identifiant unique du lecteur
nom	VARCHAR(100)	Nom du lecteur
prenom	VARCHAR(100)	Prénom du lecteur
email	VARCHAR(100)	Adresse email
Table liste_lecture
Colonne	Type	Description
id_livre	INT	Identifiant du livre
id_lecteur	INT	Identifiant du lecteur
date_emprunt	DATE	Date d'emprunt
date_retour	DATE	Date de retour
⚙️ Installation
Prérequis
Serveur web (Apache, Nginx)

PHP 7.4 ou supérieur

MySQL 5.7 ou supérieur

phpMyAdmin (recommandé)

Étapes d'installation
Cloner le projet

bash
git clone [url-du-projet]
cd bibliotheque
Configurer la base de données

sql
-- Créer la base de données
CREATE DATABASE bibliotheque;

-- Exécuter le script SQL fourni dans database_setup.sql
-- ou utiliser phpMyAdmin pour importer la structure
Configurer la connexion à la BDD
Éditer le fichier includes/database.php :

php
$host = 'localhost';
$dbname = 'bibliotheque';
$username = 'votre_utilisateur';
$password = 'votre_mot_de_passe';
Configurer les permissions

bash
chmod 755 css/ js/ includes/
chmod 644 *.php
🚀 Utilisation
Pages principales
Page d'accueil (index.php)

Présentation de la bibliothèque

Formulaire de recherche rapide

Instructions d'utilisation

Recherche (search.php et search_advanced.php)

Recherche simple par mots-clés

Recherche avancée avec filtres

Affichage des résultats en grille

Détails d'un livre (details.php)

Informations complètes du livre

Bouton d'ajout à la liste de lecture

Disponibilité en temps réel

Liste de lecture (wishlist.php)

Livres sauvegardés par l'utilisateur

Gestion (ajout/suppression)

Dates d'emprunt

Administration
Accéder à la section administration :

text
/admin/books_management.php
Fonctionnalités admin :

Ajouter de nouveaux livres

Modifier les informations existantes

Supprimer des livres

Visualiser les statistiques

🔧 Développement
Technologies utilisées
Frontend : HTML5, CSS3, JavaScript (ES6+)

Backend : PHP 7.4+, MySQL

Style : CSS Grid, Flexbox, Design Responsive

Sécurité : Prepared Statements, Validation des données

Bonnes pratiques implémentées
Sécurité :

Protection contre les injections SQL

Validation des données utilisateur

Échappement des sorties HTML

Performance :

Requêtes SQL optimisées

Pagination des résultats

Cache des ressources statiques

Accessibilité :

HTML sémantique

Contraste des couleurs

Navigation au clavier

Maintenabilité :

Séparation des préoccupations

Code modulaire avec inclusions

Documentation complète

📊 Fonctions CRUD implémentées
Pour les livres
createBook() - Ajouter un livre

readAllBooks() - Lister tous les livres

readBookById() - Obtenir un livre par ID

updateBook() - Modifier un livre

deleteBook() - Supprimer un livre

Pour la liste de lecture
addToWishlist() - Ajouter à la liste

getWishlist() - Obtenir la liste

removeFromWishlist() - Retirer de la liste

🎨 Personnalisation
Modifier le style
Éditer le fichier css/style.css :

css
:root {
  --primary-color: #3498db;
  --secondary-color: #2c3e50;
  --accent-color: #e74c3c;
}
Ajouter des fonctionnalités
Les fichiers modulaires permettent d'étendre facilement :

Ajouter des fonctions dans includes/functions.php

Créer de nouvelles pages en réutilisant les inclusions

Étendre la base de données via database_setup.sql

🐛 Dépannage
Problèmes courants
Erreur de connexion à la BDD

Vérifier les identifiants dans database.php

S'assurer que MySQL est démarré

Pages blanches

Activer l'affichage des erreurs PHP

Vérifier les permissions des fichiers

Problèmes d'affichage CSS/JS

Vérifier les chemins dans les inclusions

Vider le cache du navigateur

Logs
Les erreurs sont journalisées dans :

Logs PHP (error_log)

Logs MySQL

📝 Améliorations futures
Système d'authentification utilisateur

Réservation de livres en ligne

Notifications par email

API REST pour applications mobiles

Système de recommandations

Export de données (PDF, Excel)

👥 Contribution
Fork le projet

Créer une branche feature (git checkout -b feature/AmazingFeature)

Commit les changements (git commit -m 'Add AmazingFeature')

Push vers la branche (git push origin feature/AmazingFeature)

Ouvrir une Pull Request

📄 Licence
Ce projet est développé dans un cadre éducatif. Libre utilisation pour l'apprentissage.

👨‍💻 Auteur
Développé dans le cadre du cours de Développement Web - Niveau Intermédiaire

