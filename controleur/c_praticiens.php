<?php

//  Modèle praticien
include_once __DIR__ . '/../modele/praticien.modele.inc.php';

// Quelle est l'action
if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
    $action = "formulairepraticien";
} else {
    $action = $_REQUEST['action'];
}

// En fonction de l’action
switch ($action) {

    // Affiche la liste déroulante des praticiens
    case 'formulairepraticien': {
        $result = getAllNomPraticien();
        include("vues/v_formulairePraticien.php");
        break;
    }

    // Affiche les infos du praticien sélectionné en cliquant sur le bouton afficher les informations
    case 'afficherpraticien': {
        if (isset($_REQUEST['praticien']) && $_REQUEST['praticien'] != "") {
            $pra = $_REQUEST['praticien'];
            $info = getAllInformationPraticien($pra);
            include("vues/v_afficherPraticien.php");
        } else {
            $_SESSION['erreur'] = true;
            header("Location: index.php?uc=praticiens&action=formulairepraticien");
        }
        break;
    }

    // Renvoie vers le formulaire des praticiens sinon
    default: {
        header('Location: index.php?uc=praticiens&action=formulairepraticien');
        break;
    }
}
?>