<?php
    ob_start(); // TODO: cette ligne a fixe un bug d'affichage de la page après une redirection mais je sais pas pourquoi, demander a la prof. aussi peux t'il y avoir plusieur Responsable Secteur dans un méme secteur ?

    require_once('modele/medicament.modele.inc.php');

    require_once('modele/collaborateur.modele.inc.php');

    require_once('modele/rapportVisite.modele.inc.php');

    require_once('modele/connexion.modele.inc.php');



    if (!isset($_REQUEST['uc']) || empty($_REQUEST['uc']))
        $uc = 'accueil';
    else {
        $uc = $_REQUEST['uc'];
    }
    ?>
    
    <?php
    if (empty($_SESSION['login'])) {
        include("vues/v_headerDeconnexion.php");
    } else {
        include("vues/v_header.php");
    }
    switch ($uc) {
        case 'accueil': {
            include("vues/v_accueil.php");
            break;
        }

        case 'medicaments': {

            if (!empty($_SESSION['login'])) {  // Si l'utilisateur est connecté
                include("controleur/c_medicaments.php");
            }
            
            else {  // Si l'utilisateur n'est pas connecté
                include("vues/v_accesInterdit.php");
            }

            break;
        }

        case 'connexion': {
            include("controleur/c_connexion.php");
            break;
        }


        case "collaborateur": {
            if (empty($_SESSION['login']) || (getDroit($_SESSION['login']) != 3)) { // acesse interdit
                include("vues/v_accesInterdit.php");
            }
            else { // accese autorisé
                include("controleur/c_collaborateur.php");
            }
            break;
        }
        case "secteur": {
            if (empty($_SESSION['login'])  || (getDroit($_SESSION['login']) != 3)) { // acesse interdit
                include("vues/v_accesInterdit.php");
            }
            else { // accese autorisé
                include("controleur/c_secteur.php");
            }
            break;
        }

        default: {

            include("vues/v_accueil.php");
            break;
        }
    }
    ?>
    <?php include("vues/v_footer.php"); ?>

</body>

</html>