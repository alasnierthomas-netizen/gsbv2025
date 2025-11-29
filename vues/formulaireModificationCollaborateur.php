<section class="bg-light">
    <div class="container">
        <div class="structure-hero pt-lg-5 pt-4">
            <h1 class="titre text-center">Modifier le profil</h1>
            <p class="text text-center">
                <?php echo (isset($infoPersonnelle) && $infoPersonnelle) ? "Modification de vos informations personnelles." : "Modification des informations du collaborateur."; ?>
            </p>
            <?php if (isset($_REQUEST["erreur"])) {
                echo '<p class="alert alert-danger text-center w-100">' . $_REQUEST["erreur"] . '</p>';
            } ?>
        </div>

        <div class="row align-items-center justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 py-4">
                <form method="post" action="index.php?uc=collaborateur&action=confirmeModifier&collaborateur=<?php echo isset($info[0]) ? urlencode($info[0]) : ''; ?>">
                    <input type="hidden" name="collaborateur" value="<?php echo isset($info[0]) ? htmlspecialchars($info[0]) : ''; ?>">

                    <div class="mb-3">
                        <label class="form-label">Matricule</label>
                        <input class="form-control" type="text" name="matricule" value="<?php echo isset($info[0]) ? htmlspecialchars($info[0]) : ''; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input class="form-control" type="text" name="nom" value="<?php echo isset($info[1]) ? htmlspecialchars($info[1]) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input class="form-control" type="text" name="prenom" value="<?php echo isset($info[2]) ? htmlspecialchars($info[2]) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rue</label>
                        <input class="form-control" type="text" name="rue" value="<?php echo isset($info[3]) ? htmlspecialchars($info[3]) : ''; ?>">
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Code Postal</label>
                            <input class="form-control" type="text" name="code_postal" value="<?php echo isset($info[4]) ? htmlspecialchars($info[4]) : ''; ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Ville</label>
                            <input class="form-control" type="text" name="ville" value="<?php echo isset($info[5]) ? htmlspecialchars($info[5]) : ''; ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date d'embauche</label>
                        <input class="form-control" type="date" name="date_embauche" value="<?php echo isset($info["date_embauche"]) ? htmlspecialchars($info["date_embauche"][2]."-".((strlen($info["date_embauche"][1]) == 1)? "0".$info["date_embauche"][1] : $info["date_embauche"][1])."-".((strlen($info["date_embauche"][0]) == 1)? "0".$info["date_embauche"][0] : $info["date_embauche"][0])) : ''; ?>">
                    </div>
                    <?php if ($droitDeModificationMajeur) { ?>
                        <div class="mb-3">
                            <label class="form-label">Habilitation</label>
                            <select name="habilitation" id="habilitation" class="form-control">
                            <?php foreach ($habilitationsSubordonner as $habilitation) { ?>
                            <option value="<?php echo $habilitation[0] ?>" <?php echo (isset($info["habilitation"]) && $info["habilitation"] == $habilitation[1])? "selected" : "" ?>><?php echo htmlspecialchars($habilitation[1])  ?></option>
                            <?php } ?>
                        </select>
                        </div>

                    <?php } ?>

                    <div class="d-flex justify-content-between">
                        <a class="btn btn-secondary" href="index.php?uc=collaborateur&action=afficher&collaborateur=<?php echo isset($info[0]) ? urlencode($info[0]) : ''; ?>">Annuler</a>
                        <button class="btn btn-primary" type="submit" name="save">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>