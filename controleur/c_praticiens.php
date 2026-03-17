<?php


    function getAllPraticiens(){

        try{
            $monPdo = connexionPDO();
            $req = 'SELECT PRA_NUM,PRA_NOM, PRA_PRENOM FROM praticien ORDER BY PRA_NUM';
            $res = $monPdo->query($req);
            $result = $res->fetchAll();
            return $result;
        } 

        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }

    }

	
    function getAllInformationPraticien($depot){

        try{
            $monPdo = connexionPDO();
            $req = 'SELECT p.PRA_NUM as \'numéro\',PRA_PRENOM as \'prénom\', PRA_NOM as \'nom\', PRA_ADRESSE as \'adresse\', PRA_CP as \'codepostal\', PRA_VILLE as \'ville\',PRA_COEFCONFIANCE as \'coefdeconfiance\',TYP_CODE  FROM praticien p  where p.PRA_NUM = "'.$depot.'"';
            $res = $monPdo->query($req);
            $result = $res->fetch();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }


    
if (!isset($_REQUEST['action']) || empty($_REQUEST['action'])) {
	$action = "formulairepraticiens";
} else {
	$action = $_REQUEST['action'];
}
switch ($action) {
	case 'formulairepraticiens': {

			$result = getAllPraticiens();
			include("vues/v_formulairePraticiens.php");
			break;
		}

	case 'afficherpraticiens': {


			if (isset($_REQUEST['praticiens']) && getAllInformationPraticien($_REQUEST['praticiens'])) {
				$med = $_REQUEST['praticiens'];
				$carac = getAllInformationPraticien($med);
				if (empty($carac[8])) {
					$carac[8] = 'Non défini(e)';
				}
				include("vues/v_afficherPraticiens.php");
			} else {
				$_SESSION['erreur'] = true;
				header("Location: index.php?uc=praticiens&action=formulairepraticiens");
			}
			break;
		}

	default: {

			header('Location: index.php?uc=praticiens&action=formulairepraticiens');
			break;
		}
}

?>
