<?php

require_once('../inc/init.php');
$title='Gestion des vehicules';

// Controle des autorisations
if(!isAdmin()){
    header('location:'. URL . 'connexion.php');
    exit();
}

// 5 - Suppression d'un vehicule 
if( isset($_GET['action']) && $_GET['action']=='sup' && !empty($_GET['id'])){
    // Je vais chercher la photo du vehicule
    $resultat = execRequete("SELECT photo FROM vehicule WHERE id_vehicule=:id",array('id' => $_GET['id']));
    // si je trouve le vehicule 
    if( $resultat->rowCount() > 0 ){
        $vehicule = $resultat->fetch();
        // si le champ photo est renseigné
        if(!empty($vehicule['photo'])){
            $fichier = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/' . $vehicule['photo'];
            if(file_exists($fichier)){
                // suppression de la photo
                unlink($fichier);
            }
        }
    }
    // Supression en BDD
    execRequete("DELETE FROM vehicule WHERE id_vehicule=:id",array('id' => $_GET['id']));
    $content .= '<div class="alert alert-success">Le vehicule a été supprimé</div>';
    $_GET['action']= 'affichage';
}


// 3- enregistrement d'un vehicule en BDD (ajout et en modif)
if(!empty($_POST) ){

    // contrôles 
    $nb_champs_vides = 0;
    foreach($_POST as $value){
        if($value == '') $nb_champs_vides++;
    }
    if($nb_champs_vides > 0){
        $content .= '<div class="alert alert-danger">Merci de remplir les ' .$nb_champs_vides. ' information(s) manquante(s)</div>';
    }

    // gerer la photo 
    $photo_bdd = $_POST['photo_courante'] ?? '';


    if(!empty($_FILES['photo']['name'])){
        $photo_bdd = $_POST['titre']. '_' . $_FILES['photo']['name'];
        $dossier_photo = $_SERVER['DOCUMENT_ROOT'] . URL .'photo/';
        $ext_auto = ['image/jpeg','image/png','image/gif'];

        if(in_array($_FILES['photo']['type'], $ext_auto)){
            move_uploaded_file($_FILES['photo']['tmp_name'],$dossier_photo.$photo_bdd);
        }
        else{
            $content .= '<div class="alert alert-danger"> La photo n\a pas été enregistrée. Format acceptés : jpeg, png, gif </div>';
        } 
    }
   if(empty($content)){
       extract($_POST);
       if($id_vehicule== 0){
       execRequete("INSERT INTO vehicule VALUES (NULL,:id_agence,:titre,:marque,:modele,:description,:photo,:prix_journalier)",array(
        'id_agence' => $id_agence,
        'titre' => $titre,
        'marque' => $marque,
        'modele' => $modele,
        'description' => $description,
        'photo' => $photo_bdd,
        'prix_journalier' => $prix_journalier
       ));
       $content .= '<div class="alert alert-success"> Le vehicule a été enregistré </div>';
       $_GET['action'] = 'affichage';
    }
    else{
        execRequete("UPDATE vehicule SET titre=:titre,marque=:marque,modele=:modele,description=:description,photo=:photo,prix_journalier=:prix_journalier WHERE id_vehicule=:id_vehicule",array(
            'id_vehicule' => $id_vehicule,
            'titre' => $titre,
            'marque' => $marque,
            'modele' => $modele,
            'description' => $description,
            'photo' => $photo_bdd,
            'prix_journalier' => $prix_journalier
           ));
           $content .= '<div class="alert alert-success"> Le vehicule a été mis à jour </div>';
    }
       $_GET['action'] = 'affichage';
   } 
}

require_once('../inc/header.php');
echo $content;
// page gestion des vehicules

// 1- Onglets pour affichage / ajout-modif vehicule
?>
<!--
<ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
  //  <a class="nav-link < ?= ( (!isset($_GET['action']) || (isset($_GET//['action']) && $_GET['action'] == 'affichage')) ? 'active': '' )?>" href="?action=affichage">Affichage des vehicules</a>
    </li>
    <li class="nav-item">
 //   <a class="nav-link < ?= ( (!isset($_GET['action']) || (isset($_GET//['action']) && $_GET['action'] == 'ajout' || $_GET['action']== 'edit')) ? 'active': '' )?>" href="?action=ajout">Ajouter un vehicule</a>
    </li>
</ul>

<?php
// 4. Affichage des vehicule en BDD


//if(!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage')){

//    $resultat = execRequete("SELECT * FROM vehicule");

//    if($resultat->rowCount() == 0){
        ?>
        <div class="alert alert-warning">Il n'ya pas encore de vehicules enregistrés</div>
        
        <?php
 //   }
//    else{
        ?>
        <p> Il y a < ?= $resultat->rowCount() ?> vehicule(s) dans la boutique</p>
                <?php

        // Générer les catégories
    ?>
-->
    <!-- filtre agence vehicule -->

    <ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
    <a class="nav-link <?= ( (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage')) ? 'active': '' )?>" href="?action=affichage">Affichage des vehicules</a>
    </li>
    <li class="nav-item">
    <a class="nav-link <?= ( (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'ajout' || $_GET['action']== 'edit')) ? 'active': '' )?>" href="?action=ajout">Ajouter un vehicule</a>
    </li>
</ul>

    <?php
