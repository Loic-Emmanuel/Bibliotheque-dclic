<?php
// Page de modification d'un livre
include 'includes/database.php';
include 'includes/crud.php';

// Récupérer l'ID du livre à modifier
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ajouter_livre.php');
    exit;
}

$livre_id = intval($_GET['id']);
$livre = recupererLivre($pdo, $livre_id);

if (!$livre) {
    header('Location: ajouter_livre.php');
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description']);
    $maison_edition = trim($_POST['maison_edition']);
    $nombre_exemplaire = intval($_POST['nombre_exemplaire']);
    $supprimer_image = isset($_POST['supprimer_image']);

    // Gestion de l'image
    $image_nom = $livre['image']; // Garder l'image actuelle par défaut

    // Traitement de la nouvelle image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dossier_images = 'images/';

        // Créer le dossier s'il n'existe pas
        if (!is_dir($dossier_images)) {
            mkdir($dossier_images, 0755, true);
        }

        $nom_fichier = $_FILES['image']['name'];
        $fichier_tmp = $_FILES['image']['tmp_name'];
        $taille_fichier = $_FILES['image']['size'];
        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));

        // Vérifications de sécurité
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $type_mime_autorise = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $type_mime_upload = mime_content_type($fichier_tmp);

        if (
            in_array($extension, $extensions_autorisees) &&
            in_array($type_mime_upload, $type_mime_autorise) &&
            $taille_fichier <= 5000000
        ) {

            // Supprimer l'ancienne image si elle existe
            if (!empty($livre['image']) && file_exists($dossier_images . $livre['image'])) {
                unlink($dossier_images . $livre['image']);
            }

            // Générer un nom unique pour la nouvelle image
            $image_nom = uniqid() . '_' . time() . '.' . $extension;
            $chemin_image = $dossier_images . $image_nom;

            // Déplacer le fichier uploadé
            if (!move_uploaded_file($fichier_tmp, $chemin_image)) {
                $error_message = "Erreur lors du téléchargement de la nouvelle image.";
                $image_nom = $livre['image']; // Garder l'ancienne image en cas d'erreur
            }
        } else {
            $error_message = "Fichier image invalide. Formats acceptés : JPG, PNG, GIF, WebP (max 5MB).";
        }
    }

    // Supprimer l'image si demandé
    if ($supprimer_image && !empty($livre['image'])) {
        $ancien_chemin = 'images/' . $livre['image'];
        if (file_exists($ancien_chemin)) {
            unlink($ancien_chemin);
        }
        $image_nom = null;
    }

    if (!empty($titre) && !empty($auteur) && !isset($error_message)) {
        if (modifierLivre($pdo, $livre_id, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire, $image_nom)) {
            $success_message = "Livre modifié avec succès!";
            // Recharger les données du livre
            $livre = recupererLivre($pdo, $livre_id);
        } else {
            $error_message = "Erreur lors de la modification du livre.";
        }
    } elseif (!isset($error_message)) {
        $error_message = "Le titre et l'auteur sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le livre - Administration</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
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
            height: 150px;
            resize: vertical;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .current-image {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .current-image img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .image-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
        }

        .file-input {
            padding: 8px;
            border: 2px dashed #3498db;
            border-radius: 6px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #imageprevisualisation {
            margin-top: 10px;
        }

        #previsualiser {
            max-width: 150px;
            max-height: 200px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="admin-container" style="margin-top: 2rem;">
        <h1><i class="fa-solid fa-pen-to-square"></i> Modifier le livre</h1>
        <br>

        <!-- Messages d'alerte -->
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulaire de modification -->
        <section class="form-section" style="margin-bottom: 5rem;">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Titre *</label>
                    <input type="text" id="titre" name="titre"
                        value="<?php echo htmlspecialchars($livre['titre']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="auteur">Auteur *</label>
                    <input type="text" id="auteur" name="auteur"
                        value="<?php echo htmlspecialchars($livre['auteur']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="maison_edition">Maison d'édition</label>
                    <input type="text" id="maison_edition" name="maison_edition"
                        value="<?php echo htmlspecialchars($livre['maison_edition']); ?>">
                </div>

                <div class="form-group">
                    <label for="nombre_exemplaire">Nombre d'exemplaires</label>
                    <input type="number" id="nombre_exemplaire" name="nombre_exemplaire"
                        value="<?php echo $livre['nombre_exemplaire']; ?>" min="0">
                </div>

                <!-- Section Image actuelle -->
                <div class="form-group">
                    <label>Image actuelle</label>
                    <div class="current-image">
                        <?php if (!empty($livre['image']) && file_exists('images/' . $livre['image'])): ?>
                            <img src="images/<?php echo htmlspecialchars($livre['image']); ?>"
                                alt="Couverture actuelle">
                            <div class="image-actions">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="supprimer_image" name="supprimer_image" value="1">
                                    <label for="supprimer_image" style="font-weight: normal; margin: 0;">
                                        Supprimer cette image
                                    </label>
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="padding: 20px; text-align: center; color: #666;">
                                <i class="fa-solid fa-image" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                Aucune image actuellement
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Nouvelle image -->
                <div class="form-group">
                    <label for="image">Nouvelle image</label>
                    <input type="file" id="image" name="image" accept="image/*" class="file-input"
                        onchange="previsualisationImage(this)">
                    <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                        Formats acceptés : JPG, PNG, GIF, WebP (max 5MB)
                    </small>
                    <div id="imageprevisualisation" style="display: none;">
                        <img id="previsualiser" src="#" alt="Aperçu de la nouvelle image">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($livre['description']); ?></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" name="update_book" class="btn">
                        <i class="fa-solid fa-floppy-disk"></i> Mettre à jour
                    </button>
                    <a href="ajouter_livre.php" class="btn secondary">
                        <i class="fa-solid fa-arrow-left"></i> Retour
                    </a>
                </div>
            </form>
        </section>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Bibliothèque en ligne. Tous droits réservés.</p>
        </div>
    </footer>
    
    <script>
        // Prévisualisation de l'image
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
                previsualiser.src = "#";
            }
        }

        // Désactiver la case à cocher si une nouvelle image est sélectionnée
        document.getElementById('image').addEventListener('change', function() {
            const supprimerCheckbox = document.getElementById('supprimer_image');
            if (this.files && this.files[0]) {
                supprimerCheckbox.checked = false;
                supprimerCheckbox.disabled = true;
            } else {
                supprimerCheckbox.disabled = false;
            }
        });
    </script>
</body>

</html>