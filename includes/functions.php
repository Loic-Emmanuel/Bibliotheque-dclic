<?php
/**
 * Fonctions utilitaires pour le site de présentation
 */

// Fonction pour obtenir les détails d'un livre
function detailsLivre($pdo, $id)
{
    $sql = "SELECT * FROM livres WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour ajouter un livre à la liste de lecture
function liste_lecture($pdo, $id_livre, $id_lecteur)
{
    // Vérifier si déjà ajouté
    $verifier = $pdo->prepare("SELECT 1 FROM liste_lecture WHERE id_livre = :id_livre AND id_lecteur = :id_lecteur");
    $verifier->execute(['id_livre' => $id_livre, 'id_lecteur' => $id_lecteur]);

    if ($verifier->fetch()) {
        return "existe";
    }

    // Sinon on l'ajoute
    $sql = "INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt)
            VALUES (:id_livre, :id_lecteur, CURDATE())";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute(['id_livre' => $id_livre, 'id_lecteur' => $id_lecteur]);
}

// Fonction pour obtenir la liste de lecture d'un lecteur
function MaListe($pdo, $id_lecteur)
{
    $sql = "SELECT l.*, ll.date_emprunt, ll.date_retour 
            FROM liste_lecture ll 
            JOIN livres l ON ll.id_livre = l.id 
            WHERE ll.id_lecteur = :id_lecteur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_lecteur' => $id_lecteur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour retirer un livre de la liste de lecture
function SupprimerListe($pdo, $id_livre, $id_lecteur)
{
    $sql = "DELETE FROM liste_lecture WHERE id_livre = :id_livre AND id_lecteur = :id_lecteur";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id_livre' => $id_livre, 'id_lecteur' => $id_lecteur]);
}

// Fonction pour obtenir les meilleurs livres en fonction du nombre ajouté dans la liste de lecture
function meilleuresLivres($pdo)
{
    $sql = "SELECT li.id, li.titre, li.auteur, li.nombre_exemplaire,
       COUNT(lil.id_livre) AS nombre_ajouts
        FROM livres li
        JOIN liste_lecture lil ON lil.id_livre = li.id
        GROUP BY li.id, li.titre, li.auteur, li.nombre_exemplaire
        ORDER BY nombre_ajouts DESC
        LIMIT 3;";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir la liste de tous les livres
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

// Recherche de livres
function rechercheLivres($pdo, $titre, $auteur = '', $editeur = '') {
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
