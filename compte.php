<?php

require_once('inc/init.php');
$title = 'Mon compte';

if ( !isConnected() ){
    header('location:' . URL . 'connexion.php');
    exit();
}

if( isset($_SESSION['v']) && $_SESSION['v'] == 'ok' ){
    $content .= '<div class="alert alert-success">Le mot de passe a été modifié avec succès</div>';
    unset($_SESSION['v']);
}

// --------------------- COORDONNEES -------------------------------

if ( isset($_POST['validmodifcoord']) ){ // j'ai valider le formulaire lié aux coordonnées
    $nb_champs_vides = 0;
    foreach($_POST as $indice => $valeur){
        $_POST[$indice] = trim($valeur);
        if ( empty($valeur) )  $nb_champs_vides++;
    }
 
    if ( $nb_champs_vides == 0 ){

        // controles
        $nberrors=0;
        
        
        if( $nberrors == 0 ){
            // je mets à jour la BDD

            execRequete("UPDATE membre 
            SET civilite = :civilite,
                nom = :nom,
                prenom = :prenom,
              
            WHERE id_membre = :id_membre",array(
                'civilite' => $_POST['civilite'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                //'id_membre' => $_SESSION['membre']['id_membre']
            ));

            // je mets à jour la session
            $_SESSION['membre']['civilite'] = $_POST['civilite'];
            $_SESSION['membre']['nom'] = $_POST['nom'];
            $_SESSION['membre']['prenom'] = $_POST['prenom'];
            header('location:' . URL . 'compte.php');
            exit();
        }
    }
    else
    {
        $content .= '<div class="alert alert-danger">Merci de remplir le(s) '.$nb_champs_vides.' champ(s) manquant(s)</div>';
    }
}
//------------------------------------------------------------------

if ( isset($_POST['validmodifmdp']) ){ // j'ai validé le formulaire de modif de mdp

    $nb_champs_vides = 0;
    foreach($_POST as $indice => $valeur){
        $_POST[$indice] = trim($valeur);
        if ( empty($valeur) )  $nb_champs_vides++;
    }
 
    if ( $nb_champs_vides == 0 ){
        $resultat = execRequete("SELECT * FROM membre WHERE mdp=:mdp AND id_membre=:id_membre",array(
            'mdp' => md5( $_POST['ancienmdp'] . SALT ),
            'id_membre' => $_SESSION['membre']['id_membre']
        ));
        if ($resultat->rowCount() == 1){

            if ( $_POST['ancienmdp'] === $_POST['nouveaumdp']){
                $content .= '<div class="alert alert-danger">Le nouveau mot de passe ne peut être identique à l\'ancien mot de passe</div>';
            }
            else{
                if( $_POST['nouveaumdp'] === $_POST['confirmmdp'] )
                {
                    execRequete("UPDATE membre SET mdp = :mdp WHERE id_membre=:id_membre",array(
                        'mdp' => md5($_POST['nouveaumdp'] . SALT),
                        'id_membre' => $_SESSION['membre']['id_membre']
                    ));
                    $_SESSION['v'] = 'ok';
                    header('location:' . URL . 'compte.php');
                    exit();
                }
                else{
                    $content .= '<div class="alert alert-danger">Le nouveau mot de passe et la confirmation ne concordent pas</div>';
                }
            }  
        }
        else{
            $content .= '<div class="alert alert-danger">Mot de passe incorrect</div>';
        }
 
    }
    else
    {
        $content .= '<div class="alert alert-danger">Merci de remplir tous les champs</div>';
    }

}

?>



<?php
require_once('inc/header.php');
?>
<div class="container-fluid bgcompte">

<h2 class="font-weight-bold">Bienvenue <?= $_SESSION['membre']['pseudo'] ?></h2>


</div>

<?php
echo $content;
?>




