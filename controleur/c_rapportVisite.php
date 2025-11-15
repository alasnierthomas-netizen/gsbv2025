<?php

include_once __DIR__ . '/../modele/rapportVisite.modele.inc.php';
include_once __DIR__ . '/../modele/medicament.modele.inc.php';

if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
	$action = "mesrapports";
} else {
	$action = $_REQUEST['action'];
}
switch ($action) {
	/*
	case 'afficherrapport': {

			$rap = $_REQUEST['praticien'];
			$rap = getAllNomMedicament();
			include("vues/v_afficherRapportVisite.php");
			break;
		}

	case 'afficherrapport': {

			if (isset($_REQUEST['medicament']) && getAllInformationMedicamentDepot($_REQUEST['medicament'])) {
				$med = $_REQUEST['medicament'];
				$carac = getAllInformationMedicamentDepot($med);
				if (empty($carac[7])) {
					$carac[7] = 'Non défini(e)';
				}
				include("vues/v_afficherMedicament.php");
			} else {
				$_SESSION['erreur'] = true;
				header("Location: index.php?uc=medicaments&action=formulairemedoc");
			}
			break;
		}
*/
        case 'mesrapports':{
            $medicaments = getAllNomMedicament();
            $rapports = getAllRapportsVisiteDetails();
            include 'vues/v_afficherRapportVisite.php';
            break;
        }

        // Rechercher avec filtres
        case 'rechercher':{
			$medicaments = getAllNomMedicament();
            $rapports = getRapportsVisiteFiltres(empty($_REQUEST['dateDebut'])? null : $_REQUEST['dateDebut'], empty($_REQUEST["dateFin"])? null : $_REQUEST["dateFin"], empty($_REQUEST["medicament"])? null : $_REQUEST["medicament"]);
            include 'vues/v_afficherRapportVisite.php';
            break;
        }

		case 'redigerrapport': {
			
			$mot = getMotif();
			$med = getAllNomMedicament();
			include("vues/v_formulaireRapportDeVisite.php");
			break;
		}

	default: {

			header('Location: index.php?uc=medicaments&action=mesrapports');
			break;
		}
}
?>