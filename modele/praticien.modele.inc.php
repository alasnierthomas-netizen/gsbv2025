<?php

// Permet de réaliser des requêtes pour récupérer les informations nécessaires

// Chargement de la connexion PDO
include_once 'bd.inc.php';

/**
 * Récupère la liste de tous les praticiens
 */
function getAllNomPraticien() {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT PRA_NUM, PRA_PRENOM, PRA_NOM FROM praticien ORDER BY PRA_NUM';
        $res = $monPdo->query($req);
        return $res->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

/**
 * Récupère les informations détaillées d’un praticien quand on clique sur le bouton afficher les informations
 */
function getAllInformationPraticien($num) {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT p.PRA_NUM, p.PRA_NOM, p.PRA_PRENOM, p.PRA_ADRESSE, p.PRA_CP, 
                       p.PRA_VILLE, p.PRA_COEFNOTORIETE, t.TYP_LIBELLE
                FROM praticien p
                INNER JOIN type_praticien t ON t.TYP_CODE = p.TYP_CODE
                WHERE p.PRA_NUM = :num';
        $stmt = $monPdo->prepare($req);  // Requête préparée
        $stmt->bindValue(':num', $num, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>