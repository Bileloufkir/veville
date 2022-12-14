<?php
require_once('inc/init.php');



if( empty($_GET['id_vehicule']) ){
    header('location:' .URL);
    exit();
}

$resultat = execRequete("SELECT * FROM vehicule WHERE id_vehicule=:id_vehicule",array('id_vehicule' => $_GET['id_vehicule']));
if( $resultat->rowCount() == 0){
    header('location:' . URL);
    exit();
}

$vehicule = $resultat->fetch();
$title= $vehicule['titre'];
require_once('inc/header.php');
?>

<div class="row">
<div class="col">
<h1 class="page-header text-center"><?= $vehicule['titre']?></h1>
<hr>
<div class="row">
<div class="col-6 border">
<img class="img-fluid" src="<?= URL . 'photo/' . $vehicule['photo'] ?>" alt="<?= $vehicule['titre']?>">
</div>
<div class="col-6">
<h3>Déscription</h3>
<p><?= $vehicule['description']?></p>
<h3>Détail</h3>
<ul>
<li>Marque : <?= $vehicule['marque'] ?></li>
<li>Modèle : <?= $vehicule['modele'] ?></li>
<li>Transmission : Automatique</li>
</ul><br>
<p class="lead">prix : <?= $vehicule['prix_journalier']?> €/jour</p>
<?php

// ------- REQUETE POUR RESERVATION A EFFECTUER 

    ?>

    <form action="panier.php" method="post">
    <input type="hidden" name="id_vehicule" value="<?= $vehicule['id_vehicule'] ?>">
    <div class="form-row">
    <div class="form-group col-4">
    <!-- <select name="" class="form-control"> -->

    </select>
    </div>
    <div class="form-group col-4">
    <input type="submit" name="ajout_panier" value="Reservez" class="btn btn-dark">
    </div>
    </div>

    </form>

    </div>
    </div>
    </div>
    </div>

<?php
if(isset($_GET['statut_vehicule']) && $_GET['statut_vehicule'] == 'ajoute'):
?> 
<div class="modal fade" id="maModale" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Le vehicule a été reservez</h4>
            </div>
            <div class="modal-body">
            <a href="<?= URL . 'panier.php' ?>" class="btn btn-primary">Voir mes reservation</a>
            <a href="<?= URL . 'index.php'?>" class="btn btn-primary">Continuer ses achats</a>
            </div>
        </div>
    </div>
</div>

 <?php

endif; 

require_once('inc/footer.php');