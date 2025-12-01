<?php
require_once 'modele/bd.inc.php';


function getMedicamentPresenter($secteur) {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT medicament, COUNT(*) AS nb_citations
                FROM (
                    SELECT MED_DEPOTLEGAL_PRES1 AS medicament
                    FROM rapport_visite
                    JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
                    JOIN region ON collaborateur.REG_CODE = region.REG_CODE
                    WHERE MED_DEPOTLEGAL_PRES1 IS NOT NULL AND region.SEC_CODE = :secteur

                    UNION ALL

                    SELECT MED_DEPOTLEGAL_PRES2 AS medicament
                    FROM rapport_visite
                    JOIN collaborateur ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
                    JOIN region ON collaborateur.REG_CODE = region.REG_CODE
                    WHERE MED_DEPOTLEGAL_PRES2 IS NOT NULL  AND region.SEC_CODE = :secteur
                ) AS meds
                GROUP BY medicament';
        $stmt = $monPdo->prepare($req);
        $stmt->bindParam(':secteur', $secteur[0]);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

function getMedicamentOffert($secteur) {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT 
        offrir.MED_DEPOTLEGAL AS medicament,
        SUM(offrir.OFF_QTE) AS qte_totale_offerte
        FROM offrir
        JOIN rapport_visite 
            ON offrir.RAP_NUM = rapport_visite.RAP_NUM
        AND offrir.COL_MATRICULE = rapport_visite.COL_MATRICULE
        JOIN collaborateur 
            ON rapport_visite.COL_MATRICULE = collaborateur.COL_MATRICULE
        JOIN region 
            ON collaborateur.REG_CODE = region.REG_CODE
        WHERE region.SEC_CODE = :secteur
        GROUP BY offrir.MED_DEPOTLEGAL';
                
        $stmt = $monPdo->prepare($req);
        $stmt->bindParam(':secteur', $secteur[0]);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

function sommeMedicamentPresenter($secteur) {
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

function sommeMedicamentOffert($secteur) {
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

function sommeMedicamentPresenterFiltre($secteur, $dateDebut, $dateFin, $medicament) {
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

function sommeMedicamentOffertFiltre($secteur, $dateDebut, $dateFin, $medicament) {
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