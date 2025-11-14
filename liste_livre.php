<?php
// Page de recherche avancée
include 'includes/database.php';
include 'includes/functions.php';


$listes = listeLivres($pdo);
?>

<?php include 'includes/header.php'; ?>
<br>
<section class="search-advanced" style="margin-top: 5rem;">
    <h2 style="color: #3498db;"> <i class="fa-solid fa-list"></i> Liste des livres</h2>
    <br>

        <div class="search-results">
            <h3>Nombre de livres : <?php echo count($listes); ?></h3>
            <br>
            <?php if (empty($listes)): ?>
                <p>Aucun livre trouvé.</p>
            <?php else: ?>
                <div class="books-grid">
                    <?php foreach ($listes as $liste): ?>
                        <div class="book-card">
                            <h3><?php echo htmlspecialchars($liste['titre']); ?></h3>
                            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($liste['auteur']); ?></p>
                            <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($liste['maison_edition']); ?></p>
                            <p><strong>Exemplaires disponibles:</strong> <?php echo $liste['nombre_exemplaire']; ?></p>
                            <p class="book-description"><?php echo nl2br(htmlspecialchars(substr($liste['description'], 0, 150) . '...')); ?></p>
                            <a href="details.php?id=<?php echo $liste['id']; ?>" class="btn">Voir les détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
</section>

<?php include 'includes/footer.php'; ?>