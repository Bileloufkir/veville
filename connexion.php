<?php

require_once('inc/init.php');
$title="Connexion";

if(isset($_GET['action']) && $_GET['action']== 'deconnexion'){
    // session_destroy();
    unset($_SESSION['membre']);
    header('location:'. URL . 'connexion.php');
    exit();
}

if(!empty($_POST)){
    if(!empty($_POST['pseudo']) && !empty($_POST['mdp'])){ 

    $resultat = execRequete("SELECT * FROM membre WHERE pseudo=:pseudo AND mdp=:mdp",array(
        'pseudo' => $_POST['pseudo'],
        'mdp'=> md5($_POST['mdp'].SALT)
    ));
        if($resultat->rowCount() != 0){
        //j'ai trouver l'utilisateur 
        $membre = $resultat->fetch();
        unset($membre['mdp']);           //je retire le mdp crypté
        $_SESSION['membre'] = $membre;
        header('location:'.URL.'compte.php');
        exit();
    }
else{
    $content .= '<div class="alert alert-danger">Erreur de mdp ou identifiant</div>';
        }    
    }
}
require_once('inc/header.php');
echo $content;

?>

<h1>Formulaire de connexion</h1>
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
 <input type="submit" class="btn btn-primary" value="Se connecter">
</form>
<?php
require_once('inc/footer.php');