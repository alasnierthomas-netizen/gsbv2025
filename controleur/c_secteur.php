<?php

include_once __DIR__ . '/../modele/statistiqueSecteur.modele.inc.php';
include_once __DIR__ . '/../modele/medicament.modele.inc.php';

if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
	$action = "mesrapports";
} else {
	$action = $_REQUEST['action'];
}
switch ($action) {

        case 'mesrapports':{
            $medicaments = getAllNomMedicament();
            $medicamentPresenter = medicamentPresenter();
			$medicamentOffert = medicamentOffert();
            include 'vues/v_statistiqueSecteur.php';
            break;
        }

        // Rechercher avec filtres
        case 'rechercher':{
			$medicaments = getAllNomMedicament();
            $medicamentPresenter = medicamentPresenterFiltre(empty($_REQUEST['dateDebut'])? null : $_REQUEST['dateDebut'], empty($_REQUEST["dateFin"])? null : $_REQUEST["dateFin"], empty($_REQUEST["medicament"])? null : $_REQUEST["medicament"]);
            $medicamentOffert = medicamentOffertFiltre(empty($_REQUEST['dateDebut'])? null : $_REQUEST['dateDebut'], empty($_REQUEST["dateFin"])? null : $_REQUEST["dateFin"], empty($_REQUEST["medicament"])? null : $_REQUEST["medicament"]);
			include 'vues/v_statistiqueSecteur.php';
            break;
        }


	default: {

			header('Location: index.php?uc=medicaments&action=mesrapports');
			break;
		}
}
?>