<?php
include 'includes/database.php';
include 'includes/functions.php';

$id_lecteur = 1;

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
                    <div class="book-cover">
                        <?php 
                        $image_path = 'images/' . $livre['image'];
                        $image_exists = !empty($livre['image']) && file_exists($image_path);
                        ?>
                        
                        <?php if ($image_exists): ?>
                            <img src="images/<?php echo htmlspecialchars($livre['image']); ?>" 
                                 alt="Couverture de <?php echo htmlspecialchars($livre['titre']); ?>"
                                 style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <?php endif; ?>
                        
                        <div class="default-cover" style="display: <?php echo $image_exists ? 'none' : 'flex'; ?>; 
                                    width: 100%; height: 200px; background: linear-gradient(135deg, #868997ff 0%, #968aa1ff 100%); 
                                    flex-direction: column; align-items: center; justify-content: center; color: white; 
                                    border-radius: 8px; font-weight: bold;">
                            <i class="fa-solid fa-book" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <span style="font-size: 1.2rem; text-transform: uppercase;">
                                <?php echo substr(htmlspecialchars($livre['titre']), 0, 2); ?>
                            </span>
                        </div>
                    </div>
                    
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