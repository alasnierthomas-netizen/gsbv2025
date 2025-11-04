<section class="bg-light">
    <div class="container">
        <div class="structure-hero pt-lg-5 pt-4">
            <h1 class="titre text-center">Rapport de visite <span class="carac"><?php echo $carac[1]; ?></span></h1>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5">
                <img class="img-fluid" src="assets/img/medoc.jpeg">
            </div>
            <div class="test col-12 col-sm-8 col-lg-6 col-xl-5 col-xxl-4 py-lg-5 py-3">
                <div class="formulaire">
                    <p><span class="carac">Collaborateur</span> : <?php echo $carac[0] ?></p>
                    <p><span class="carac">Date de visite</span> : <?php echo $carac[1] ?></p>
                    <p><span class="carac">Bilan</span> : <?php echo $carac[2] ?></p>
                    <p><span class="carac">Cout</span> : <?php echo $carac[3] ?></p>
                    <p><span class="carac">Etat</span> : <?php echo $carac[4] ?></p>
                    <p><span class="carac">Motif</span> : <?php echo $carac[5] . '€' ?></p>
                    <p><span class="carac">Praticien</span> : <?php echo $carac[6] ?></p>
                    <input class="btn btn-info text-light valider col-6 col-sm-5 col-md-4 col-lg-3" type="button" onclick="history.go(-1)" value="Retour">
                </div>
            </div>
        </div>
    </div>
</section>