<div class="row d-flex justify-content-between">
    <div class="col-sm-5 shadow p-3 mb-5 bg-white rounded compteperso">
        <h3 class="text-white  bg-dark ">Info Perso</h3>
    <?php
    if ( isset($_GET['action']) && $_GET['action']=='modifcoord' ) :
        $civilite = $_POST['civilite'] ?? $_SESSION['membre']['civilite'];
    ?>
    <form method="post" action="">
        <div class="form-row">
            <div class="form-group col-4">
                <label for="civilite">Civilité</label>
                <select name="civilite" class="form-control">
                    <option value="m" 
                    <?= ( $civilite == 'm') ? 'selected' : '' ?>>Monsieur</option>
                    <option value="f" <?= ( $civilite  == 'f') ? 'selected' : '' ?>>Madame</option>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="nom">Nom</label>
                <input type="text" name="nom" value="<?= $_POST['nom'] ?? $_SESSION['membre']['nom'] ?>"  class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" value="<?= $_POST['prenom'] ?? $_SESSION['membre']['prenom'] ?>"  class="form-control">
            </div>
        </div>
        <!-- <div class="form-group">
            <label for="adresse">Adresse</label>
            <textarea class="form-control" id="adresse" name="adresse"> // $_POST['adresse'] ?? $_SESSION['membre']['adresse']?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group col-3">
                <label for="code_postale">Code Postal</label>
                <input type="text" id="code_postale" name="code_postale" value="// $_POST['code_postale'] ?? $_SESSION['membre']['code_postale'] ?>"  class="form-control">
            </div>
            <div class="form-group col-9">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" value=" // $_POST['ville'] ?? $_SESSION['membre']['ville'] ?> "  class="form-control">
            </div> --
        </div> -->
        <input type="submit" name="validmodifcoord" value="Enregistrer" class="btn btn-primary">
        <a href="<?= URL . 'compte.php' ?>" class="btn btn-warning">Annuler</a>

    </form>
    <?php
    else :
    ?>
        <p><?= ( $_SESSION['membre']['civilite'] == 'm' ) ? 'Monsieur' : 'Madame' ?></p>
        <p>Nom : <?= $_SESSION['membre']['nom'] ?></p>
        <p>Prénom : <?= $_SESSION['membre']['prenom'] ?></p>
            <!-- <address>
                 // $_SESSION['membre']['adresse']?> <br>
                 // $_SESSION['membre']['code_postale'] . ' ' .  $_SESSION['membre']['ville'] ?>
            </address> -->
        <a href="?action=modifcoord" class="btn btn-dark">Modifier mes informations</a>
    <?php
    endif;
    ?>
    </div>
    <div class="col-sm-5 shadow p-3 mb-5 bg-white rounded compteperso">
        <h3 class="text-white bg-dark">Identification</h3>
        <p>Pseudo : <?= $_SESSION['membre']['pseudo'] ?></p>
        <p>Email : <?= $_SESSION['membre']['email'] ?></p>
        <?php 
        if ( isset($_GET['action']) && $_GET['action'] == 'modifmdp') :
        ?>
        <form action="" method="post">

            <div class="form-group">
                <label for="ancienmdp">Ancien mot de passe</label>
                <input type="password" name="ancienmdp" id="ancienmdp" class="form-control">
            </div>
            <div class="form-group">
                <label for="nouveaumdp">Nouveau mot de passe</label>
                <input type="password" name="nouveaumdp" id="nouveaumdp" class="form-control">
            </div>
            <div class="form-group">
                <label for="confirmmdp">Confirmation</label>
                <input type="password" name="confirmmdp" id="confirmmdp" class="form-control">
            </div>
            
            <input type="submit" name="validmodifmdp" value="Enregistrer" class="btn btn-primary">
            <a href="<?= URL . 'compte.php' ?>" class="btn btn-warning">Annuler</a>
            
        </form>
        <?php
        else :
        ?>
        <a href="?action=modifmdp" class="btn btn-dark">Modifier mon mot de passe</a>
        <?php
        endif;
        ?>
    </div>

</div>


<?php
require_once('inc/footer.php');