<?php

include_once 'bd.inc.php';


    /**
     * Récupère la liste des praticiens.
     *
     * @return array
     */
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

	
    /**
     * Récupère les informations complètes d'un praticien.
     *
     * @param string $depot Le numéro du praticien.
     * @return array|false
     */
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

?>