<?php
// Page d'administration pour la gestion des livres
include 'includes/database.php';
include 'includes/crud.php';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description']);
    $maison_edition = trim($_POST['maison_edition']);
    $nombre_exemplaire = intval($_POST['nombre_exemplaire']);

    if (!empty($titre) && !empty($auteur)) {
        $new_book_id = creerLivre($pdo, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire);

        if ($new_book_id) {
            $success_message = "Livre ajouté avec succès!";
        } else {
            $error_message = "Erreur lors de l'ajout du livre.";
        }
    } else {
        $error_message = "Le titre et l'auteur sont obligatoires.";
    }
}

// Traitement de la suppression
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    if (supprimerLivre($pdo, $delete_id)) {
        $success_message = "Livre supprimé avec succès!";
    } else {
        $error_message = "Erreur lors de la suppression du livre.";
    }
}

// Récupérer tous les livres
$listes = listeLivresCree($pdo);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Livres - Administration</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-section,
        .books-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .books-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .books-table th,
        .books-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .books-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    
    <div style="position: absolute; top: 35px; right: 230px;">
        <button style="
        background-color: #3498db;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.3s;
        ">
            <a href="index.php" style="color: white; text-decoration: none;">
                <i class="fa-solid fa-house"></i> Passer à l'accueil
            </a>
        </button>
    </div>

    <div class="admin-container">
        <h1>Gestion des Livres - Administration</h1>
        <br>

        <!-- Messages d'alerte -->
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <section class="form-section">
            <h2> <i class="fa-solid fa-book"></i> Ajouter un nouveau livre</h2>
            <br>
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="titre">Titre *</label>
                        <input type="text" id="titre" name="titre" required>
                    </div>

                    <div class="form-group">
                        <label for="auteur">Auteur *</label>
                        <input type="text" id="auteur" name="auteur" required>
                    </div>

                    <div class="form-group">
                        <label for="maison_edition">Maison d'édition</label>
                        <input type="text" id="maison_edition" name="maison_edition">
                    </div>

                    <div class="form-group">
                        <label for="nombre_exemplaire">Nombre d'exemplaires</label>
                        <input type="number" id="nombre_exemplaire" name="nombre_exemplaire" value="1" min="0">
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                </div>

                <button type="submit" name="add_book" class="btn"> <i class="fa-solid fa-plus"></i> Ajouter le livre</button>
            </form>
        </section>

        <!-- Liste des livres -->
        <section class="books-section" style="margin-bottom: 5rem;">
            <h2><i class="fa-solid fa-book"></i> Liste des livres (<?php echo count($listes); ?>)</h2>

            <?php if (empty($listes)): ?>
                <p>Aucun livre dans la bibliothèque.</p>
            <?php else: ?>
                <table class="books-table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Éditeur</th>
                            <th>Exemplaires</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listes as $key => $liste): ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo htmlspecialchars($liste['titre']); ?></td>
                                <td><?php echo htmlspecialchars($liste['auteur']); ?></td>
                                <td><?php echo htmlspecialchars($liste['maison_edition']); ?></td>
                                <td><?php echo $liste['nombre_exemplaire']; ?></td>
                                <td class="action-buttons">
                                    <a href="modifier_livre.php?id=<?php echo $liste['id']; ?>" class="btn btn-sm"><i class="fa-solid fa-pen"></i> Modifier</a>
                                    <a href="?delete_id=<?php echo $liste['id']; ?>"
                                        class="btn btn-sm danger"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                                        <i class="fa-solid fa-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>