<?php
if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
	$action = "listCollaborateurs";
} else {
	$action = $_REQUEST['action'];
}

switch ($action) {
	case 'listCollaborateurs': 
    {
        $collaborateurs = getAllCollaborateur();
		include("vues/v_formulaireCollaborateurs.php");
		break;
    }
	case "afficher":
	{
		$info = getAllInformationCompte($_REQUEST["collaborateur"]);
		include("vues/v_profil.php");
		break;
	}
	case "modifier":
	{
		if (isset($_REQUEST["matricule"]) && getAllInformationCompte($_REQUEST["matricule"]) != false) { // si on a indiquer un matricule et que se matricule est correcte

			$info = getAllInformationCompte($_REQUEST["matricule"]);
			$habilitationsSubordonner = getAllHabilitationSubordonnerOuEgal($_SESSION["habilitation"]);
			$regions = getAllRegion();
			//$region = getRegionDuSecteur($info['secteur']);

			if ($_REQUEST["matricule"] == $_SESSION["matricule"]) { //modifier son propre profil
				$infoPersonnelle = true;
				$droitDeModificationMajeur = false;
			}
			else { //modifier un autre collaborateur
				$infoPersonnelle = false;
				$droitDeModificationMajeur = superieurOuEgal($_SESSION['habilitation'], $info['libeleID']);
			}

			if (!$droitDeModificationMajeur && !$infoPersonnelle) {
				include("vues/v_accesInterdit.php");
			}
			else {
				include("vues/formulaireModificationCollaborateur.php");
			}
		}
		else{
			header('Location: index.php');
			break;
		}
		break;
	}
	case 'confirmeModifier': //TODO thomas: rajouter des regex
	{
		$habilitaionVide = empty($_REQUEST['habilitation']);
		if (isset(($_SESSION['habilitation']) )) //test si l'utilisateur est connecté
		{
		if ($habilitaionVide || superieurOuEgal( $_SESSION['habilitation'], $_REQUEST['habilitation'])) //test si l'utilisateur ne donne pas des droit supérieur au sien
			{
				if (isset($_REQUEST['matricule'])) // test si le matricule est bien présent
				{
					if (isset($_REQUEST['nom']) && isset($_REQUEST['prenom']) && isset($_REQUEST['rue']) && isset($_REQUEST['code_postal']) && isset($_REQUEST['ville']) && isset($_REQUEST['date_embauche']) && $_REQUEST['date_embauche'] != '' && isset($_REQUEST['region'])) // test si tout les champs sont bien présent
					{
						if ($habilitaionVide)
						{
							$modificationReussi = modifierCollaborateurSansHabilitation($_REQUEST['matricule'], $_REQUEST['nom'], $_REQUEST['prenom'], $_REQUEST['rue'], $_REQUEST['code_postal'], $_REQUEST['ville'], $_REQUEST['date_embauche'], $_REQUEST['region']);
						}
						else
						{
							if ($_REQUEST['habilitation'] == 3)
							{
								if (secteurOccuper(getSecteurDeLaRegion($_REQUEST['region']), $_REQUEST['matricule']))
								{
									header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('Le secteur est déjà occupée par un autre responsable de secteur.') . '&matricule=' . urlencode($_REQUEST['matricule']));
									exit();
								}
								else
								{
									$modificationReussi = modifierCollaborateurResponsableSecteur($_REQUEST['matricule'], $_REQUEST['nom'], $_REQUEST['prenom'], $_REQUEST['rue'], $_REQUEST['code_postal'], $_REQUEST['ville'], $_REQUEST['date_embauche'], $_REQUEST['habilitation'], $_REQUEST['region']);
								}

							}
							else
							{
								$modificationReussi = modifierCollaborateur($_REQUEST['matricule'], $_REQUEST['nom'], $_REQUEST['prenom'], $_REQUEST['rue'], $_REQUEST['code_postal'], $_REQUEST['ville'], $_REQUEST['date_embauche'], $_REQUEST['habilitation'], $_REQUEST['region']);

							}
							
						}
						if ($modificationReussi) {
							header('Location: index.php?uc=collaborateur&action=afficher&success=' . urlencode('Les modifications ont bien été prises en compte.') . '&collaborateur=' . urlencode($_REQUEST['matricule']));
							exit();
						}
						else {
							header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('modifier&erreur=Une erreur est survenue lors de la modification.') . '&matricule=' . urlencode($_REQUEST['matricule']));
							exit();
						}
					}
					else
					{
						header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('Tous les champs ne sont pas remplis.') . '&matricule=' . urlencode($_REQUEST['matricule']));
						exit();
					}
				}
				else{
					header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('Une erreur est survenue lors de la modification.') . '&matricule=' . urlencode($_REQUEST['matricule']));
					exit();
				}
			}
			else
			{
				header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('Vous ne pouvez pas attribuer une habilitation supérieur à la votre') . '&matricule=' . urlencode($_REQUEST['matricule']));
				exit();
			}
		}
		else
		{
			include("vues/v_accesInterdit.php");
		}

	}
	default: 
    {
		header('Location: index.php');
		break;
	}
}
?>