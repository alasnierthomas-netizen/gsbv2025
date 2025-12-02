<?php

include_once __DIR__ . '/../modele/statistiqueSecteur.modele.inc.php';
include_once __DIR__ . '/../modele/medicament.modele.inc.php';
include_once __DIR__ . '/../modele/collaborateur.modele.inc.php';

if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
	$action = "statistiqueSecteur";
} else {
	$action = $_REQUEST['action'];
}
switch ($action) {

        case 'statistiqueSecteur':{
            $secteur = getSecteur($_SESSION['matricule']);
            $medicaments = getAllNomMedicament();
            $medicamentPresenterOuOffert = getMedicamentPresenterOuOffert($secteur);
            $sommeMedicamentPresenter = sommeMedicamentPresenter($secteur);
			$sommeMedicamentOffert = sommeMedicamentOffert($secteur);
            include 'vues/v_statistiqueSecteur.php';
            break;
        }

        // Rechercher avec filtres
        case 'rechercher':{
            $secteur = getSecteur($_SESSION['matricule']);
			$medicaments = getAllNomMedicament();
            $medicamentPresenterOuOffert = getMedicamentPresenterOuOffertFiltre($secteur, empty($_REQUEST['dateDebut'])? null : $_REQUEST['dateDebut'], empty($_REQUEST["dateFin"])? null : $_REQUEST["dateFin"], empty($_REQUEST["medicament"])? null : $_REQUEST["medicament"]);
            $sommeMedicamentPresenter = sommeMedicamentPresenterFiltre($secteur, empty($_REQUEST['dateDebut'])? null : $_REQUEST['dateDebut'], empty($_REQUEST["dateFin"])? null : $_REQUEST["dateFin"], empty($_REQUEST["medicament"])? null : $_REQUEST["medicament"]);
            $sommeMedicamentOffert = sommeMedicamentOffertFiltre($secteur, empty($_REQUEST['dateDebut'])? null : $_REQUEST['dateDebut'], empty($_REQUEST["dateFin"])? null : $_REQUEST["dateFin"], empty($_REQUEST["medicament"])? null : $_REQUEST["medicament"]);
			include 'vues/v_statistiqueSecteur.php';
            break;
        }


	default: {

			header('Location: index.php?uc=medicaments&action=statistiqueSecteur');
			break;
		}
}
?>