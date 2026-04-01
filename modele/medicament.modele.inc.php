<?php

include_once 'bd.inc.php';

    /**
     * Récupère le dépôt légal et le nom commercial de tous les médicaments.
     *
     * @return array
     */
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

    /**
     * Récupère les informations d'un médicament à partir de son dépôt légal.
     *
     * @param string $depot Le code dépôt légal du médicament.
     * @return array|false
     */
    function getAllInformationMedicamentDepot($depot){

        try{
            $monPdo = connexionPDO();
            $req = 'SELECT m.MED_DEPOTLEGAL as \'depotlegal\', m.MED_NOMCOMMERCIAL as \'nomcom\', m.MED_COMPOSITION as \'compo\', m.MED_EFFETS as \'effet\', m.MED_CONTREINDIC as \'contreindic\', m.MED_PRIXECHANTILLON as \'prixechan\', f.FAM_LIBELLE as \'famille\' FROM medicament m INNER JOIN famille f ON f.FAM_CODE = m.FAM_CODE WHERE MED_DEPOTLEGAL = "'.$depot.'"';
            $res = $monPdo->query($req);
            $result = $res->fetch();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Récupère les informations d'un médicament à partir de son nom commercial.
     *
     * @param string $nom Le nom commercial du médicament.
     * @return array|false
     */
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

    /**
     * Récupère le dépôt légal et le nom commercial d'un médicament par depot.
     *
     * @param string $nom Le code dépôt légal du médicament.
     * @return array|false
     */
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
    
    /**
     * Retourne le nombre total de médicaments enregistrés.
     *
     * @return array|false
     */
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

?>