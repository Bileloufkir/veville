<?php

$pdo = new PDO(
  'mysql:host=localhost;dbname=veville', // SGBD avec host et bdd
  'root', // user
  '', // password
  array( // tableau d'options
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // Affichage des erreurs en mode warning : nous serons avertis des erreurs SQL
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', // Encodage des noms de tables et colonnes
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Parcours des données en mode tableau ASSOCiatif
  )
);

// définition du nombre d'éléments par page
$nb_par_page = 6;
// connaitre le nbre total d'éléments à afficher
$resultat = $pdo->query("SELECT COUNT(*) as id_vehicule FROM vehicule");
$nb_employes = $resultat->fetch()['id_vehicule'];
// calcul du nombre de pages
$nbpages = $nb_employes / $nb_par_page;
if ($nb_employes % $nb_par_page > 0) $nbpages++;

// on récupère l'éventuel début de ma LIMIT dans l'url (par défaut 0)
if( !isset($_GET['debut']) ){
  $debut = 0;
}
else{
  $debut = intval($_GET['debut']);
}
// je fais ma réquête en tenant compte de mes limites
$resultat = $pdo->prepare("SELECT * FROM vehicule LIMIT :debut, :nb");
$resultat->bindValue('debut',$debut,PDO::PARAM_INT);
$resultat->bindValue('nb',$nb_par_page,PDO::PARAM_INT);
$resultat->execute();
// je parcoure mes résultats
while($employe = $resultat->fetch()){
  ?>
  <p><?= $employe['titre'] ?> <img src="<?= URL . 'photo/' .$employe['photo'] ?>" alt=""> </p>
  
  <?php
}

// liens de pagination
$incrementation=0;
for($i=1 ; $i <= $nbpages; $i++){
  ?>
  <a href="?debut=<?= $incrementation ?>">Page <?= $i ?></a>
  <?php
  $incrementation += $nb_par_page;
}
