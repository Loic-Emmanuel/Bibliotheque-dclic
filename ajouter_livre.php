<?php
include 'includes/database.php';
include 'includes/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_livre'])) {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description']);
    $maison_edition = trim($_POST['maison_edition']);
    $nombre_exemplaire = intval($_POST['nombre_exemplaire']);
    $image_nom = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dossier_images = 'images/';

        if (!is_dir($dossier_images)) {
            mkdir($dossier_images, 0755, true);
        }

        $nom_fichier = $_FILES['image']['name'];
        $fichier_tmp = $_FILES['image']['tmp_name'];
        $taille_fichier = $_FILES['image']['size'];
        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));

        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $type_mime_autorise = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $type_mime_upload = mime_content_type($fichier_tmp);

        if (in_array($extension, $extensions_autorisees) && in_array($type_mime_upload, $type_mime_autorise) && $taille_fichier <= 5000000) {
            $image_nom = uniqid() . '_' . time() . '.' . $extension;
            $chemin_image = $dossier_images . $image_nom;

            if (move_uploaded_file($fichier_tmp, $chemin_image)) {
            } else {
                $error_message = "Erreur lors du téléchargement de l'image.";
                $image_nom = null;
            }
        } else {
            $error_message = "Fichier image invalide. Formats acceptés : JPG, PNG, GIF, WebP (max 5MB).";
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) {
        $error_message = "Erreur lors du téléchargement de l'image. Code d'erreur : " . $_FILES['image']['error'];
    }

    if (!empty($titre) && !empty($auteur) && !isset($error_message)) {
        $nouveau_livre = creerLivre($pdo, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire, $image_nom);

        if ($nouveau_livre) {
            $success_message = "Livre ajouté avec succès!" . ($image_nom ? " (avec image)" : "");
            $_POST = array();
        } else {
            $error_message = "Erreur lors de l'ajout du livre.";
            if ($image_nom && file_exists($dossier_images . $image_nom)) {
                unlink($dossier_images . $image_nom);
            }
        }
    } elseif (!isset($error_message)) {
        $error_message = "Le titre et l'auteur sont obligatoires.";
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    if (supprimerLivre($pdo, $delete_id)) {
        $success_message = "Livre supprimé avec succès!";
    } else {
        $error_message = "Erreur lors de la suppression du livre.";
    }
}

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
            font-weight: bold;
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

        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <section class="form-section">
            <h2> <i class="fa-solid fa-book"></i> Ajouter un nouveau livre</h2>
            <br>
            <form method="POST" enctype="multipart/form-data">
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

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" accept="image/*" style="padding: 8px;
                        border: 2px dashed #3498db;
                        border-radius: 6px;
                        background-color: #f8f9fa;
                        width: 100%;
                        cursor: pointer;
                        transition: all 0.3s ease;"
                            onchange="previsualisationImage(this)">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                            Formats acceptés : JPG, PNG, GIF, WebP
                        </small>
                        <div id="imageprevisualisation" style="margin-top: 10px; display: none;">
                            <img id="previsualiser" src="#" alt="Aperçu" style="max-width: 150px; max-height: 200px; border-radius: 4px; border: 1px solid #ddd;">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                </div>

                <button type="submit" name="ajouter_livre" class="btn"> <i class="fa-solid fa-plus"></i> Ajouter le livre</button>
            </form>
        </section>

        <section class="books-section" style="margin-bottom: 5rem;">
            <h2><i class="fa-solid fa-book"></i> Liste des livres (<?php echo count($listes); ?>)</h2>

            <?php if (empty($listes)): ?>
                <p>Aucun livre dans la bibliothèque.</p>
            <?php else: ?>
                <table class="books-table">
                    <thead style="color: #333;">
                        <tr>
                            <th>N°</th>
                            <th>Image</th>
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
                                <td>
                                    <?php if (!empty($liste['image']) && file_exists('images/' . $liste['image'])): ?>
                                        <img src="images/<?php echo htmlspecialchars($liste['image']); ?>"
                                            alt="Couverture de <?php echo htmlspecialchars($liste['titre']); ?>"
                                            style="width: 50px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 60px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 10px; color: #666;">
                                            <span>Aucune image</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
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

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Bibliothèque en ligne. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
        function previsualisationImage(input) {
            const previsualiser = document.getElementById("previsualiser");
            const imageprevisualisation = document.getElementById("imageprevisualisation");

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previsualiser.src = e.target.result;
                    imageprevisualisation.style.display = "block";
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                imageprevisualisation.style.display = "none";
            }
        }
    </script>
</body>

</html>