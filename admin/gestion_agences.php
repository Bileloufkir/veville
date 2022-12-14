<?php

require_once('../inc/init.php');
$title='Gestion des agences';

// Controle des autorisations
if(!isAdmin()){
    header('location:'. URL . 'connexion.php');
    exit();
}

// 5 - Suppression d'une agence 
if( isset($_GET['action']) && $_GET['action']=='sup' && !empty($_GET['id'])){
    // Je vais chercher la photo du agence
    $resultat = execRequete("SELECT photo FROM agence WHERE id_agence=:id",array('id' => $_GET['id']));
    // si je trouve l'agence 
    if( $resultat->rowCount() > 0 ){
        $agence = $resultat->fetch();
        // si le champ photo est renseigné
        if(!empty($agence['photo'])){
            $fichier = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/' . $agence['photo'];
            if(file_exists($fichier)){
                // suppression de la photo
                unlink($fichier);
            }
        }
    }
    // Supression en BDD
    execRequete("DELETE FROM agence WHERE id_agence=:id",array('id' => $_GET['id']));
    $content .= '<div class="alert alert-success">L\'agence a été supprimé</div>';
    $_GET['action']= 'affichage';
}


// 3- enregistrement d'un agence en BDD (ajout et en modif)
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
       if($id_agence== 0){
       execRequete("INSERT INTO agence VALUES (NULL,:titre,:adresse,:ville,:code_postal,:description,:photo)",array(
        'titre' => $titre,
        'adresse' => $adresse,
        'ville' => $ville,
        'code_postal' => $code_postal,
        'description' => $description,
        'photo' => $photo_bdd
       ));
       $content .= '<div class="alert alert-success"> L \'agence a été enregistré </div>';
       //$_GET['action'] = 'affichage';
    }
    else{
        execRequete("UPDATE agence SET titre=:titre,adresse=:adresse,ville=:ville,code_postal=:code_postal,description=:description,photo=:photo WHERE id_agence=:id_agence",array(
            'id_agence' => $id_agence,
            'titre' => $titre,
            'adresse' => $adresse,
            'ville' => $ville,
            'code_postal' => $code_postal,
            'description' => $description,
            'photo' => $photo_bdd
           ));
           $content .= '<div class="alert alert-success"> L\'agence a été mis à jour </div>';
    }
       $_GET['action'] = 'affichage';
   } 
}




require_once('../inc/header.php');
echo $content;
// page gestion des agences

// 1- Onglets pour affichage / ajout-modif agence
?>

<ul class="nav nav-tabs nav-justified">
    <li class="nav-item">
    <a class="nav-link <?= ( (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage')) ? 'active': '' )?>" href="?action=affichage">Affichage des agences</a>
    </li>
    <li class="nav-item">
    <a class="nav-link <?= ( (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'ajout' || $_GET['action']== 'edit')) ? 'active': '' )?>" href="?action=ajout">Ajouter une agence</a>
    </li>
</ul>

<?php
// 4. Affichage des agence en BDD
if(!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage')){

    $resultat = execRequete("SELECT * FROM agence");

    if($resultat->rowCount() == 0){
        ?>
        <div class="alert alert-warning">Il n'ya pas encore d'agences enregistrés</div>
        
        <?php
    }
    else{
        ?>
        <p> Il y a <?= $resultat->rowCount() ?> agence(s) enregistrés</p>


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
                <th colspan="2">Actions</th>
            </tr>

            <?php
            // donnée de la table agence 
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
                <td><a href="?action=edit&id=<?= $ligne['id_agence']?>"><i class="fas fa-pen"></i></a></td>
                <td><a class="confirm"  href="?action=sup&id=<?= $ligne['id_agence']?>"><i class="fas fa-trash"></i></a></td>

                </tr>
                <?php
            }
            ?>
        </table>

     <?php
    }
}

// 2. formulaire ajout/modif de agence

if(isset($_GET['action']) && ($_GET['action']=='ajout'|| $_GET['action']=='edit') ):

// 6 - cahrgement d'une agence en edition 
if(!empty($_GET['id'])){
    $resultat = execRequete("SELECT * FROM agence WHERE id_agence=:id",array('id' => $_GET['id']));
    $agence_courant = $resultat->fetch();
}

?>
<form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="id_agence" value="<?= $_POST['id_agence'] ?? $agence_courant['id_agence'] ?? 0 ?>">
<div class="form-row">
  
    <!-- <div class="form-goup col-6">
        <label for="id_agence">Agence</label>
        <input type="id_agence" name="id_agence" id="id_agence" class="form-control" value=" // $_POST['id_agence'] ?? $agence_courant['id_agence'] ?? '' ?>">
    </div> -->
    <div class="form-goup col-12">
        <label for="titre">Titre</label>
        <input type="titre" name="titre" id="titre" class="form-control" value="<?= $_POST['titre'] ?? $agence_courant['titre'] ?? '' ?>">
    </div>
</div>
<div class="form-group">
        <label for="nom">Adresse</label>
        <input type="text" name="adresse" id="adresse" class="form-control" value="<?= $_POST['adresse'] ?? $agence_courant['adresse'] ?? '' ?>">
    </div>
    <div class="form-row">
        <div class="form-group col-3">
        <label for="code_postal">Code postal</label>
        <input type="text" name="code_postal" id="code_postal" class="form-control" value="<?= $_POST['code_postal'] ?? $agence_courant['code_postal'] ?? '' ?>">
        </div>
        <div class="form-group col-9">
        <label for="ville">Ville</label>
        <input type="text" name="ville" id="ville" class="form-control" value="<?= $_POST['ville'] ?? $agence_courant['titre'] ?? '' ?>">
        </div>
</div>
    <div class="form-goup">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control"><?= $_POST['description'] ?? $agence_courant['description'] ?? '' ?></textarea>
    </div>
    <br>
       <div class="form-group">
           <label for="photo"><i class="fa fa-camera"></i> <span id="fichier"></span>
        </label>
           <input type="file" name="photo" id="photo" class="form-control">
           <?php
           if(!empty($agence_courant['photo'])){
               ?>
               <em>Vous pouvez uploader une nouvelle photo</em>
               <img src="<?= URL . 'photo/' . $agence_courant['photo'] ?>" class="img-fluid w-25" alt="<?= $agence_courant['titre'] ?>">
               <input type="hidden" name="photo_courante" value="<?= $agence_courant['photo'] ?>">
             <?php
           }
           ?>
       </div>
     
       </div>
       <input type="submit" class="btn btn-primary" value="Enrengistrer">
</form>


<?php
endif;



require_once('../inc/footer.php');