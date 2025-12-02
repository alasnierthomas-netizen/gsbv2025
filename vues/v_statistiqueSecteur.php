<section class="container mt-5">
    <h1 class="text-center mb-4">Statistiques des médicaments du secteur <?php echo $secteur[1] ?></h1>

    <!-- Formulaire de recherche -->
    <form method="POST" action="index.php?uc=secteur&action=rechercher" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="dateDebut" class="form-label">Date début :</label>  <!-- Pour entrer la date de début -->
            <input type="date" id="dateDebut" name="dateDebut" class="form-control" <?php echo (isset($_REQUEST['dateDebut']))? "value=\"".$_REQUEST['dateDebut']."\"" : "" ?>>
        </div>
        <div class="col-md-3">
            <label for="dateFin" class="form-label">Date fin :</label>  <!-- Pour entrer la date de fin -->
            <input type="date" id="dateFin" name="dateFin" class="form-control" <?php echo (isset($_REQUEST['dateFin']))? "value=\"".$_REQUEST['dateFin']."\"" : "" ?>>
        </div>
        <div class="col-md-3">
            <label for="medicament" class="form-label">médicament :</label>  <!-- Liste déroulante des médicaments -->
            <select id="medicament" name="medicament" class="form-select">
                <option value="">Tous les médicaments</option>
                <?php foreach ($medicaments as $medicament): ?>
                    <option value="<?= $medicament[0] ?>" <?php echo (isset($_REQUEST['medicament']) && $_REQUEST['medicament'] == $medicament[0]) ? 'selected' : '' ; ?>>
                        <?= htmlspecialchars($medicament[1]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-info text-white w-100">Rechercher</button>
        </div>
    </form>

    <!-- Affichage les statistiques individuelle des médicament -->
    <div class="card shadow-sm mb-4">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 py-2">Médicament</th>
                            <th class="px-3 py-2 text-center">Présentés</th>
                            <th class="px-3 py-2 text-center">Offerts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicamentPresenterOuOffert as $med): ?>
                            <tr>
                                <?php
                                // Chercher le nom du médicament à partir de la clé
                                $nomMedicament = $med["medicament"];
                                foreach ($medicaments as $m) {
                                    if ($m[0] == $nomMedicament) {
                                        $nomMedicament = $m[1];
                                        break;
                                    }
                                } ?>

                                <td class="px-3 py-2"><?php echo htmlspecialchars($nomMedicament); ?></td>
                                <td class="px-3 py-2 text-center"><span class="badge bg-info text-dark px-3 py-2 fs-6"><?= intval($med["presentes"] ?? 0) ?></span></td>
                                <td class="px-3 py-2 text-center"><span class="badge bg-success px-3 py-2 fs-6"><?= intval($med["offert"] ?? 0) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Résultats: affichage des 2 statistiques -->
    <div class="row g-3 mt-4" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">Médicaments présentés</h5>
                    <p class="card-text display-4 text-primary fw-bold">
                        <?= intval($sommeMedicamentPresenter[0][0] ?? 0) ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title">Médicaments offerts</h5>
                    <p class="card-text display-4 text-success fw-bold">
                        <?= intval($sommeMedicamentOffert[0][0] ?? 0) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>