<?php 
include_once 'bd.inc.php';

    function getDroit(int $id_log): int | array {
        try {
            $getInfo = connexionPDO();
            $req = $getInfo->prepare('SELECT hab_id FROM login JOIN collaborateur ON collaborateur.COL_MATRICULE = login.COL_MATRICULE WHERE login.LOG_ID = ?');
            $req->execute([$id_log]);
            $res = $req->fetch();

            return $res[0];
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }


    function getAllCollaborateur(): array{
        try
        {
            $monPdo = connexionPDO();
            $req = 'SELECT * FROM collaborateur';
            $res = $monPdo->query($req);
            $result = $res->fetchAll();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    function getAllHabilitationSubordonner(int $hab_id): array{
        $allHabilitation = getAllHabilitation();
        $subordonner = [];
        foreach ($allHabilitation as $habiliatation) {
            if (superieur($hab_id, $habiliatation["HAB_ID"]))
            {
                array_push($subordonner, $habiliatation);
            }
        }
        return $subordonner;
    }

    function getAllHabilitation(): array{
        try
        {
            $monPdo = connexionPDO();
            $req = 'SELECT * FROM habilitation';
            $res = $monPdo->query($req);
            $result = $res->fetchAll();
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    function getAllRegion(): array{
        try
        {
            $monPdo = connexionPDO();
            $req = 'SELECT * FROM region';
            $res = $monPdo->query($req);
            $result = $res->fetchAll();    
            return $result;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    function modifierCollaborateur($matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('UPDATE collaborateur SET COL_NOM = ?, COL_PRENOM = ?, COL_ADRESSE = ?, COL_CP = ?, COL_VILLE = ?, COL_DATEEMBAUCHE = ?, HAB_ID = ?, REG_CODE = ? WHERE COL_MATRICULE = ?');
            $res = $req->execute([$nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region, $matricule]);
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    function getLibelleHabilitation(int $hab_id): array{
        try 
        {
            $getInfo = connexionPDO();
            $req = $getInfo->prepare('SELECT hab_lib FROM habilitation WHERE hab_id = ?');
            $req->execute([$hab_id]);
            $res = $req->fetch();
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

function superieur(string $hab_user, string $hab_segond): bool //toute modification faite a la hirarchie, dois être indiquer sur cette fonction
    {
        $result = false;
        if ($hab_user == 2) { //Délégué Régional
            if ($hab_segond == 1) //peux modifier les visiteurs
            {
                $result = true;
            }
        }
        elseif ($hab_user == 3) { //Responsable de Secteur
            if ($hab_segond == 2 || $hab_segond == 1) //peux modifier les délégués régionaux
            {
                $result = true;
            }
        }
        return $result;
    }
?>