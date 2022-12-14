<?php

require_once('inc/init.php');
$title="Inscription";
$inscription = false;
if(!empty($_POST)){
    // le formulaire est posté
    $nb_champs_vides = 0;
    foreach($_POST as $value){
        if(empty($value) ) $nb_champs_vides++;
    }
if ($nb_champs_vides > 0){
    $content .= '<div class="alert alert-danger"> Il manque '.$nb_champs_vides.' information(s)</div>'; 
    }
    //verif du pseudo 
    $verif_pseudo = preg_match('#^[\w.-]{3,20}$#',$_POST['pseudo']);
    if(!$verif_pseudo){
        $content .= '<div class="alert alert-danger">Le pseudo doit comporter entre 3 et 20 caractéres (a à z, A à Z, 0 à 9 et .,-,_)</div>';
    }

    //verif de l'email 
    $verif_email = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);
    if(!$verif_email){
        $content .= '<div class="alert alert-danger">L\'email n\'est pas valide</div>';
    }
    if(empty($content)){
        // je n'ai pas d'erreur 
        //controler l'unicite du pseudo 
        $verif_membre  = execRequete("SELECT * FROM membre WHERE pseudo=:pseudo", array('pseudo' => $_POST['pseudo']));
        if ($verif_membre->rowCount() > 0){
            $content .= '<div class="alert alert-danger">Pseudo deja utiliser </div>';
        }
        else{
            extract($_POST);
            //genere des variables avec le nom des index
            execRequete("INSERT INTO membre VALUES (NULL,:pseudo,:mdp,:nom,:prenom,:email,:civilite,0,NOW())",array(
                'pseudo' => $pseudo,
                'mdp' => md5($mdp . SALT),
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'civilite' => $civilite,
            ));
            $inscription =true;
            $content .= '<div class="alert alert-success">Vous êtes inscrit! <a href="'.URL.'connexion.php">Cliquer ici pour vous connecter</a></div>';
        }

    }
}

require_once('inc/header.php');
echo $content;
if(!$inscription) : 
?>

<h1>Formulaire d'inscription</h1>
<form method="post" action="">
    <fieldset>
        <legend>Identifiants</legend>
 <div class="form-group">
    <label for="pseudo">Pseudo</label>
    <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $_POST['pseudo'] ?? '' ?>">
 </div>
<div class="form-group">
    <label for="mdp">Password</label>
    <input type="password" name="mdp" id="mdp" class="form-control" value="">
 </div>
<div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>">
</div>
    </fieldset>
    <fieldset>
        <legend>Coordonnées</legend>
<div class="form-row">
    <div class="form-group col-2">
        <label for="civilite">Civilité</label>
        <select name="civilite" id="civilite" class="form-control">
        <option value="m">Monsieur</option>
        <option <?= (isset($_POST['civilite']) && $_POST['civilite']=='f') ? 'selected' : '' ?> value="f">Madame</option>
        </select>
    </div>
    <div class="form-group col-5">
        <label for="prenom">Prenom</label>
        <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $_POST['prenom']?? '' ?>">
    </div>
    <div class="form-group col-5">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" class="form-control" value="<?= $_POST['nom']?? '' ?>">
    </div>
</div>
    </div>

    </fieldset>
    <input type="submit" class="btn btn-primary" value="S'inscrire">
</form>
<?php

endif;

require_once('inc/footer.php');
