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

<section class="book-details" style="margin-top: 5rem;">
    <h2><?php echo htmlspecialchars($livre['titre']); ?></h2>

    <div class="book-info">
        <p><strong>Auteur:</strong> <?php echo htmlspecialchars($livre['auteur']); ?></p>
        <p><strong>Éditeur:</strong> <?php echo htmlspecialchars($livre['maison_edition']); ?></p>
        <p><strong>Exemplaires disponibles:</strong> <?php echo $livre['nombre_exemplaire']; ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($livre['description'])); ?></p>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" name="ajouter_ma_liste" class="btn">Ajouter à ma liste de lecture</button>
    </form>

    <a href="index.php" class="btn secondary">Retour</a>
</section>

<?php include 'includes/footer.php'; ?>