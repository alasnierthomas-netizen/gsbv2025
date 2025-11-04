
<section class="bg-light">
    <div class="container">
        <div class="structure-hero pt-lg-5 pt-4">
            <h1 class="titre text-center">Sélectionner un collaborateur</h1>
            <p class="text text-center">
                Choisissez un collaborateur pour afficher ses informations.
            </p>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5">
                <img class="img-fluid size-img-page" src="assets/img/medecin.jpg" alt="illustration collaborateurs">
            </div>
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5 py-3">
                <?php if (!empty($_SESSION['erreur'])) {
                    echo '<p class="alert alert-danger text-center w-100">Un problème est survenu.</p>';
                    $_SESSION['erreur'] = false;
                } ?>
                <form action="index.php?uc=collaborateur&action=afficher" method="post" class="formulaire-recherche col-12 m-0">
                    <label class="titre-formulaire" for="listecollab">Collaborateurs disponibles :</label>
                    <select required name="collaborateur" id="listecollab" class="form-select mt-3">
                        <option value class="text-center">- Choisissez un collaborateur -</option>
                        <?php
                        foreach ($collaborateurs as $collaborateur) {
                            var_dump($collaborateur);
                            $value = htmlspecialchars($collaborateur['COL_MATRICULE']);
                            $label = htmlspecialchars($collaborateur['COL_NOM'] . ' - ' . $collaborateur['COL_PRENOM']);
                            echo '<option value="' . $value . '" class="form-control">' . $label . '</option>';
                        }
                        ?>
                    </select>

                        <input class="btn btn-info text-light valider" type="submit" value="Afficher les informations">
                </form>
            </div>
        </div>
    </div>
</section>