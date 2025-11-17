<?php
require_once 'modele/bd.inc.php';

/**
 * Récupère tous les praticiens (pour la liste déroulante)
 */
/*
function getAllPraticiens() {
    $pdo = connexionPDO();
    $req = "SELECT PRA_NUM, CONCAT(PRA_PRENOM, ' ', PRA_NOM) AS nomComplet
            FROM praticien
            ORDER BY PRA_NOM";
    $stmt = $pdo->query($req);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
*/

function getMotif() {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT MOT_CODE, MOT_LIB FROM Motif';
        $res = $monPdo->query($req);
        return $res->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

/**
 * Récupère tous les rapports du visiteur (avec praticien, motif, médicament)
 */
function getAllRapportsVisiteDetails() {
    $pdo = connexionPDO();

    $req = "SELECT rapport_visite.RAP_NUM, rapport_visite.RAP_DATEVISITE, praticien.PRA_NOM, praticien.PRA_PRENOM, collaborateur.COL_NOM, collaborateur.COL_PRENOM, rapport_visite.RAP_BILAN, rapport_visite.MED_DEPOTLEGAL_PRES1
            FROM rapport_visite
            JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
            JOIN praticien ON rapport_visite.PRA_NUM = praticien.PRA_NUM";

    $res = $pdo->query($req);  // Requête préparée pour éviter les injections SQL
    return $res->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Recherche filtrée, permettant d'entrer une date début, date fin et praticien
 */
function getRapportsVisiteFiltres($dateDebut, $dateFin, $medicament) {
    $pdo = connexionPDO();

    $req = "SELECT rapport_visite.RAP_NUM, rapport_visite.RAP_DATEVISITE, praticien.PRA_NOM, praticien.PRA_PRENOM, collaborateur.COL_NOM, collaborateur.COL_PRENOM, rapport_visite.RAP_BILAN, rapport_visite.MED_DEPOTLEGAL_PRES1
            FROM rapport_visite
            JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
            JOIN praticien ON rapport_visite.PRA_NUM = praticien.PRA_NUM
            WHERE true";

    if (!empty($dateDebut)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE >= date(:dateDebut)";
    }
    if (!empty($dateFin)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE <= date(:dateFin)";
    }
    if (!empty($medicament)) {
        $req .= " AND praticien.PRA_NUM = :medicament";
    }

    $req .= " ORDER BY rapport_visite.RAP_DATEVISITE DESC";

    $stmt = $pdo->prepare($req);  // Requête préparée pour éviter les injections SQL
    if (!empty($dateDebut)) $stmt->bindParam(':dateDebut', $dateDebut);
    if (!empty($dateFin)) $stmt->bindParam(':dateFin', $dateFin);
    if (!empty($medicament)) $stmt->bindParam(':medicament', $medicament);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>