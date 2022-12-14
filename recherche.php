<?php


require_once('inc/init.php');
$title="Recherche";
// $_SESSION['membre']['statut']=1;

    if(! empty($_POST['critere'])){
        $vehicules = execRequete("SELECT * FROM vehicule
            WHERE titre LIKE CONCAT('%',:critere,'%')
            OR description LIKE CONCAT('%',:critere,'%')
            OR marque LIKE CONCAT('%',:critere,'%')
            OR modele LIKE CONCAT('%',:critere,'%')" ,array('critere' => $_POST['critere']));
           // OR couleur LIKE CONCAT('%',:critere,'%')
        $nb_resultats = $vehicules->rowCount();
    }


            
    else{
        header('location:' . URL);
        exit();
    }


require_once('inc/header.php');

// corps de la page Recherche

?>
<h2>Recherche de <?= $_POST['critere'] ?></h2>

    <?php
    if($nb_resultats > 0){
        // on a trouvé des vehicules
        ?>
<h3>Nous avons trouvé <?= $nb_resultats ?> vehicule(s)</h3>
    <div class="row">
        <?php
        while( $prd = $vehicules->fetch() ):
                ?>
                <div class="col-4 p-1">
                  <div class="border">
                    <div class="thumbnail">
                      <a href="fiche_vehicule.php?id_vehicule=<?= $prd['id_vehicule'] ?>">
                        <img src="<?= URL . 'photo/' . $prd['photo'] ?>" alt="<?= $prd['titre'] ?>" class="img-fluid">
                      </a>
                    </div>
                    <div class="caption mx-2">
                      <h4 class="float-right"><?= $prd['prix_journalier'] ?>€</h4>
                      <h4><a href="fiche_vehicule.php?id_vehicule=<?= $prd['id_vehicule'] ?>"><?= $prd['titre'] ?></a></h4>
                    </div>
                  </div>
                </div>
            <?php
        endwhile;
        ?>
    </div>
        <?php
    }else{
    
    ?>
    <div class="alert alert-info">Il n'y a pas de vehicules correspondant à votre recherche</div>
    <?php
    }

require_once('inc/footer.php');