<?php
require_once 'modele/bd.inc.php';

function medicamentPresenter() {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT COUNT(*)
                FROM rapport_visite
                WHERE rapport_visite.MED_DEPOTLEGAL_PRES1 != null || rapport_visite.MED_DEPOTLEGAL_PRES2 != null';
        $res = $monPdo->query($req);
        return $res->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

function medicamentOffert() {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT SUM(offrir.OFF_QTE)
                FROM offrir';
        $res = $monPdo->query($req);
        return $res->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

function medicamentPresenterFiltre($dateDebut, $dateFin, $medicament) {
    $pdo = connexionPDO();

    $req = "SELECT COUNT(*)
            FROM rapport_visite
            WHERE true";

    if (!empty($dateDebut)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE >= date(:dateDebut)";
    }
    if (!empty($dateFin)) {
        $req .= " AND rapport_visite.RAP_DATEVISITE <= date(:dateFin)";
    }
    if (!empty($medicament)) {
        $req .= " AND rapport_visite.MED_DEPOTLEGAL_PRES1 = :medicament OR rapport_visite.MED_DEPOTLEGAL_PRES2 = :medicament";
    }

    $stmt = $pdo->prepare($req);  // Requête préparée pour éviter les injections SQL
    if (!empty($dateDebut)) $stmt->bindParam(':dateDebut', $dateDebut);
    if (!empty($dateFin)) $stmt->bindParam(':dateFin', $dateFin);
    if (!empty($medicament)) $stmt->bindParam(':medicament', $medicament);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function medicamentOffertFiltre($dateDebut, $dateFin, $medicament) {
    $pdo = connexionPDO();

    $req = "SELECT SUM(offrir.OFF_QTE)
            FROM offrir
            JOIN rapport_visite ON offrir.RAP_NUM = rapport_visite.RAP_NUM
            WHERE true";

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
    if (!empty($dateDebut)) $stmt->bindParam(':dateDebut', $dateDebut);
    if (!empty($dateFin)) $stmt->bindParam(':dateFin', $dateFin);
    if (!empty($medicament)) $stmt->bindParam(':medicament', $medicament);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>