$agences = execRequete ("
      SELECT id_agence, ville 
      FROM agence 
      ");
  
if ( !isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage') ) {
     
  if (isset($_GET['ville'])) {

    if ( $_GET['ville'] =='all' )  {
      $whereclause = '';
    }else{

      $ville = $_GET['ville'];
      $whereclause = "AND a.ville = '$ville'";

      }
    }
  else{
    $whereclause = '';
  }
  
  $resultat = execRequete(
  
    "SELECT v.id_vehicule,a.ville,v.titre,v.marque,v.modele,v.description,v.photo,v.prix_journalier
     FROM
     vehicule v, agence a 
     WHERE v.id_agence = a.id_agence
     $whereclause
     "

     );


   if ( $resultat->rowCount() == 0 ){
    ?>
    <div class="alert alert-warning">Il n'y a pas encore de vehicules enregistrés</div>
    <?php
  }
  else{


    ?>
    <h5 class="">Gestion des véhicules</h5>
    <p class="">Nous avons <?= $resultat->rowCount() ?> vehicule(s) <?= (empty($ville)) ? '' : "à " . $ville ?></p>

   
<div class="input-group mb-3">
    <form action="" method="get" class="d-flex w-25">  
    
    <select class="custom-select mr-2" name="ville" id="ville" class="form-control" onchange ="this.form.submit();">
            <option value="all" >Toute les agences</option>
           <?php
           while($agence = $agences->fetch()){       
          ?>

            <option value="<?= $agence['ville']?>"<?= (isset($_GET['ville']) && $agence['ville'] == $_GET['ville']) ? 'selected' : '' ?>><?= $agence['ville']?></option>
   
          <?php 
           }
        ?>

    </select>
      <!-- <div class="input-group-append form-group">      

        <input type="submit" class="btn btn-dark input-group-btn " name="selectVille"  value="Selectionner">

      </div> -->
     </form> 
      </div>
     <!-- fin de filtre agence vehicule  -->

<table class="table table-bordered table-striped">
<tr>

    <?php
    for($i=0;$i<$resultat->columnCount();$i++){

        $colonne = $resultat->getColumnMeta($i);

    ?>
    <th><?= ucfirst($colonne['name']) ?></th>
<?php
    }
    ?>
    <th colspan="3">Actions</th>
</tr>
<?php
// donnée de la table vehicule 
while($ligne = $resultat->fetch()){
    ?>
    <tr>
    <?php 
    foreach($ligne as $key => $value){
        if($key == 'photo'){
            $value = '<img class="img-fluid" src="'.URL.'photo/'.$value.'" alt="'.$ligne['titre'].'">';
        }
        ?>
        <td><?= $value ?></td>
        <?php
    }
    ?>
    <td><a href="?action=edit&id=<?= $ligne['id_vehicule'] ?>"><i class="fas fa-pen"></i></a></td>
    <td><a class="confirm"  href="?action=sup&id=<?= $ligne['id_vehicule']?>"><i class="fas fa-trash"></i></a></td>

    </tr>
    <?php
}
?>
</table>
<?php
}
}



// 2. formulaire ajout/modif de vehicule

if(isset($_GET['action']) && ($_GET['action']=='ajout'|| $_GET['action']=='edit') ):

// 6 - chargement d'un vehicule en edition 
if(!empty($_GET['id'])){
    $resultat = execRequete("SELECT * FROM vehicule WHERE id_vehicule=:id",array('id' => $_GET['id']));
    $vehicule_courant = $resultat->fetch();
}

?>
<form method="post" action="" enctype="multipart/form-data">
   <input type="hidden" name="id_vehicule" value="<?= $_POST['id_vehicule'] ?? $vehicule_courant['id_vehicule'] ?? 0 ?>">

    <div class="form-row">
       <div class="form-goup col-6">
        <label for="titre">Titre</label>
     <input type="text" name="titre" id="titre" class="form-control" value="<?= $_POST['titre'] ?? $vehicule_courant['titre'] ?? '' ?>">
     </div>

    <div class="form-goup col-6"> 
    <label for="id_agence">Agence</label>
    <select name="id_agence" class="custom-select" id="inputGroupSelect01" value="<?= $_POST['id_agence'] ?? $vehicule_courant['id_agence'] ?? '' ?>"> 
    <option  id="id_agence">1</option>
    <option  id="id_agence">2</option>
    <option  id="id_agence">3</option>
  </select>
  </div>

</div>
</div>
<div class="form-row">
    <div class="form-goup col-6">
        <label for="marque">Marque</label>
        <input type="marque" name="marque" id="marque" class="form-control" value="<?= $_POST['marque'] ?? $vehicule_courant['marque'] ?? '' ?>">
    </div>
    <div class="form-goup col-6">
        <label for="modele">Modele</label>
        <input type="modele" name="modele" id="modele" class="form-control" value="<?= $_POST['modele'] ?? $vehicule_courant['modele'] ?? '' ?>">
    </div>
</div>

    <div class="form-goup">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control"><?= $_POST['description'] ?? $vehicule_courant['description'] ?? '' ?></textarea>
    </div>
    <br>
       <div class="form-group">
           <label for="photo"><i class="fa fa-camera"></i> <span id="fichier"></span>
        </label>
           <input type="file" name="photo" id="photo" class="form-control">
           <?php
           if(!empty($vehicule_courant['photo'])){
               ?>
               <em>Vous pouvez uploader une nouvelle photo</em>
               <img src="<?= URL . 'photo/' . $vehicule_courant['photo'] ?>" class="img-fluid w-25" alt="<?= $vehicule_courant['titre'] ?>">
               <input type="hidden" name="photo_courante" value="<?= $vehicule_courant['photo'] ?>">
             <?php
           }
           ?>
       </div>
       <div class="form-row">
           <div class="form-group col-1">
               <label for="prix_journalier">Prix</label>
               <input type="number" name="prix_journalier" id="prix_journalier" class="form-control" value="<?= $_POST['prix_journalier'] ?? $vehicule_courant['prix_journalier'] ?? '' ?>">
           </div>
       </div>
       <input type="submit" class="btn btn-primary" value="Enrengistrer">
</form>


<?php

endif;

require_once('../inc/footer.php');
