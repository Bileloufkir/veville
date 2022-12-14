<?php
require_once('inc/init.php');



if( empty($_GET['id_produit']) ){
    header('location:' .URL);
    exit();
}

$resultat = execRequete("SELECT * FROM produit WHERE id_produit=:id_produit",array('id_produit' => $_GET['id_produit']));
if( $resultat->rowCount() == 0){
    header('location:' . URL);
    exit();
}

$produit = $resultat->fetch();
$title= $produit['titre'];
require_once('inc/header.php');
?>
<div class="row">
<div class="col">
<h1 class="page-header text-center"><?= $produit['titre']?></h1>
<div class="row">
<div class="col-8">
<img class="img-fluid" src="<?= URL . 'photo/' . $produit['photo'] ?>" alt="<?= $produit['titre']?>">
</div>
<div class="col-4">
<h3>Déscription</h3>
<p><?= $produit['description']?></p>
<h3>Détail</h3>
<ul>
<li>Catégorie : <?= $produit['categorie'] ?></li>
<li>Couleur : <?= $produit['couleur'] ?></li>
<li>Taille : <?= $produit['taille'] ?></li>
</ul>
<p class="lead"><?= $produit['prix']?>€</p>
<?php

if($produit['stock'] > 0){
    // formulaire pour mettre le produit dans le panier
    ?>
    <?= ($produit['stock'] > 5 ) ? '<div class="text-success">En stock</div>' : ' <div class="text-warning"> Plus que ' .$produit['stock'] . ' exemplaire(s) en stock </div>' ?>
    <form action="panier.php" method="post">
    <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
    <div class="form-row">
    <div class="form-group col-4">
    <select name="quantite" class="form-control">
    <?php
    for($i=1; $i<=$produit['stock'] && $i <= 10 ; $i++){
        ?>
        <option><?= $i ?></option>
        <?php
    }
    ?>
    </select>
    </div>
    <div class="form-group col-4">
    <input type="submit" name="ajout_panier" value="Ajouter au panier" class="btn btn-primary">
    </div>
    </div>

    </form>
    <?php
}
else
{
    ?>
    <p class="alert alert-warning">Produit indisponible</p>
    <?php
}
?>
</div>
</div>
</div>
</div>
<?php
if(isset($_GET['statut_produit']) && $_GET['statut_produit'] == 'ajoute'):
?>
<div class="modal fade" id="maModale" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Le produit a été ajouté au panier</h4>
            </div>
            <div class="modal-body">
            <a href="<?= URL . 'panier.php' ?>" class="btn btn-primary">Voir le Panier</a>
            <a href="<?= URL ?>" class="btn btn-primary">Continuer ses achats</a>
            </div>
        </div>
    </div>
</div>

<?php

endif;

require_once('inc/footer.php');