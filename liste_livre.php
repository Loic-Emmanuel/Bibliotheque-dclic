<?php
// Page de recherche avancée
include 'includes/database.php';
include 'includes/crud.php';


$listes = listeLivres($pdo);

?>

<?php include 'includes/header.php'; ?>
<br>
<section class="search-advanced" style="margin-top: 5rem;">
    <h2 style="color: #3498db;"> <i class="fa-solid fa-list"></i> Rechercher un livre</h2>
    <br>

    <form method="GET" class="advanced-search-form">
        <div class="form-grid">
            <div class="form-group">
                <label for="titre">titre ou description</label>
                <input type="text" id="titre" name="titre"
                    value="<?php echo htmlspecialchars($titre); ?>"
                    placeholder="Rechercher par titre ou description...">
            </div>

            <div class="form-group">
                <label for="auteur">Auteur</label>
                <input type="text" id="auteur" name="auteur"
                    value="<?php echo htmlspecialchars($auteur); ?>"
                    placeholder="Filtrer par auteur...">
            </div>

            <div class="form-group">
                <label for="editeur">Éditeur</label>
                <input type="text" id="editeur" name="editeur"
                    value="<?php echo htmlspecialchars($editeur); ?>"
                    placeholder="Filtrer par éditeur...">
            </div>
        </div>

        <button type="submit" name="search" class="btn">Rechercher</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])): ?>
        <div class="search-results">
            <h3>Résultats de la recherche (<?php echo count($results); ?>)</h3>
            <br>
            <?php if (empty($results)): ?>
                <p>Aucun livre trouvé pour votre recherche.</p>
            <?php else: ?>
                <div class="books-grid">
                    <?php foreach ($results as $book): ?>
                        <div class="book-card">
                            <h3><?php echo htmlspecialchars($book['titre']); ?></h3>
                            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($book['auteur']); ?></p>
                            <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($book['maison_edition']); ?></p>
                            <p><strong>Exemplaires disponibles:</strong> <?php echo $book['nombre_exemplaire']; ?></p>
                            <p class="book-description"><?php echo nl2br(htmlspecialchars(substr($book['description'], 0, 150) . '...')); ?></p>
                            <a href="details.php?id=<?php echo $book['id']; ?>" class="btn">Voir les détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>