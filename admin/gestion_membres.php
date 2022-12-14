<?php

require_once('../inc/init.php');
$title='Gestion des Membres';

// Controle des autorisations
if(!isAdmin()){
    header('location:'. URL . 'connexion.php');
    exit();
}

// controle admin 

if(isset($_GET['action']) && $_GET['action']=='changestatut' && !empty($_GET['id_membre']) && $_GET['id_membre'] != $_SESSION['membre']['id_membre']){
    $resultat =execRequete("SELECT statut FROM membre WHERE id_membre=:id_membre",array(
        'id_membre' => $_GET['id_membre']));
        if($resultat->rowCount() == 1 ){
            $membre = $resultat->fetch();
            $nouveaustatut = ($membre['statut'] == 0) ? 1 : 0;
            execRequete("UPDATE membre SET statut=:nouveaustatut WHERE id_membre=:id_membre",array(
                'nouveaustatut' => $nouveaustatut,
                'id_membre'=> $_GET['id_membre']
            ));
        }
}



require_once('../inc/header.php');

?>



  <?php
    $resultat = execRequete("SELECT * FROM membre ORDER BY nom,prenom");
   ?>
    <table class="table table-bordered table-dark table-striped">
        <tr>
    <?php
    for($i=0; $i<$resultat->columnCount();$i++){
      $colonne = $resultat->getColumnMeta($i);
      if($colonne['name'] != 'mdp'){
          ?> 
          <th><?= ucfirst($colonne['name']) ?></th>
          <?php
      }
    }
    ?>
    <th>Action</th>
</tr>
<?php
// donnéé
while($membre = $resultat->fetch()){
    ?>
    <tr>
      <?php
        foreach($membre as $key => $value){
            if($key != 'mdp'){
                if($key== 'statut'){
                    $value = ($value == 0) ? 'Membre' : 'Admin';
                }
          ?>
          <td><?= $value ?></td>
          <?php
        }
    }
    if($membre['id_membre'] != $_SESSION['membre']['id_membre']){
        ?>

        <td> <a href="?action=changestatut&id_membre=<?= $membre['id_membre'] ?>"><i class="fas fa-users-cog"></i></a></td>

        <?php

    }
    else{
        ?>
        <td><i class="fas fa-user-check"></i></td>
        <?php
    }
    ?>
    </tr>
    <?php
}
    ?>
</table>

<?php

require_once('../inc/footer.php');