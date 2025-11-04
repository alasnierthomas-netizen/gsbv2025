<?php

include_once 'bd.inc.php';
/*
    function getAllNomMedicament(){

        try{
            $monPdo = connexionPDO();
            $req = 'SELECT MED_DEPOTLEGAL, MED_NOMCOMMERCIAL FROM medicament ORDER BY MED_NOMCOMMERCIAL';
            $res = $monPdo->query($req);
            $result = $res->fetchAll();
            return $result;
        } 

        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }

    }


    function getAllNomPraticien() {
    try {
        $monPdo = connexionPDO();
        $req = 'SELECT PRA_NUM, PRA_PRENOM, PRA_NOM FROM praticien ORDER BY PRA_NUM';
        $res = $monPdo->query($req);
        return $res->fetchAll();
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
*/
    function getAllInformationRapportVisite($rap_num){

        try{
            $monPdo = connexionPDO();
            $req = 
            'SELECT r.COL_MATRICULE as \'matricule\', 
            r.RAP_NUM as \'Numero\', 
            r.RAP_DATEVISITE as \'DateVisite\', 
            r.RAP_BILAN as \'Bilan\', 
            r.COUT as \'Cout\', 
            r.DATESAISIE as \'Datesaisie\', 
            e.ETAT_LIB as \'Libelleetat\', 
            m.MOT_LIB as \'Motif\', 
            p.PRA_PRENOM as \'PrenomPraticien\', 
            p.PRA_NOM as \'NomPraticien\',
            p.PRA_PRENOM_REMP as \'PrenomPraticienRemp\', 
            p.PRA_NOM_REMP as \'NomPraticienRemp\',  
            med.MED_DEPOTLEGAL_PRES1 as \'pres1\', 
            med.MED_DEPOTLEGAL_PRES2 as \'pres2\'
            FROM rapport_visite r 
            INNER JOIN etat_rapport e ON e.ETAT_CODE = r.ETAT_CODE
            INNER JOIN motif m ON m.MOT_CODE = r.MOT_CODE
            INNER JOIN praticien p ON p.PRA_NUM = r.PRA_NUM OR p.PRA_NUM = r.PRA_NUM_REMP
            INNER JOIN medicament med ON med.DEPOTLEGAL = r.MED_DEPOTLEGAL_PRES1 OR med.DEPOTLEGAL = r.MED_DEPOTLEGAL_PRES2
            WHERE r.RAP_NUM = "'.$rap_num.'"';

            $res = $monPdo->query($req);
            $result = $res->fetch();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }
/*
    function getAllInformationMedicamentNom($nom){

        try{
            $monPdo = connexionPDO();
            $req = 'SELECT m.MED_DEPOTLEGAL as \'depotlegal\', m.MED_NOMCOMMERCIAL as \'nomcom\', m.MED_COMPOSITION as \'compo\', m.MED_EFFETS as \'effet\', m.MED_CONTREINDIC as \'contreindic\', m.MED_PRIXECHANTILLON as \'prixechan\', f.FAM_LIBELLE as \'famille\' FROM medicament m INNER JOIN famille f ON f.FAM_CODE = m.FAM_CODE WHERE MED_NOMCOMMERCIAL = "'.$nom.'"';
            $res = $monPdo->query($req);
            $result = $res->fetch();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    function getDepotMedoc($nom){

        try{
            $monPdo = connexionPDO();
            $req = 'SELECT MED_DEPOTLEGAL, MED_NOMCOMMERCIAL FROM medicament WHERE MED_DEPOTLEGAL = "'.$nom.'"';
            $res = $monPdo->query($req);
            $result = $res->fetch();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }
    
    function getNbMedicament(){

        try{
            $monPdo = connexionPDO();
            $req = 'SELECT COUNT(MED_DEPOTLEGAL) as \'nb\' FROM medicament';
            $res = $monPdo->query($req);
            $result = $res->fetch();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }
*/
?>