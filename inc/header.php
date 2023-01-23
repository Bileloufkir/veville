<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Veville<?= $title ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

   <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">

    

    <!-- bootstrap js -->
    <script
   src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
   
    <!-- css perso -->
    <link rel="stylesheet" href="  <?=  URL . 'inc/css/style.css' ?>">
    
    <!-- datepicker -->
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script type="text/javascript" src="<?= URL . 'inc/js/moment.js'?>"></script>
   	<script src="<?= URL . 'inc/js/locale/fr.js'?>" charset="UTF-8"></script>



    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
 
    <!-- jquery 
    <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script> -->

    <!-- js perso -->
    <script type="text/javascript" src="<?= URL . 'inc/js/moment.js'?>"></script>
    <script src="<?= 'inc/js/functions.js'?>"></script>

</head>
<body>

  
    <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top hautpage">
  <a class="navbar-brand" href="<?= URL . 'index.php' ?>">Veville</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?= ($title == 'Accueil') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= URL . 'index.php' ?>">Accueil<span class="sr-only">Accueil</span></a>
      </li>
      <li class="nav-item <?= ($title == 'Contact') ? 'active' : '' ?>">
        <a class="nav-link" href="<?=  URL . 'contact.php' ?>"> Contact </a>
      </li>
      <?php
      if(!isConnected()):
        ?>
      <li class="nav-item  <?= ($title == 'Inscription') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= URL . 'inscription.php' ?>">Inscription</a>
      </li>
      <li class="nav-item  <?= ($title == 'Connexion') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= URL . 'connexion.php' ?>">Connexion</a>
      </li>
      <?php
      else:
      ?>
<!-- --------------------- -->
<li class="nav-item <?= ($title == '') ? 'active' : '' ?> dropdown ">
            <a class="nav-link dropdown-toggle" href="#" id="menuadmin" role="button" data-toggle="dropdown">Espace membres</a>
                
            <div class="dropdown-menu" aria-labelleby="">
                <a class="dropdown-item" href="<?= URL . 'compte.php'?>">Mon compte</a>
                <a class="dropdown-item" href="<?= URL . 'panier.php'?>">Mes Reservations</a>
                <a class="dropdown-item" href="<?= URL . 'connexion.php?action=deconnexion'?>">Déconnexion</a>
                
            </div>
</li>
<!-- --------------------- -->

      <?php
      endif;
      if(isAdmin()):
        ?>
        <li class="nav-item  <?= (substr($title,0,7)=='Gestion') ? 'active': '' ?> dropdown ">
            <a class="nav-link dropdown-toggle" href="#" id="menuadmin" role="button" data-toggle="dropdown">
                <i class="fas fa-tools"></i> Admin </a>
            <div class="dropdown-menu" aria-labelleby="menuadmin">
                <a class="dropdown-item" href="<?= URL . 'admin/gestion_vehicules.php'?>">Gestion des vehicules</a>
                <a class="dropdown-item" href="<?= URL . 'admin/gestion_membres.php'?>">Gestion des membres</a>
                <a class="dropdown-item" href="<?= URL . 'admin/gestion_commandes.php'?>">Gestion des commandes</a>
                <a class="dropdown-item" href="<?= URL . 'admin/gestion_agences.php'?>">Gestion des agences</a>
            </div>
        </li>
        <?php
        endif;
      ?>

    </ul>
    <form method="post" action="<?= URL . 'recherche.php' ?>" class="form-inline mt-2 mt-md-0">
      <input class="form-control mr-sm-1" type="text" placeholder="Véhicules" aria-label="Search" name="critere" value="<?= $_POST['critere']?? ''?>">
      <button class="btn btn-outline-secondary my-2 my-sm" type="submit">Recherche</button>
    </form>
  </div>
</nav>
    </header>
    <main class="container">
   