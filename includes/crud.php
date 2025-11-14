<?php

// Ajouter un nouveau livre
function creerLivre($pdo, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire) {
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

// Récupérer tous les livres
function listeLivresCree($pdo) {
    try {
        $sql = "SELECT * FROM livres ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Erreur lecture livres: " . $e->getMessage());
        return [];
    }
}

// Récupérer un livre par son ID
function recupererLivre($pdo, $id) {
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

// Modifier un livre
function modifierLivre($pdo, $id, $titre, $auteur, $description, $maison_edition, $nombre_exemplaire) {
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

// Supprimer un livre
function supprimerLivre($pdo, $id) {
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

?>