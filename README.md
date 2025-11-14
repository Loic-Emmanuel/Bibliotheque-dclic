# Gestion de Bibliothèque

Application web complète de gestion de bibliothèque développée avec PHP, MySQL, HTML, CSS et JavaScript. Cette application permet de gérer une collection de livres avec des fonctionnalités complètes de recherche, d'ajout, de modification et de suppression de livres.

## Fonctionnalités

### Pour les utilisateurs
- Recherche avancée de livres par titre, auteur ou description
- Consultation détaillée des livres
- Gestion d'une liste de souhaits personnelle
- Interface responsive adaptée à tous les appareils

### Pour les administrateurs
- Ajout de nouveaux livres avec images
- Modification des informations des livres existants
- Suppression de livres
- Gestion complète du catalogue


## Architecture

**Structure du projet** :
   ```
   bibliotheque/
   ├── assets/                # Fichiers statiques (CSS, JS, images)
   ├── includes/              # Fichiers PHP d'inclusion
   ├── images/                # Couvertures des livres
   ├── ajouter_livre.php      # Formulaire d'ajout
   ├── details.php            # Détails d'un livre
   ├── index.php              # Page d'accueil
   ├── liste_livre.php        # Liste des livres
   ├── modifier_livre.php     # Modifier un livre
   ├── results.php            # Liste de resultats de recherche
   ├── wishlist.php           # Liste de souhaits
   ├── README.md              # Fichier de documentation
   ```

## Base de données

La base de données utilise les tables :
- `livres` : Stocke les informations des livres
- `lecteurs` : Gestion des comptes lecteurs
- `liste_lecture` : Association lecteurs/livres pour la liste de lecture


## Contact

Votre nom - Yao Allou Loic Emmanuel - allouyao21@gmail.com

Lien du projet : [https://github.com/Loic-Emmanuel/Bibliotheque-dclic]
