
<?php

require_once('inc/init.php');
$title = 'Accueil';


require_once('inc/header.php');

// définition du nombre d'éléments par page
$nb_par_page = 6;
// connaitre le nbre total d'éléments à afficher
$resultat = $pdo->query("SELECT COUNT(*) as id_vehicule FROM vehicule");
$nb_employes = $resultat->fetch()['id_vehicule'];
// calcul du nombre de pages
$nbpages = $nb_employes / $nb_par_page;
if ($nb_employes % $nb_par_page > 0) $nbpages++;

// on récupère l'éventuel début de ma LIMIT dans l'url (par défaut 0)
if( !isset($_GET['debut']) ){
  $debut = 0;
}
else{
  $debut = intval($_GET['debut']);
}
// je fais ma réquête en tenant compte de mes limites
$resultat = $pdo->prepare("SELECT * FROM vehicule LIMIT :debut, :nb");
$resultat->bindValue('debut',$debut,PDO::PARAM_INT);
$resultat->bindValue('nb',$nb_par_page,PDO::PARAM_INT);
$resultat->execute();


?>
<?php
// je parcoure mes résultats
while($employe = $resultat->fetch()){
  // ?>
  <!-- <p>< ?= $employe['titre'] ?> < ?= $employe['marque'] ?> < ?= $employe['prix_journalier'] ?> < ?= $employe['photo'] ?> </p> -->

  <div class="row indexcar">
            <div class="col-6 pt-2">
              <div class="border">
                <div class="thumbnail">
                  <a href="fiche_vehicule.php?id_vehicule=<?= $employe['id_vehicule'] ?>">
                    <img src="<?= URL . 'photo/' . $employe['photo'] ?>" alt="<?= $employe['titre'] ?>" class="img-fluid" style="width:700px;">
                  </a>
                </div>
                <div class="caption mx-2">
                  <h6 class="float-right"><?= $employe['prix_journalier'] ?>€/Jour</h6>
                  <h5><a href="fiche_vehicule.php?id_vehicule=<?= $employe['id_vehicule'] ?>"><?= $employe['titre'] ?></a></h5>
                </div>
              </div>
              </div>
<?php
              }
?>
            </div>
          </div>

  <?php


// liens de pagination
$incrementation=0;
for($i=1 ; $i <= $nbpages; $i++){
  ?>
  <a href="?debut=<?= $incrementation ?>">Page <?= $i ?></a>
  <?php
  $incrementation += $nb_par_page;
}


require_once('inc/footer.php');