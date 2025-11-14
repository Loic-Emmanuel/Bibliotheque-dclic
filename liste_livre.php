<?php
include 'includes/database.php';
include 'includes/functions.php';


$listes = listeLivres($pdo);
?>

<?php include 'includes/header.php'; ?>
<br>
<section class="search-section" style="margin-top: 5rem;">
    <h3><i class="fa-solid fa-magnifying-glass"></i> Rechercher un livre</h3>
    <form action="results.php" method="GET">
        <input type="text" name="titre" placeholder="Rechercher par titre ou description..." required>
        <button type="submit" name="search">
            <span><i class="fa-solid fa-magnifying-glass"></i> Rechercher</span>
        </button>
    </form>
</section>
<section class="search-advanced">
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
                            <div class="book-cover">
                                <?php 
                                $image_path = 'images/' . $liste['image'];
                                $image_exists = !empty($liste['image']) && file_exists($image_path);
                                ?>
                                
                                <?php if ($image_exists): ?>
                                    <img src="images/<?php echo htmlspecialchars($liste['image']); ?>" 
                                         alt="Couverture de <?php echo htmlspecialchars($liste['titre']); ?>"
                                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <?php endif; ?>
                                
                                <div class="default-cover" style="display: <?php echo $image_exists ? 'none' : 'flex'; ?>; 
                                            width: 100%; height: 200px; background: linear-gradient(135deg, #868997ff 0%, #968aa1ff 100%); 
                                            flex-direction: column; align-items: center; justify-content: center; color: white; 
                                            border-radius: 8px; font-weight: bold;">
                                    <i class="fa-solid fa-book" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                    <span style="font-size: 1.2rem; text-transform: uppercase;">
                                        <?php echo substr(htmlspecialchars($liste['titre']), 0, 2); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <h3><?php echo htmlspecialchars($liste['titre']); ?></h3>
                            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($liste['auteur']); ?></p>
                            <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($liste['maison_edition']); ?></p>
                            <p><strong>Exemplaires disponibles:</strong> <?php echo $liste['nombre_exemplaire']; ?></p>
                            <p class="book-description">
                                <?php 
                                $description = !empty($liste['description']) ? $liste['description'] : 'Aucune description disponible.';
                                echo nl2br(htmlspecialchars(substr($description, 0, 150) . (strlen($description) > 150 ? '...' : ''))); 
                                ?>
                            </p>
                            <a href="details.php?id=<?php echo $liste['id']; ?>" class="btn">Voir les détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
</section>

<?php include 'includes/footer.php'; ?>