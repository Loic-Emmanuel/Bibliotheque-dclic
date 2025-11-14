<?php
include 'includes/database.php';
include 'includes/functions.php';

// Dans un cas réel, vous récupéreriez l'ID du lecteur connecté
$id_lecteur = 1; // ID temporaire pour la démonstration

// Traitement de la suppression d'un livre
if (isset($_POST['remove_from_wishlist'])) {
    $id_livre = intval($_POST['book_id']);
    if (removeFromWishlist($pdo, $id_livre, $id_lecteur)) {
        $success_message = "Livre retiré de votre liste de lecture!";
    } else {
        $error_message = "Erreur lors du retrait de la liste de lecture.";
    }
}

$wishlist = getWishlist($pdo, $id_lecteur);
?>

<?php include 'includes/header.php'; ?>

<section class="wishlist" style="margin: 5rem 0;">
    <h2 style="color: #3498db;"> <i class="fa-solid fa-list"></i> Ma liste de lecture</h2>
    <br>
    <?php if (isset($success_message)): ?>
        <div class="alert success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <?php if (empty($wishlist)): ?>
        <p>Votre liste de lecture est vide.</p>
    <?php else: ?>
        <div class="books-grid advanced-search-form">
            <?php foreach ($wishlist as $book): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($book['titre']); ?></h3>
                    <p><strong>Auteur:</strong> <?php echo htmlspecialchars($book['auteur']); ?></p>
                    <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($book['maison_edition']); ?></p>
                    <p><strong>Date d'emprunt:</strong> <?php echo $book['date_emprunt']; ?></p>
                    
                    <form method="POST" class="remove-form">
                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                        <button type="submit" name="remove_from_wishlist" class="btn danger">Retirer de ma liste</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>