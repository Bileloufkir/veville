<?php
require_once('inc/init.php');
$title = 'Booking';
require_once('inc/header.php');
$arg = array();
$date = $_POST['startAt'];

 $sql = "SELECT *
 FROM vehicule
 LEFT JOIN commande ON vehicule.id_vehicule = commande.id_vehicule 
 WHERE commande.date_heure_fin < $date OR commande.id_vehicule IS NULL";

 $vehicules = execRequete($sql);
 ?>
      <h2 class="titreindex">Nos voitures disponible du <span class="font-weight-bold"><?= $_POST['startAt'] ?></span> au <span class="font-weight-bold"><?= $_POST['endAt'] ?></span> </h2>

  <div class="row indexcar">
      <?php
          while( $prd = $vehicules->fetch() ):
            ?>
            <div class="col-6 pt-2">
              <div class="border">
                <div class="thumbnail">
                  <a href="fiche_vehicule.php?id_vehicule=<?= $prd['id_vehicule'] ?>">
                    <img src="<?= URL . 'photo/' . $prd['photo'] ?>" alt="<?= $prd['titre'] ?>" class="img-fluid" style="width:700px;">
                  </a>
                </div>
                <div class="caption mx-2">
                  <h6 class="float-right"><?= $prd['prix_journalier'] ?>€/Jour</h6>
                  <h5><a href="fiche_vehicule.php?id_vehicule=<?= $prd['id_vehicule'] ?>"><?= $prd['titre'] ?></a></h5>
                </div>
              </div>
            </div>
            <?php
          endwhile;
      ?>
    </div>

    <?php
    require_once('inc/footer.php');