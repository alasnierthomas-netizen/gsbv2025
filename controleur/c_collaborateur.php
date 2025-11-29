<?php
if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
    $action = "listCollaborateurs";
} else {
    $action = $_REQUEST['action'];
}

switch ($action) {
    case 'listCollaborateurs': 
    {
        $secteur = getSecteur($_SESSION["matricule"]);
        $collaborateurs = getAllCollaborateurFromSecteur($secteur[0]);
        include("vues/v_formulaireCollaborateurs.php");
        break;
    }
	case 'listeDeCollaborateurModifiable': 
    {
        $secteur = getSecteur($_SESSION["matricule"]);
        $collaborateurs = getAllCollaborateurFromSecteur($secteur[0]);
        include("vues/v_formulaireCollaborateursModifiable.php");
        break;
    }
    case "afficher":
    {
        $info = (!empty($_REQUEST['collaborateur']) && collaborateurExiste($_REQUEST['collaborateur']))? getAllInformationCompte($_REQUEST["collaborateur"]) : null;
        if ($info != null && getSecteur($_SESSION['matricule'])[1] == $info['secteur'])
        {
            $infoNonPersonnelle = $_SESSION["matricule"] == $info["matricule"] ? false : true;
            include("vues/v_profil.php");
        }
        else
        {
            $_SESSION['erreur'] = "vous ne vous occuper pas du secteur dans le qu'elle se trouve le collaborateur ou selui-ci n'existe pas.";
            header('Location: index.php?uc=collaborateur&action=listCollaborateurs');
            exit();
        }
        break;
    }

    case "ajouter":
    {
        $habilitationsSubordonner = getAllHabilitationSubordonnerOuEgal($_SESSION["habilitation"]);
        $secteurUtilisateur = getSecteur($_SESSION["matricule"]);
        // ne récupérer que les régions autorisées pour le secteur de l'utilisateur
        $regions = getRegionDuSecteur($secteurUtilisateur[0]);
        include("vues/formulaireAjoutCollaborateur.php");
        break;
    }

    case "confirmeAjouter":
    {
        if (isset($_SESSION['habilitation'])) // test si l'utilisateur est connecté
        {
            // récupérer id des régions autorisées pour l'utilisateur connecté
            $secteurUtilisateur = getSecteur($_SESSION['matricule']);
            $regionsAutorisees = array_column(getRegionDuSecteur($secteurUtilisateur[0]), 0);

            if (isset($_REQUEST['matricule'])) // test si le matricule est bien présent
            {
                if (isset($_REQUEST['nom']) && isset($_REQUEST['prenom']) && isset($_REQUEST['rue']) && isset($_REQUEST['code_postal']) && isset($_REQUEST['ville']) && isset($_REQUEST['date_embauche']) && $_REQUEST['date_embauche'] != '' && isset($_REQUEST['habilitation'])) // test si tous les champs sont présents
                {
                    // validation regex : code postal = 5 chiffres, rue = lettres/chiffres/espaces/ponctuation basique (2-100 chars)
                    $cp = trim($_REQUEST['code_postal']);
                    if (!preg_match('/^[0-9]{5}$/', $cp)) {
                        header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Code postal invalide (5 chiffres requis).'));
                        exit();
                    }

                    // Vérifier que le matricule n'existe pas déjà
                    if (collaborateurExiste($_REQUEST['matricule']))
                    {
                        header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Ce matricule existe déjà.'));
                        exit();
                    }
                    
                    // vérifier que le département existe et qu'il appartient au secteur de l'utilisateur connecté
                    $departement = departement($cp[0].$cp[1]);
                    if ($departement == false || getSecteur($_SESSION['matricule'])[1] != getSecteurDeLaRegion($departement["REG_CODE"]))
                    {
                        header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Le département correspondant à ce code postal n’existe pas ou n’appartient pas à votre secteur.'));
                        exit();
                    }

                    // détermine l'habilitation fournie
                    $habilitation = $_REQUEST['habilitation'];
                    // si c'est un responsable de secteur (habilitation = 3)
                    if ($habilitation == 3)
                    {
                        if (secteurOccuper(getSecteurDeLaRegion($departement["REG_CODE"]), $_REQUEST['matricule']))
                        {
                            header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Le secteur est déjà occupé par un autre responsable de secteur.'));
                            exit();
                        }
                        else
                        {
                            $ajoutReussi = ajouterCollaborateurResponsableSecteur(
                                $_REQUEST['matricule'],
                                $_REQUEST['nom'],
                                $_REQUEST['prenom'],
                                $_REQUEST['rue'],
                                $cp,
                                $_REQUEST['ville'],
                                $_REQUEST['date_embauche'],
                                $habilitation,
                                $departement["REG_CODE"]
                            );
                        }
                    }
                    else
                    {
                        $ajoutReussi = ajouterCollaborateur(
                            $_REQUEST['matricule'],
                            $_REQUEST['nom'],
                            $_REQUEST['prenom'],
                            $_REQUEST['rue'],
                            $cp,
                            $_REQUEST['ville'],
                            $_REQUEST['date_embauche'],
                            $habilitation,
                            $departement["REG_CODE"]
                        );
                    }
                    
                    if ($ajoutReussi) {
                        header('Location: index.php?uc=collaborateur&action=afficher&success=' . urlencode('Le collaborateur a bien été ajouté.') . '&collaborateur=' . urlencode($_REQUEST['matricule']));
                        exit();
                    }
                    else {
                        header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Une erreur est survenue lors de l\'ajout du collaborateur.'));
                        exit();
                    }
                }
                else
                {
                    header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Tous les champs ne sont pas remplis.'));
                    exit();
                }
            }
            else
            {
                header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Une erreur est survenue lors de l\'ajout.'));
                exit();
            }
        }
        else
        {
            include("vues/v_accesInterdit.php");
        }
        break;
    }

    case "modifier":
    {
		$info = (isset($_REQUEST["matricule"]) && collaborateurExiste($_REQUEST["matricule"]))? getAllInformationCompte($_REQUEST["matricule"]) : null;
        if ($info != null && getSecteur($_SESSION['matricule'])[1] == $info['secteur']) { // on s'assure que le collaborateur existe et qu'il fait partie du secteur de l'utilisateur connecté
            $info["date_embauche"] = explode("/", $info["date_embauche"]);
            $habilitationsSubordonner = getAllHabilitationSubordonnerOuEgal($_SESSION["habilitation"]);
            $regions = getRegionDuSecteur(getSecteur($_SESSION["matricule"])[0]);

            if ($_REQUEST["matricule"] == $_SESSION["matricule"]) { //modifier son propre profil
                $infoPersonnelle = true;
                $droitDeModificationMajeur = false;
            }
            else { //modifier un autre collaborateur
                $infoPersonnelle = false;
                $droitDeModificationMajeur = true;
            }
            include("vues/formulaireModificationCollaborateur.php");
        }
        else{
            $_SESSION['erreur'] = "vous ne vous occuper pas du secteur dans le qu'elle se trouve le collaborateur ou selui-ci n'existe pas.";
            header('Location: index.php?uc=collaborateur&action=listeDeCollaborateurModifiable');
            exit();
        }
        break;
    }

    case 'confirmeModifier': 
    {
        $habilitaionVide = empty($_REQUEST['habilitation']);
        if (isset($_SESSION['habilitation'])) // test si l'utilisateur est connecté
        {
            // récupérer les régions autorisées pour l'utilisateur connecté
            $secteurUtilisateur = getSecteur($_SESSION['matricule']);
            $regionsAutorisees = array_column(getRegionDuSecteur($secteurUtilisateur[0]), 0);

            // vérifier que le département existe et qu'il appartient au secteur de l'utilisateur connecté
            $departement = departement($cp[0].$cp[1]);
            if ($departement == false || getSecteur($_SESSION['matricule'])[1] != getSecteurDeLaRegion($departement["REG_CODE"]))
            {
                header('Location: index.php?uc=collaborateur&action=ajouter&erreur=' . urlencode('Le département correspondant à ce code postal n’existe pas ou n’appartient pas à votre secteur.'));
                exit();
            }


            if ($habilitaionVide || superieurOuEgal( $_SESSION['habilitation'], $_REQUEST['habilitation'])) //test si l'utilisateur ne donne pas des droit supérieur au sien
            {
                if (isset($_REQUEST['matricule'])) // test si le matricule est bien présent
                {
                    if (isset($_REQUEST['nom']) && isset($_REQUEST['prenom']) && isset($_REQUEST['rue']) && isset($_REQUEST['code_postal']) && isset($_REQUEST['ville']) && isset($_REQUEST['date_embauche']) && $_REQUEST['date_embauche'] != '') // test si tout les champs sont bien présent
                    {
                        // validation regex : code postal = 5 chiffres
                        $cp = trim($_REQUEST['code_postal']);
                        if (!preg_match('/^[0-9]{5}$/', $cp)) {
                            header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('Code postal invalide (5 chiffres requis).') . '&matricule=' . urlencode($_REQUEST['matricule']));
                            exit();
                        }

                        if ($habilitaionVide)
                        {
                            $habilitation = getHabilitation($_REQUEST['matricule']);
                        }
                        if (($habilitaionVide)? $habilitation = 3 : $_REQUEST['habilitation'] == 3)
                        {
                            if (secteurOccuper(getSecteurDeLaRegion($departement["REG_CODE"]), $_REQUEST['matricule']))
                            {
                                header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('Le secteur est déjà occupée par un autre responsable de secteur.') . '&matricule=' . urlencode($_REQUEST['matricule']));
                                exit();
                            }
                            else
                            {
                                $modificationReussi = modifierCollaborateurResponsableSecteur($_REQUEST['matricule'], $_REQUEST['nom'], $_REQUEST['prenom'], $_REQUEST['rue'], $cp, $_REQUEST['ville'], $_REQUEST['date_embauche'], ($habilitaionVide)? $habilitation : $_REQUEST['habilitation'], $departement["REG_CODE"]);
                            }

                        }
                        else
                        {
                            $modificationReussi = modifierCollaborateur($_REQUEST['matricule'], $_REQUEST['nom'], $_REQUEST['prenom'], $_REQUEST['rue'], $cp, $_REQUEST['ville'], $_REQUEST['date_embauche'], ($habilitaionVide)? $habilitation : $_REQUEST['habilitation'], $departement["REG_CODE"]);

                        }
                            

                        if ($modificationReussi) {
                            header('Location: index.php?uc=collaborateur&action=afficher&success=' . urlencode('Les modifications ont bien été prises en compte.') . '&collaborateur=' . urlencode($_REQUEST['matricule']));
                            exit();
                        }
                        else {
                            header('Location: index.php?uc=collaborateur&action=modifier&erreur=' . urlencode('Une erreur est survenue lors de la modification.') . '&matricule=' . urlencode($_REQUEST['matricule']));
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