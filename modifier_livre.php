<?php
// Page de modification d'un livre
include '../includes/database.php';
include '../includes/crud.php';

// Récupérer l'ID du livre à modifier
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: books_management.php');
    exit;
}

$book_id = intval($_GET['id']);
$book = readBookById($pdo, $book_id);

if (!$book) {
    header('Location: books_management.php');
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description']);
    $maison_edition = trim($_POST['maison_edition']);
    $nombre_exemplaire = intval($_POST['nombre_exemplaire']);
    
    if (!empty($titre) && !empty($auteur)) {
        if (updateBook($pdo, $book_id, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire)) {
            $success_message = "Livre modifié avec succès!";
            // Recharger les données du livre
            $book = readBookById($pdo, $book_id);
        } else {
            $error_message = "Erreur lors de la modification du livre.";
        }
    } else {
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
    <link rel="stylesheet" href="../assets/css/style.css">
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
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
    </style>
</head>
<body>
    
    <div class="admin-container">
        <h1>Modifier le livre</h1>
        
        <!-- Messages d'alerte -->
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <!-- Formulaire de modification -->
        <section class="form-section">
            <form method="POST">
                <div class="form-group">
                    <label for="titre">Titre *</label>
                    <input type="text" id="titre" name="titre" 
                           value="<?php echo htmlspecialchars($book['titre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="auteur">Auteur *</label>
                    <input type="text" id="auteur" name="auteur" 
                           value="<?php echo htmlspecialchars($book['auteur']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="maison_edition">Maison d'édition</label>
                    <input type="text" id="maison_edition" name="maison_edition" 
                           value="<?php echo htmlspecialchars($book['maison_edition']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="nombre_exemplaire">Nombre d'exemplaires</label>
                    <input type="number" id="nombre_exemplaire" name="nombre_exemplaire" 
                           value="<?php echo $book['nombre_exemplaire']; ?>" min="0">
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($book['description']); ?></textarea>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="update_book" class="btn">Mettre à jour</button>
                    <a href="livre_management.php" class="btn secondary">Retour</a>
                </div>
            </form>
        </section>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>