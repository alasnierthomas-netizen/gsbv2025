<?php
require_once 'modele/bd.inc.php';

function medicamentPresenter($secteur) {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT COUNT(*)
            FROM rapport_visite
            JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
            JOIN region ON collaborateur.REG_CODE = region.REG_CODE
            WHERE (rapport_visite.MED_DEPOTLEGAL_PRES1 IS NOT NULL OR rapport_visite.MED_DEPOTLEGAL_PRES2 IS NOT NULL) AND region.SEC_CODE = :secteur';
        $stmt = $monPdo->prepare($req);
        $stmt->bindParam(':secteur', $secteur[0]);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

function medicamentOffert($secteur) {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT SUM(offrir.OFF_QTE)
                FROM offrir
                JOIN rapport_visite ON offrir.RAP_NUM = rapport_visite.RAP_NUM
                JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
                JOIN region ON collaborateur.REG_CODE = region.REG_CODE
                WHERE region.SEC_CODE = :secteur';
                
        $stmt = $monPdo->prepare($req);
        $stmt->bindParam(':secteur', $secteur[0]);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

function medicamentPresenterFiltre($secteur, $dateDebut, $dateFin, $medicament) {
    $pdo = connexionPDO();

    $req = "SELECT COUNT(*)
            FROM rapport_visite
            JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
            JOIN region ON collaborateur.REG_CODE = region.REG_CODE
            WHERE region.SEC_CODE = :secteur AND (rapport_visite.MED_DEPOTLEGAL_PRES1 IS NOT NULL OR rapport_visite.MED_DEPOTLEGAL_PRES2 IS NOT NULL)";

    if (!empty($dateDebut)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE >= date(:dateDebut)";
    }
    if (!empty($dateFin)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE <= date(:dateFin)";
    }
    if (!empty($medicament)) {
        $req .= " AND (rapport_visite.MED_DEPOTLEGAL_PRES1 = :medicament OR rapport_visite.MED_DEPOTLEGAL_PRES2 = :medicament)";
    }
    
    $stmt = $pdo->prepare($req);  // Requête préparée pour éviter les injections SQL
    $stmt->bindParam(':secteur', $secteur[0]);
    if (!empty($dateDebut)) $stmt->bindParam(':dateDebut', $dateDebut);
    if (!empty($dateFin)) $stmt->bindParam(':dateFin', $dateFin);
    if (!empty($medicament)) $stmt->bindParam(':medicament', $medicament);

    $stmt->execute();
    return $stmt->fetchAll();
}

function medicamentOffertFiltre($secteur, $dateDebut, $dateFin, $medicament) {
    $pdo = connexionPDO();

    $req = "SELECT SUM(offrir.OFF_QTE)
            FROM offrir
            JOIN rapport_visite ON offrir.RAP_NUM = rapport_visite.RAP_NUM
            JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
            JOIN region ON collaborateur.REG_CODE = region.REG_CODE
            WHERE region.SEC_CODE = :secteur";

    if (!empty($dateDebut)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE >= date(:dateDebut)";
    }
    if (!empty($dateFin)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE <= date(:dateFin)";
    }
    if (!empty($medicament)) {
        $req .= " AND offrir.MED_DEPOTLEGAL = :medicament";
    }

    $stmt = $pdo->prepare($req);  // Requête préparée pour éviter les injections SQL
    $stmt->bindParam(':secteur', $secteur[0]);
    if (!empty($dateDebut)) $stmt->bindParam(':dateDebut', $dateDebut);
    if (!empty($dateFin)) $stmt->bindParam(':dateFin', $dateFin);
    if (!empty($medicament)) $stmt->bindParam(':medicament', $medicament);

    $stmt->execute();
    return $stmt->fetchAll();
}
?>