<section class="bg-light">
    <div class="container">
        <div class="structure-hero pt-lg-5 pt-4">
            <h1 class="titre text-center">Ajouter un collaborateur</h1>
            <p class="text text-center">
                Remplissez le formulaire pour ajouter un nouveau collaborateur à votre secteur.
            </p>
            <?php if (isset($_REQUEST["erreur"])) {
                echo '<p class="alert alert-danger text-center w-100">' . $_REQUEST["erreur"] . '</p>';
            } ?>
        </div>

        <div class="row align-items-center justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 py-4">
                <form method="post" action="index.php?uc=collaborateur&action=confirmeAjouter">

                    <div class="mb-3">
                        <label class="form-label">Matricule</label>
                        <input class="form-control" type="text" name="matricule" value="<?php echo isset($_REQUEST['matricule']) ? htmlspecialchars($_REQUEST['matricule']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input class="form-control" type="text" name="nom" value="<?php echo isset($_REQUEST['nom']) ? htmlspecialchars($_REQUEST['nom']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input class="form-control" type="text" name="prenom" value="<?php echo isset($_REQUEST['prenom']) ? htmlspecialchars($_REQUEST['prenom']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rue</label>
                        <input class="form-control" type="text" name="rue" value="<?php echo isset($_REQUEST['rue']) ? htmlspecialchars($_REQUEST['rue']) : ''; ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Code Postal</label>
                            <input class="form-control" type="text" name="code_postal" value="<?php echo isset($_REQUEST['code_postal']) ? htmlspecialchars($_REQUEST['code_postal']) : ''; ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Ville</label>
                            <input class="form-control" type="text" name="ville" value="<?php echo isset($_REQUEST['ville']) ? htmlspecialchars($_REQUEST['ville']) : ''; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date d'embauche</label>
                        <input class="form-control" type="date" name="date_embauche" value="<?php echo isset($_REQUEST['date_embauche']) ? htmlspecialchars($_REQUEST['date_embauche']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Habilitation</label>
                        <select name="habilitation" id="habilitation" class="form-control" required>
                            <option value="">-- Sélectionner une habilitation --</option>
                            <?php foreach ($habilitationsSubordonner as $habilitation) { ?>
                            <option value="<?php echo $habilitation[0] ?>" <?php echo (isset($_REQUEST['habilitation']) && $_REQUEST['habilitation'] == $habilitation[0])? "selected" : "" ?>><?php echo htmlspecialchars($habilitation[1])  ?></option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="d-flex justify-content-between">
                        <a class="btn btn-secondary" href="index.php?uc=collaborateur&action=listCollaborateurs">Annuler</a>
                        <button class="btn btn-primary" type="submit" name="save">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>