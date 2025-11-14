<?php
include 'includes/database.php';
include 'includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$book_id = intval($_GET['id']);
$book = getBookDetails($pdo, $book_id);

if (!$book) {
    header('Location: index.php');
    exit;
}

// Traitement de l'ajout à la liste de lecture
if (isset($_POST['add_to_wishlist'])) {
    // Dans un cas réel, vous récupéreriez l'ID du lecteur connecté
    $id_lecteur = 1; // ID temporaire pour la démonstration
    
    if (addToWishlist($pdo, $book_id, $id_lecteur)) {
        $success_message = "Livre ajouté à votre liste de lecture!";
    } else {
        $error_message = "Erreur lors de l'ajout à la liste de lecture.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="book-details" style="margin-top: 5rem;">
    <h2><?php echo htmlspecialchars($book['titre']); ?></h2>
    
    <div class="book-info">
        <p><strong>Auteur:</strong> <?php echo htmlspecialchars($book['auteur']); ?></p>
        <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($book['maison_edition']); ?></p>
        <p><strong>Exemplaires disponibles:</strong> <?php echo $book['nombre_exemplaire']; ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
    </div>
    
    <?php if (isset($success_message)): ?>
        <div class="alert success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <button type="submit" name="add_to_wishlist" class="btn">Ajouter à ma liste de lecture</button>
    </form>
    
    <a href="results.php" class="btn secondary">Retour à la recherche</a>
</section>

<?php include 'includes/footer.php'; ?>