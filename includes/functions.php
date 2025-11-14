<?php
// Fonctions utilitaires pour le site

// Fonction pour rechercher des livres
function searchBooks($pdo, $query) {
    $sql = "SELECT * FROM livres WHERE titre LIKE :query OR auteur LIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => "%$query%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir les détails d'un livre
function getBookDetails($pdo, $id) {
    $sql = "SELECT * FROM livres WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour ajouter un livre à la liste de lecture
function addToWishlist($pdo, $id_livre, $id_lecteur) {
    $sql = "INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (:id_livre, :id_lecteur, CURDATE())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id_livre' => $id_livre, 'id_lecteur' => $id_lecteur]);
}

// Fonction pour obtenir la liste de lecture d'un utilisateur
function getWishlist($pdo, $id_lecteur) {
    $sql = "SELECT l.*, ll.date_emprunt, ll.date_retour 
            FROM liste_lecture ll 
            JOIN livres l ON ll.id_livre = l.id 
            WHERE ll.id_lecteur = :id_lecteur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_lecteur' => $id_lecteur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour retirer un livre de la liste de lecture
function removeFromWishlist($pdo, $id_livre, $id_lecteur) {
    $sql = "DELETE FROM liste_lecture WHERE id_livre = :id_livre AND id_lecteur = :id_lecteur";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id_livre' => $id_livre, 'id_lecteur' => $id_lecteur]);
}
?>