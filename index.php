<?php

require_once('inc/init.php');
$title = 'Accueil';

?>

<div class="container-fluid bg d-flex align-items-end flex-column">

  <div class="blocindex prems">
    <h1>Bienvenue à bord</h1>
  </div>
  <div class="blocindex">
    <h3>Location de voiture 24h/24 et 7j/7 </h3>
  </div>
  <!-- formulaire reservation Accueil -->
  <form class="col-11 form-group" action="booking.php" method="post">
    <div class=" form-group d-flex picker1">

      <div class='col-md-5'>
        <div class="form-group">
          <div class="input-group date inpout" id="datetimepicker7" data-target-input="nearest">
            <input type="text" name="startAt" class="form-control datetimepicker-input" data-target="#datetimepicker7" placeholder="date de début" />
            <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
            </div>
          </div>
        </div>
      </div>

      <div class='col-md-5'>
        <div class="form-group">

          <div class="input-group date inpout" id="datetimepicker8" data-target-input="nearest">
            <input type="text" name="endAt" class="form-control datetimepicker-input" data-target="#datetimepicker8" placeholder="date de fin" />
            <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
              <button type="submit" class="btn btn-dark mnbtn">Valider</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </form>

</div>
<?php
require_once('inc/header.php');

// ----------- PAGINATION  


// définition du nombre d'éléments par page
$nb_par_page = 6;
// connaitre le nbre total d'éléments à afficher
$resultat = $pdo->query("SELECT COUNT(*) as id_vehicule FROM vehicule");
$nb_employes = $resultat->fetch()['id_vehicule'];
// calcul du nombre de pages
$nbpages = $nb_employes / $nb_par_page;
if ($nb_employes % $nb_par_page > 0) $nbpages++;

// on récupère l'éventuel début de ma LIMIT dans l'url (par défaut 0)
if (!isset($_GET['debut'])) {
  $debut = 0;
} else {
  $debut = intval($_GET['debut']);
}
// je fais ma réquête en tenant compte de mes limites
$resultat = $pdo->prepare("SELECT * FROM vehicule LIMIT :debut, :nb");
$resultat->bindValue('debut', $debut, PDO::PARAM_INT);
$resultat->bindValue('nb', $nb_par_page, PDO::PARAM_INT);
$resultat->execute();

// Générer les catégories
$categories = execRequete("SELECT * FROM vehicule");
?>

<div class="col-12">

  <h1 class="titreindex">Nos voitures de luxe à la location</h1>


  <!-- tri prix par odre croissant ou decroissant  -->

  <?php
  if (isset($_POST['sort'])) {
    $sort =  $_POST['sort'];
    // echo $sort;  
  };

  $searchResultQuery = "SELECT * FROM vehicule ORDER BY prix_journalier ";
  if ($sort === "asc") {
    $searchResultQuery .= "ASC";
  } elseif ($sort === "desc") {
    $searchResultQuery .= "DESC";
  }

  $stmt = $pdo->prepare($searchResultQuery);
  $stmt->execute();
  $searchResult = $stmt->fetchAll();

  if (count($searchResult) > 0) {
    // process the result
  ?>
    <form method="post" class="form-inline" name="myform">
      <select name="sort" id="sort" onchange="javascript:document.myform.submit();">
        <option selected value="asc" <?= $sort == "asc" ? 'selected' : '' ?>>PrixCroissant</option>
        <option value="desc" <?= $sort == "desc" ? 'selected' : '' ?>>Prix Décroissant</option>
      </select>
    </form>
    <div class="row indexcar">
      <?php
      foreach ($searchResult as $value) {
        // while ($prd = $searchResult->fetchAll()) :
      ?>
        <div class="col-6 pt-2">
          <div class="border">
            <div class="thumbnail">
              <a href="fiche_vehicule.php?id_vehicule=<?= $value['id_vehicule'] ?>">
                <img src="<?= URL . 'photo/' . $value['photo'] ?>" alt="<?= $value['titre'] ?>" class="img-fluid" style="width:700px;">
              </a>
            </div>
            <div class="caption mx-2">
              <h6 class="float-right"><?= $value['prix_journalier'] ?>€/Jour</h6>
              <h5><a href="fiche_vehicule.php?id_vehicule=<?= $value['id_vehicule'] ?>"><?= $value['titre'] ?></a></h5>
            </div>
          </div>
        </div>
      <?php
        // endwhile;
      }
      ?>
    </div>
  <?php
  } else {
    echo "No results found.";
  }

  ?>


  <!-- <button type="submit" class="btn btn-outline-secondary btn-sm">Validé</button>  -->
  <?php

  // Afficher les vehicules de la boutique
  $whereclause = '';
  $arg = array();

  // Eventuel filtre sur la categ

  //$vehicules = execRequete("SELECT * FROM vehicule $whereclause",$arg);

  // natsort($vehicules);
  ?>
  <!-- <div class="row indexcar">
    <?php
    while ($prd = $resultat->fetch()) :
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
  </div> -->
</div>

<?php
require_once('inc/footer.php');

// liens de pagination

$incrementation = 0;


// for ($i = 1; $i <= $nbpages; $i++) {
// ?>
<!-- //   <div class="lienindex2">
//     <a class="lienindex page-link" href="?debut=<?= $incrementation ?>">Page <?= $i ?></a>
//   </div>
//   
//   

//  
//   $incrementation += $nb_par_page;
// }


<?php

require_once('inc/footer.php');
