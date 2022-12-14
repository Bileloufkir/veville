 <?php
// l'existence du tableau membre dans la session indique que l'utilisateur s'est correctement connecté
function isConnected(){
  if ( isset($_SESSION['membre']) ){
    return true;
  }
  else{
    return false;
  }
}

// un admin est un membre connecté dont le statut vaut 1
function isAdmin(){
  if( isConnected() && $_SESSION['membre']['statut'] == 1){
    return true;
  }
  else{
    return false;
  }
}

function execRequete($req,$params=array()){
  global $pdo;
  $r = $pdo->prepare($req);
  if ( !empty($params) ){
    // sanatize et bindvalue
    foreach($params as $key => $value){
      $params[$key] = htmlspecialchars($value,ENT_QUOTES);
      $r->bindValue($key,$params[$key],PDO::PARAM_STR);
    }    
  }
  $r->execute();
  if ( !empty( $r->errorInfo()[2] )){
    die('Erreur rencontrée - merci de contacter l\'administrateur');
  }
  return $r;
}

// $vehicule = execRequete("SELECT * FROM vehicule WHERE id_vehicule=:id_vehicule", array('id_vehicule'=> 485));
// $membres = execRequete("SELECT * FROM membre");

// Fonctions liées au Panier

function createPanier(){
  if( !isset($_SESSION['panier']) ){
    $_SESSION['panier'] = array();
    $_SESSION['panier']['id_vehicule'] = array();
    $_SESSION['panier']['prix_journalier'] = array();
  }
}

function addPanier($id_vehicule,$prix){
    createPanier();
    // nouveau vehicule
    $_SESSION['panier']['id_vehicule'][] = $id_vehicule;
    $_SESSION['panier']['prix_journalier'][] = $prix;

}

function removePanier($id_vehicule){
    $position_vehicule = array_search($id_vehicule,$_SESSION['panier']['id_vehicule']);
    if ( $position_vehicule !== false){

    array_splice($_SESSION['panier']['id_vehicule'],$position_vehicule,1);
    // array_splice($_SESSION['panier']['quantite'],$position_vehicule,1);
    array_splice($_SESSION['panier']['prix_journalier'],$position_vehicule,1);

  }
}

function montantPanier(){
  $total = 0;
  for($i=0; $i < count($_SESSION['panier']['id_vehicule']); $i++)
  {
    $total += $_SESSION['panier'][$i] * $_SESSION['panier']['prix_journalier'][$i];
  }
  return $total;
}

// function nbArticles(){
//   $nb='';
//   if( !empty($_SESSION['panier']['id_vehicule'])){
//     $nb = '<span class="badge badge-primary">' .array_sum($_SESSION['panier']['quantite']) . '</span>';
//   }
//   return $nb;
// }
