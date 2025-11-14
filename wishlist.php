<?php
include 'includes/database.php';
include 'includes/functions.php';

// Dans un cas réel, vous récupéreriez l'ID du lecteur connecté
$id_lecteur = 1; // ID temporaire pour la démonstration

// Traitement de la suppression d'un livre
if (isset($_POST['supprimer'])) {
    $id_livre = intval($_POST['livre_id']);
    if (SupprimerListe($pdo, $id_livre, $id_lecteur)) {
        $success_message = "Livre retiré de votre liste de lecture!";
    } else {
        $error_message = "Erreur lors du retrait de la liste de lecture.";
    }
}

$livres = MaListe($pdo, $id_lecteur);
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
    
    <?php if (empty($livres)): ?>
        <p>Votre liste de lecture est vide.</p>
    <?php else: ?>
        <div class="books-grid advanced-search-form">
            <?php foreach ($livres as $livre): ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                    <p><strong>Auteur:</strong> <?php echo htmlspecialchars($livre['auteur']); ?></p>
                    <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($livre['maison_edition']); ?></p>
                    <p><strong>Date d'emprunt:</strong> <?php echo $livre['date_emprunt']; ?></p>
                    
                    <form method="POST" class="remove-form">
                        <input type="hidden" name="livre_id" value="<?php echo $livre['id']; ?>">
                        <button type="submit" name="supprimer" class="btn danger">Retirer de ma liste</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>