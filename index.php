<?php
include 'includes/database.php';
include 'includes/functions.php';

$listes = meilleuresLivres($pdo);
?>

<?php include 'includes/header.php'; ?>


<section class="hero" style="margin-top: 100px;">
    <div class="hero-content" >
        <div class="hero-text">
            <h2>Bienvenue dans notre bibliothèque en ligne</h2>
            <p>Découvrez, explorez et empruntez des livres de notre vaste collection. Plus de 10,000 livres disponibles pour tous les goûts.</p>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Livres disponibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Auteurs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Accès en ligne</div>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/Img/telephone.svg" alt="Personne tenant un telephone" />
        </div>
    </div>
</section>

<section class="search-section">
    <h3><i class="fa-solid fa-magnifying-glass"></i> Rechercher un livre</h3>
    <form action="results.php" method="GET">
        <input type="text" name="titre" placeholder="Rechercher par titre ou description..." required>
        <button type="submit" name="search">
            <span><i class="fa-solid fa-magnifying-glass"></i> Rechercher</span>
        </button>
    </form>
</section>

<section class="instructions">
    <h3><i class="fa-solid fa-circle-info"></i> Comment utiliser notre bibliothèque</h3>
    <ol>
        <li><strong>Recherchez</strong> - Utilisez la barre de recherche pour trouver des livres par titre, auteur ou description</li>
        <li><strong>Explorez</strong> - Consultez les détails complets d'un livre en cliquant sur son titre</li>
        <li><strong>Sauvegardez</strong> - Ajoutez des livres à votre liste de lecture pour les retrouver facilement</li>
        <li><strong>Gérez</strong> - Organisez votre liste de lecture depuis votre espace personnel</li>
    </ol>
</section>

<section class="featured-books">
    <h3><i class="fa-solid fa-star"></i> Livres populaires</h3>
    <br>
    <div class="books-grid">
        <?php foreach ($listes as $liste): ?>
            <div class="book-card">
                <div class="book-cover">Couverture du livre</div>
                <h3><?php echo htmlspecialchars($liste['titre']); ?></h3>
                <p><strong>Auteur:</strong> <?php echo htmlspecialchars($liste['auteur']); ?></p>
                <p><strong>Disponible:</strong> <?php echo $liste['nombre_exemplaire']; ?> exemplaires</p>
                <a href="details.php?id=<?php echo $liste['id']; ?>" class="btn">Voir les détails</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>