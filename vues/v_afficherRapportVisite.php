<section class="container mt-5">
    <h1 class="text-center mb-4">Historique des rapports de visite</h1>

    <!-- Formulaire de recherche -->
    <form method="POST" action="index.php?uc=rapportdevisite&action=rechercher" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="dateDebut" class="form-label">Date début :</label>  <!-- Pour entrer la date de début -->
            <input type="date" id="dateDebut" name="dateDebut" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="dateFin" class="form-label">Date fin :</label>  <!-- Pour entrer la date de fin -->
            <input type="date" id="dateFin" name="dateFin" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="medicament" class="form-label">médicament :</label>  <!-- Liste déroulante des médicaments -->
            <select id="medicament" name="medicament" class="form-select">
                <option value="">Tous les médicaments</option>
                <?php foreach ($medicaments as $medicament): ?>
                    <option value="<?= $medicament[0] ?>">
                        <?= htmlspecialchars($medicament[1]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-info text-white w-100">Rechercher</button>
        </div>
    </form>

    <!-- Résultats -->
    <?php if (empty($rapports)): ?>
            Aucun rapport trouvé pour les critères indiqués.

    <?php else: ?>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark text-center">
                <tr>
                    <th>Numéro rapport</th>
                    <th>date de la visite</th>
                    <th>Nom du praticien</th>
                    <th>bilan de la visite</th>
                    <th>nom du collaborateur</th>
                    <th>Nom du médicament</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rapports as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['RAP_NUM']) ?></td>  <!-- Pour éviter les failles XSS -->
                        <td><?= htmlspecialchars($r['RAP_DATEVISITE']) ?></td>  <!-- Pour éviter les failles XSS -->
                        <td><?= htmlspecialchars($r['PRA_PRENOM'] . ' ' . $r['PRA_NOM']) ?></td>  <!-- Pour éviter les failles XSS -->
                        <td><?= htmlspecialchars($r['RAP_BILAN'] ?? '-') ?></td>  <!-- Pour éviter les failles XSS -->
                        <td><?= htmlspecialchars($r['COL_PRENOM'] . ' ' . $r['COL_NOM']) ?></td>  <!-- Pour éviter les failles XSS -->
                        <td><?= htmlspecialchars($r['MED_DEPOTLEGAL_PRES1'] ?? '-') ?></td>  <!-- Pour éviter les failles XSS -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>