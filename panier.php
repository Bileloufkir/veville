<?php

require_once('inc/init.php');
$title = 'Panier';

if(isset($_POST['ajout_panier'])){
   //je sais qu'il s'agit d'un ajout au panier
$resultat = execRequete("SELECT prix_journalier FROM vehicule WHERE id_vehicule=:id_vehicule",array('id_vehicule' => $_POST['id_vehicule']));
if ($resultat->rowCount()>0){
   $vehicule = $resultat->fetch();
   addPanier($_POST['id_vehicule'],$vehicule['prix_journalier']);
   header('location:'. URL . 'fiche_vehicule.php?id_vehicule='. $_POST['id_vehicule'] . '&statut_vehicule=ajoute');
   exit();

}

}

//suppression d'une ligne du panier
if (isset($_GET['action']) && $_GET['action']=='sup' && !empty($_GET['id_vehicule'])){
    removePanier($_GET['id_vehicule']);
 }

// vider le panier
if(isset($_GET['action']) && $_GET['action']== 'vider'){
    unset($_SESSION['panier']);
}


//valider le panier  (=transformation en commande)

if(isset($_GET['action']) && $_GET['action']== 'valider' && isConnected() ){
    $feu_rouge = false;

    // controle du panier avant commande 
    for($i=0 ; $i < count($_SESSION['panier']['id_vehicule']); $i++ ){
        $resultat = execRequete("SELECT * FROM vehicule WHERE id_vehicule=:id_vehicule",array('id_vehicule' => $_SESSION['panier']['id_vehicule'][$i]));
        $vehicule = $resultat-> fetch();
        // if($_SESSION['panier']['quantite'][$i] > 10 ) $feu_rouge = true ;
        // if($vehicule['stock'] < $_SESSION['panier']['quantite'][$i]) $feu_rouge = true;
        if($vehicule['prix_journalier'] != $_SESSION['panier']['prix_journalier'][$i]) $feu_rouge = true;
    }

    // if($feu_rouge === false){
    //     // on procede à la commande 
    //     $id_membre = $_SESSION['membre']['id_membre'];
    //     $montant_total = montantPanier();
    //     execRequete("INSERT INTO commande VALUES (NULL,:id_membre,:montant,NOW(), 'en cours de traitement')",array(
    //         'id_membre' => $id_membre,
    //         'montant'=> $montant_total
    //     ));
        
        $id_commande = $pdo->lastInsertId();

        // on va boucler sur le panier pour alimenter la table details_commande et mettre a jour le stock 
        for($i=0; $i < count($_SESSION['panier']['id_vehicule']); $i++){
            $id_vehicule = $_SESSION['panier']['id_vehicule'][$i];
            //$quantite = $_SESSION['panier']['quantite'][$i];
            $prix_journalier = $_SESSION['panier']['prix_journalier'][$i];
            // on alimente details_commande

            // execRequete("INSERT INTO details_commande VALUES (NULL,:id_commande,:id_vehicule,:prix_journalier)",array(
            //     'id_commande' => $id_commande,
            //     'id_vehicule'=> $id_vehicule,
                
            //     'prix_journalier'=> $prix_journalier
            // ));

           // on décrémente le stock 
            // execRequete("UPDATE vehicule SET stock = stock - :quantite WHERE id_vehicule=:id_vehicule",array(
            //     'quantite'=> $quantite,
            //     'id_vehicule'=> $id_vehicule
            // ));
        }
        // detruire le panier aprés instruction 
        unset($_SESSION['panier']);
        // redirection sur la page 'mes commande '
        header('location:'.URL.'index.php');
        exit();
        }

    // else{
    //     $content .= '<div class="alert alert-danger">la commande n\'a pas été validée en raison de modifications concernant le stock ou le prix_journalier des articles. Merci de valider à nouveau aprés vérification </div>';
    // }
// }




require_once('inc/header.php');
echo $content;
// page du panier
?>
<h2>Vos réservation</h2>
<?php
if(empty($_SESSION['panier']['id_vehicule'])){
    ?>
<div class="alert alert-info">Aucune reservation ! </div>
    <?php
}
else{
    ?>
    <table class="table table-bordered table-striped">
    <tr>
    <th>Réference</th>
    <th>Titre</th>
    <!-- <th>Quantite</th> -->
    <th>prix_journalier</th>
    <!-- <th>Total</th> -->
    <th>Action</th>
    </tr>

 <?php
// Controles et réécriture eventuelle du panier 
    for( $i=0;$i < count($_SESSION['panier']['id_vehicule']);$i++):
        $resultat = execRequete ("SELECT * FROM vehicule WHERE id_vehicule=:id_vehicule",array('id_vehicule'=> $_SESSION['panier']['id_vehicule'][$i]));
        $vehicule = $resultat->fetch();
        $message = '' ;
        // if($_SESSION['panier']['quantite'][$i] > 10){
        // $_SESSION['panier']['quantite'][$i] = 10 ;
        // }
        // if($vehicule['stock'] < $_SESSION['panier']['quantite'][$i]){
        //     $_SESSION['panier']['quantite'][$i] = $vehicule['stock'];
        //     $message .= '<div class="alert alert-info">La quantite a été réajustée en fonction du stock et dans la limite de 10 artcles pas commande </div>';
        // }
        // if($_SESSION['panier']['prix_journalier'][$i] != $vehicule['prix_journalier']){
        //     $_SESSION['panier']['prix_journalier'][$i] = $vehicule['prix_journalier'];
        //     $message .= '<div class="alert alert-info">Le prix_journalier a été réactualisé</div>';
        // }
    ?>
    <tr>
     <td><a href="<?= URL . 'fiche_vehicule.php?id_vehicule=' . $_SESSION['panier']['id_vehicule'][$i] ?>"> <?= $vehicule['id_vehicule'] ?></a></td>
     <td><?=  $vehicule['titre'] . $message ?></td>
     <!-- <td>< ?=   $_SESSION['panier']['quantite'][$i] ?></td> -->
     <td><?=   $_SESSION['panier']['prix_journalier'][$i] ?></td>
     <!-- <td>< ?=   $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix_journalier'][$i] ?>€</td> -->
     <td><a href="?action=sup&id_vehicule=<?= $_SESSION['panier']['id_vehicule'][$i] ?>"><i class="fa fa-trash"></i></a></td>
    </tr>
        <?php
    endfor;
?>
    <tr class="bg-info">
    <th colspan="4" class="text-right">Total</th>
    <!-- <th colspan="2">< ?= montantPanier() ?></th> -->
    </tr>
    <?php
    if(isConnected() ){
        ?>
        <tr>
        <td colspan="6" class="text-center">
            <a href="?action=valider" class="btn btn-primary">Valider le panier</a>
        </td>
        </tr>
        <?php
    }
    else{
        ?>
        <tr>
        <td colspan="6" class="text-center">
            Veuillez vous <a href="<?= URL . 'inscription.php' ?>">inscrire </a> ou vous <a href="<?= URL . 'connexion.php' ?>">connecter</a> afin de valider votre reservation
        </td>
        </tr>
        <?php
    }

    ?>
    <tr>
    <td colspan="6" class="text-center">
        <a href="?action=vider" class="btn btn-warning">Vider le panier</a>
    </td>
    </tr>
    </table>
<?php
}
?>


<?php

require_once('inc/footer.php');