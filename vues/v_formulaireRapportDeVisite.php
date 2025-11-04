<section class="bg-light">
    <div class="container">
        <div class="structure-hero pt-lg-5 pt-4">
            <h1 class="titre text-center">Saisie de rapport n°</h1>
            <p class="text text-center">
                Formulaire permettant de rédiger un rapport de visite
            </p>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5">
            </div>
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5 py-3">
                <?php if ($_SESSION['erreur']) {
                    echo '<p class="alert alert-danger text-center w-100">Un problème est survenu lors de la selection du praticien</p>';
                    $_SESSION['erreur'] = false;
                } ?>
                <form action="index.php?uc=mesrapports&action=afficherrapport" method="post" class="formulaire-recherche col-12 m-0">
                    <label class="titre-formulaire">Rapport de visite</label>
                    <label>Numéro du rapport: </label>
                    <label>Matricule du collaborateur: </label>

                    <label for="praticien">Praticien concerné: <span style="color: red">*</span></label>
                    <select required name="praticien" class="form-select mt-3">
                        <option value class="text-center"></option>
                        <?php
                        // Liste les numéros, prénoms et noms des différents praticiens en liste déroulante
                        foreach ($result as $key) {
                            echo '<option value="'. $key['PRA_PRENOM'] . ' - ' . $key['PRA_NOM'] . '</option>';
                        }
                        ?>
                    </select>

                    <label for="remplacant">Rempaçant: <span style="color: red">*</span></label>
                    <select required name="remplacant" class="form-select mt-3">
                        <option value class="text-center"></option>
                        <?php
                        // Liste les numéros, prénoms et noms des différents praticiens en liste déroulante
                        foreach ($result as $key) {
                            echo '<option value="'. $key['PRA_PRENOM'] . ' - ' . $key['PRA_NOM'] . '</option>';
                        }
                        ?>
                    </select>
                    
                    <label for="dateSaisie">Date de saisie <span style="color: red">*</span> :</label>
                    <input type="date" id="dateSaisie" name="dateSaisie" required >

                    <label for="bilan">Bilan du rapport<span style="color: red">*</span> :</label>
                    <input type="text" id="bilan" name="bilan" required >

                    <label for="dateVisite">Date de visite <span style="color: red">*</span> :</label>
                    <input type="date" id="dateVisite" name="dateVisite" required >
                    
                    <label for="motif">Motif: <span style="color: red">*</span></label>
                    <select required name="motif" class="form-select mt-3">
                        <option value class="text-center"></option>
                        <?php
                        // Liste les numéros, prénoms et noms des différents praticiens en liste déroulante
                        foreach ($result as $key) {
                            echo '<option value="'. $key['MOT_LIB'].'</option>';
                        }
                        ?>
                    </select>

                    <label for="premMedoc">1er medicament présenté: </label>
                    <select required name="premMedoc" class="form-select mt-3">
                        <option value class="text-center"></option>
                        <?php
                        // Liste les numéros, prénoms et noms des différents praticiens en liste déroulante
                        foreach ($result as $key) {
                            echo '<option value="'. $key['MOT_LIB'].'</option>';
                        }
                        ?>
                    </select>

                    <label><input type="checkbox" id="echantillon">Échantillon</label>

                    <select required name="premMedoc" class="form-select mt-3">
                        <option value class="text-center"></option>
                        <?php
                        // Liste les numéros, prénoms et noms des différents praticiens en liste déroulante
                        foreach ($result as $key) {
                            echo '<option value="'. $key['MED_NOMCOMMERCIAL'].'</option>';
                        }
                        ?>
                    </select>
                    <input type="number" id="quantite" name="quantite" min="1" value="1">

                    <label><input type="checkbox" id="definitive">Saisie definitive</label>
                    
                    <input class="btn btn-info text-light valider" type="submit" value="Valider le rapport">
                    <input class="btn btn-info text-light valider" type="button" onclick="history.back()" value="Retour"></button>
                </form>
            </div>
        </div>
    </div>
</section>