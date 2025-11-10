

<section class="bg-light">
    <div class="container">
        <div class="structure-hero pt-lg-5 pt-4">
            <h1 class="titre text-center">Profil</h1>
            <p class="text text-center">
                <?php echo (isset($infoNonPersonnelle) && $infoNonPersonnelle)? "informations du collaborateur : " . $info[1] . " " . $info[2] : "informations personnelles."; ?>
            </p>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5">
                <img class="img-fluid w-75" src="assets/img/profil.png">
            </div>
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5 py-3">
                <div class="formulaire">

                    <p><span class="carac">Matricule</span> : <?php echo htmlspecialchars($info[0]) ?></p>
                    <p><span class="carac">Nom</span> : <?php echo htmlspecialchars($info[1]) ?></p>
                    <p><span class="carac">Prenom</span> : <?php echo htmlspecialchars($info[2]) ?></p>
                    <p><span class="carac">Rue</span> : <?php echo htmlspecialchars($info[3]) ?></p>
                    <p><span class="carac">Code Postal</span> : <?php echo htmlspecialchars($info[4]) ?></p>
                    <p><span class="carac">Ville</span> : <?php echo htmlspecialchars($info[5]) ?></p>
                    <p><span class="carac">Date d'embauche</span> : <?php echo htmlspecialchars($info[6]) ?></p>
                    <p><span class="carac">Habilitation</span> : <span style="color:#0DCAF0;font-weight: 700;"> <?php echo htmlspecialchars($info[7]) ?></span></p>
                    <p><span class="carac">Secteur</span> : <?php echo htmlspecialchars($info[8]) ?></p>
                    <p><span class="carac">Région</span> : <?php echo htmlspecialchars($info[9]) ?></p>

                <?php if (getDroit($_SESSION['login']) == 3) { ?>
                    <a href="index.php?uc=collaborateur&action=modifier&matricule=<?php echo htmlspecialchars($info[0]);?>">
                    <button class="btn btn-info text-light valider" type="submit" >modfifier</button>
                    </a>
                <?php } ?>
                </div>
            </div>
        </div>
</section>