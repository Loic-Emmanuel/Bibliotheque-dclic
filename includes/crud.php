<?php

/**
 * CRÉER - Ajouter un nouveau livre
 */
function createBook($pdo, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire) {
    try {
        $sql = "INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) 
                VALUES (:titre, :auteur, :description, :maison_edition, :nombre_exemplaire)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'titre' => $titre,
            'auteur' => $auteur,
            'description' => $description,
            'maison_edition' => $maison_edition,
            'nombre_exemplaire' => $nombre_exemplaire
        ]);
        
        return $pdo->lastInsertId();
    } catch(PDOException $e) {
        error_log("Erreur création livre: " . $e->getMessage());
        return false;
    }
}

/**
 * LIRE - Récupérer tous les livres
 */
function readAllBooks($pdo) {
    try {
        $sql = "SELECT * FROM livres ORDER BY titre";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Erreur lecture livres: " . $e->getMessage());
        return [];
    }
}

/**
 * LIRE - Récupérer un livre par son ID
 */
function readBookById($pdo, $id) {
    try {
        $sql = "SELECT * FROM livres WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Erreur lecture livre ID $id: " . $e->getMessage());
        return false;
    }
}

/**
 * METTRE À JOUR - Modifier un livre
 */
function updateBook($pdo, $id, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire) {
    try {
        $sql = "UPDATE livres 
                SET titre = :titre, auteur = :auteur, description = :description, 
                    maison_edition = :maison_edition, nombre_exemplaire = :nombre_exemplaire 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'titre' => $titre,
            'auteur' => $auteur,
            'description' => $description,
            'maison_edition' => $maison_edition,
            'nombre_exemplaire' => $nombre_exemplaire
        ]);
    } catch(PDOException $e) {
        error_log("Erreur mise à jour livre ID $id: " . $e->getMessage());
        return false;
    }
}

/**
 * SUPPRIMER - Supprimer un livre
 */
function deleteBook($pdo, $id) {
    try {
        // D'abord supprimer les références dans liste_lecture
        $sql_delete_ref = "DELETE FROM liste_lecture WHERE id_livre = :id";
        $stmt_ref = $pdo->prepare($sql_delete_ref);
        $stmt_ref->execute(['id' => $id]);
        
        // Puis supprimer le livre
        $sql = "DELETE FROM livres WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    } catch(PDOException $e) {
        error_log("Erreur suppression livre ID $id: " . $e->getMessage());
        return false;
    }
}

/**
 * Recherche avancée de livres
 */
function RechercheLivres($pdo, $titre, $auteur = '', $editeur = '') {
    try {
        $sql = "SELECT * FROM livres WHERE 1=1";
        $params = [];
        
        if (!empty($titre)) {
            $sql .= " AND (titre LIKE :titre OR description LIKE :titre)";
            $params['titre'] = "%$titre%";
        }
        
        if (!empty($auteur)) {
            $sql .= " AND auteur LIKE :auteur";
            $params['auteur'] = "%$auteur%";
        }
        
        if (!empty($editeur)) {
            $sql .= " AND maison_edition LIKE :editeur";
            $params['editeur'] = "%$editeur%";
        }
        
        $sql .= " ORDER BY titre";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Erreur recherche avancée: " . $e->getMessage());
        return [];
    }
}

function listeLivres($pdo) {
    try {
        $sql = "SELECT * FROM livres ORDER BY titre";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Erreur liste livres: " . $e->getMessage());
        return [];
    }
}

/**
 * Compter le nombre total de livres
 */
function countBooks($pdo) {
    try {
        $sql = "SELECT COUNT(*) as total FROM livres";
        $stmt = $pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch(PDOException $e) {
        error_log("Erreur comptage livres: " . $e->getMessage());
        return 0;
    }
}

/**
 * Récupérer les livres avec pagination
 */
function getBooksPaginated($pdo, $limit, $offset) {
    try {
        $sql = "SELECT * FROM livres ORDER BY titre LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Erreur pagination livres: " . $e->getMessage());
        return [];
    }
}
?>