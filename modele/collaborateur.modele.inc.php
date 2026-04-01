<?php 
include_once 'bd.inc.php';


    /**
     * Vérifie si un collaborateur existe pour un matricule donné.
     *
     * @param string $matricule Le matricule du collaborateur.
     * @return bool
     */
    function collaborateurExiste($matricule): bool
    {
        try {
            $getInfo = connexionPDO();
            $req = $getInfo->prepare('SELECT * FROM collaborateur WHERE COL_MATRICULE = ?');
            $req->execute([$matricule]);
            $res = $req->fetch();

            return ($res != false);
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Récupère l'identifiant d'habilitation associé à un login.
     *
     * @param int $id_log L'identifiant de login.
     * @return int|array
     */
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


    /**
     * Récupère tous les collaborateurs d'un secteur donné.
     *
     * @param mixed $secteur Le code du secteur.
     * @return array
     */
    function getAllCollaborateurFromSecteur($secteur): array{
        try
        {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT *
                FROM collaborateur
                JOIN region ON region.REG_CODE = collaborateur.REG_CODE
                WHERE region.SEC_CODE = ?
                ORDER BY collaborateur.COL_NOM, collaborateur.COL_PRENOM');
            $req->execute([$secteur]);
            $res = $req->fetchAll();
            return $res;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Récupère toutes les habilitations strictement subordonnées à une habilitation donnée.
     *
     * @param int $hab_id L'identifiant d'habilitation.
     * @return array
     */
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

    /**
     * Récupère toutes les habilitations subordonnées ou égales à une habilitation donnée.
     *
     * @param int $hab_id L'identifiant d'habilitation.
     * @return array
     */
    function getAllHabilitationSubordonnerOuEgal(int $hab_id): array{
        $allHabilitation = getAllHabilitation();
        $subordonner = [];
        foreach ($allHabilitation as $habiliatation) {
            if (superieurOuEgal($hab_id, $habiliatation["HAB_ID"]))
            {
                array_push($subordonner, $habiliatation);
            }
        }
        return $subordonner;
    }

    /**
     * Récupère toutes les habilitations disponibles.
     *
     * @return array
     */
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

    /**
     * Récupère l'habilitation d'un collaborateur.
     *
     * @param string $matricule Le matricule du collaborateur.
     * @return array|false
     */
    function getHabilitation($matricule): array{
        try
        {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT HAB_ID FROM collaborateur WHERE collaborateur.COL_MATRICULE = ?');
            $req->execute([$matricule]);
            $res = $req->fetch();
            return $res;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Récupère toutes les régions disponibles.
     *
     * @return array
     */
    function getAllRegion(): array{
        try
        {
            $monPdo = connexionPDO();
            $req = 'SELECT * FROM region
                    order by REG_NOM';
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
     * Récupère les régions associées à un secteur donné.
     *
     * @param mixed $secteur Le code du secteur.
     * @return array
     */
    function getRegionDuSecteur($secteur): array{
        try
        {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT * FROM region WHERE SEC_CODE = ? ORDER BY REG_NOM');
            $req->execute([$secteur]);
            $res = $req->fetchAll();
            return $res;
        } 
    
        catch (PDOException $e){
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Ajoute un collaborateur sans secteur de responsabilité.
     *
     * @param string $matricule
     * @param string $nom
     * @param string $prenom
     * @param string $rue
     * @param string $code_postal
     * @param string $ville
     * @param string $date_embauche
     * @param int $habilitation
     * @param string $region
     * @return bool
     */
    function ajouterCollaborateur($matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('INSERT INTO collaborateur (COL_MATRICULE, COL_NOM, COL_PRENOM, COL_ADRESSE, COL_CP, COL_VILLE, COL_DATEEMBAUCHE, HAB_ID, REG_CODE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $res = $req->execute([$matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region]);
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Ajoute un responsable de secteur.
     *
     * @param string $matricule
     * @param string $nom
     * @param string $prenom
     * @param string $rue
     * @param string $code_postal
     * @param string $ville
     * @param string $date_embauche
     * @param int $habilitation
     * @param string $region
     * @return bool
     */
    function ajouterCollaborateurResponsableSecteur($matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('INSERT INTO collaborateur (COL_MATRICULE, COL_NOM, COL_PRENOM, COL_ADRESSE, COL_CP, COL_VILLE, COL_DATEEMBAUCHE, HAB_ID, SEC_CODE, REG_CODE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $res = $req->execute([$matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, getSecteurDeLaRegion($region), $region]);
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Modifie les informations d'un collaborateur.
     *
     * @param string $matricule
     * @param string $nom
     * @param string $prenom
     * @param string $rue
     * @param string $code_postal
     * @param string $ville
     * @param string $date_embauche
     * @param int $habilitation
     * @param string $region
     * @return bool
     */
    function modifierCollaborateur($matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('UPDATE collaborateur SET COL_NOM = ?, COL_PRENOM = ?, COL_ADRESSE = ?, COL_CP = ?, COL_VILLE = ?, COL_DATEEMBAUCHE = ?, HAB_ID = ?, REG_CODE = ?, SEC_CODE = NUll WHERE COL_MATRICULE = ?');
            $res = $req->execute([$nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region, $matricule]);
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }
    
    /**
     * Modifie un responsable de secteur.
     *
     * @param string $matricule
     * @param string $nom
     * @param string $prenom
     * @param string $rue
     * @param string $code_postal
     * @param string $ville
     * @param string $date_embauche
     * @param int $habilitation
     * @param string $region
     * @return bool
     */
    function modifierCollaborateurResponsableSecteur($matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, $region): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('UPDATE collaborateur SET COL_NOM = ?, COL_PRENOM = ?, COL_ADRESSE = ?, COL_CP = ?, COL_VILLE = ?, COL_DATEEMBAUCHE = ?, HAB_ID = ?, SEC_CODE = ?, REG_CODE = ? WHERE COL_MATRICULE = ?');
            $res = $req->execute([$nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $habilitation, getSecteurDeLaRegion($region), $region, $matricule]);
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Modifie un collaborateur sans changer son habilitation.
     *
     * @param string $matricule
     * @param string $nom
     * @param string $prenom
     * @param string $rue
     * @param string $code_postal
     * @param string $ville
     * @param string $date_embauche
     * @param string $region
     * @return bool
     */
    function modifierCollaborateurSansHabilitation($matricule, $nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $region): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('UPDATE collaborateur SET COL_NOM = ?, COL_PRENOM = ?, COL_ADRESSE = ?, COL_CP = ?, COL_VILLE = ?, COL_DATEEMBAUCHE = ?, REG_CODE = ? WHERE COL_MATRICULE = ?');
            $res = $req->execute([$nom, $prenom, $rue, $code_postal, $ville, $date_embauche, $region, $matricule]);
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Vérifie si une région est déjà occupée par un autre délégué régional.
     *
     * @param string $region Code de la région.
     * @param string $matriculeIgnorer Matricule à exclure de la vérification.
     * @return bool
     */
    function regionOccuper ($region, $matriculeIgnorer): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT COL_MATRICULE FROM collaborateur WHERE HAB_ID = 2 && REG_CODE = ? && COL_MATRICULE != ?');
            $req->execute([$region, $matriculeIgnorer]);
            $res = $req->fetchAll();
            return (count($res) > 0); // Return true if there are collaborators with HAB_ID = 2
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Récupère le code de secteur associé à une région.
     *
     * @param string $region Code de la région.
     * @return string
     */
    function getSecteurDeLaRegion ($region): string {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT SEC_CODE FROM region WHERE REG_CODE = ?');
            $req->execute([$region]);
            $res = $req->fetch();
            return $res['SEC_CODE'];
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Vérifie si un secteur est déjà occupé par un autre responsable.
     *
     * @param string $secteur Code du secteur.
     * @param string $matriculeIgnorer Matricule à exclure de la vérification.
     * @return bool
     */
    function secteurOccuper ($secteur, $matriculeIgnorer): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT COL_MATRICULE FROM collaborateur WHERE HAB_ID = 3 && SEC_CODE = ? && COL_MATRICULE != ?');
            $req->execute([$secteur, $matriculeIgnorer]);
            $res = $req->fetchAll();
            return (count($res) > 0); // Return true if there are collaborators with HAB_ID = 2
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

        /**
         * Vérifie si un département existe.
         *
         * @param string|int $NoDEPT Numéro du département.
         * @return bool
         */
        function existeDepartement($NoDEPT): bool {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT * FROM departement WHERE NoDEPT = ?');
            $req->execute([$NoDEPT]);
            $res = $req->fetchAll();
            return (count($res) > 0); // Return true si le département existe
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Récupère les informations d'un département.
     *
     * @param string|int $NoDEPT Numéro du département.
     * @return array|false
     */
    function departement($NoDEPT): array {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT * FROM departement WHERE NoDEPT = ?');
            $req->execute([$NoDEPT]);
            $res = $req->fetch();
            return $res; // Return true si le département existe
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }

    /**
     * Récupère le libellé d'une habilitation.
     *
     * @param int $hab_id L'identifiant de l'habilitation.
     * @return array|false
     */
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

/**
 * Vérifie si une habilitation est strictement supérieure à une autre.
 *
 * @param string $hab_user Habilitation de l'utilisateur.
 * @param string $hab_segond Habilitation à comparer.
 * @return bool
 */
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

    /**
     * Vérifie si une habilitation est supérieure ou égale à une autre.
     *
     * @param string $hab_user Habilitation de l'utilisateur.
     * @param string $hab_segond Habilitation à comparer.
     * @return bool
     */
    function superieurOuEgal(string $hab_user, string $hab_segond): bool //toute modification faite a la hirarchie, dois être indiquer sur cette fonction
    {
        $result = false;
        if ($hab_user == 2) { //Délégué Régional
            if ($hab_segond == 1 || $hab_segond == 2) //peux modifier les visiteurs et lui
            {
                $result = true;
            }
        }
        elseif ($hab_user == 3) { //Responsable de Secteur
            if ($hab_segond == 2 || $hab_segond == 1 || $hab_segond == 3) //peux modifier les délégués régionaux et lui
            {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Récupère le secteur d'un collaborateur par son matricule.
     *
     * @param string $matricule Le matricule du collaborateur.
     * @return array|false
     */
    function getSecteur($matricule): array {
        try {
            $monPdo = connexionPDO();
            $req = $monPdo->prepare('SELECT secteur.SEC_CODE, secteur.sec_libelle  FROM collaborateur
                                    JOIN secteur ON secteur.SEC_CODE = collaborateur.SEC_CODE
                                    WHERE COL_MATRICULE = ?');
            $req->execute([$matricule]);
            $res = $req->fetch();
            return $res;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }
?>