<?php
include 'includes/database.php';
include 'includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$livre_id = intval($_GET['id']);

$livre = detailsLivre($pdo, $livre_id);

if (!$livre) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['ajouter_ma_liste'])) {

    // Lecteur par défaut = Yao Allou Loic Emmanuel
    $id_lecteur = 1;

    $result = liste_lecture($pdo, $livre_id, $id_lecteur);

    if ($result === true) {
        $success_message = "Livre ajouté à votre liste de lecture !";
    } elseif ($result === "existe") {
        $error_message = "Ce livre est déjà dans votre liste de lecture.";
    } else {
        $error_message = "Erreur lors de l'ajout à la liste de lecture.";
    }
}

?>

<?php include 'includes/header.php'; ?>
<h2 style="margin-top: 5rem;">Détails du livre</h2>

<section class="book-details" style="margin-bottom: 5rem;">
    <h2><?php echo htmlspecialchars($livre['titre']); ?></h2>
    <br>
    <div class="book-info" style="display: flex; align-items: flex-start; justify-content: space-between; gap: 40px;">
        <div style="flex: 1;">
            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($livre['auteur']); ?></p>
            <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($livre['maison_edition']); ?></p>
            <p><strong>Exemplaires disponibles:</strong> <?php echo $livre['nombre_exemplaire']; ?></p>
            <p><strong>Description:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($livre['description'])); ?></p>
        </div>
        <div style="flex-shrink: 0;">
            <?php
            $image_path = 'images/' . $livre['image'];
            $image_exists = !empty($livre['image']) && file_exists($image_path);
            ?>

            <?php if ($image_exists): ?>
                <img src="images/<?php echo htmlspecialchars($livre['image']); ?>"
                    alt="Couverture de <?php echo htmlspecialchars($livre['titre']); ?>"
                    style="width: 250px; height: 350px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <?php endif; ?>

            <div class="default-cover" style="display: <?php echo $image_exists ? 'none' : 'flex'; ?>; 
                        width: 250px; height: 350px; background: linear-gradient(135deg, #868997ff 0%, #968aa1ff 100%); 
                        flex-direction: column; align-items: center; justify-content: center; color: white; 
                        border-radius: 8px; font-weight: bold; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-book" style="font-size: 3rem; margin-bottom: 15px;"></i>
                <span style="font-size: 1.5rem; text-transform: uppercase;">
                    <?php echo substr(htmlspecialchars($livre['titre']), 0, 2); ?>
                </span>
                <p style="font-size: 0.9rem; margin-top: 10px; font-weight: normal; text-align: center;">
                    Pas de couverture
                </p>
            </div>
        </div>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" style="margin-top: 20px;">
        <button type="submit" name="ajouter_ma_liste" class="btn">
            <i class="fa-solid fa-plus"></i> Ajouter à ma liste de lecture
        </button>
    </form>

    <a href="index.php" class="btn secondary" style="margin-top: 10px;">
        <i class="fa-solid fa-arrow-left"></i> Retour à l'accueil
    </a>
</section>

<?php include 'includes/footer.php'; ?>