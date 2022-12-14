<?php

require_once('inc/init.php');
$title = 'Contact';
?>

<div class="container-fluid bg d-flex justify-content-center align-items-center flex-column">
<h1 style="margin-top:30px; text-shadow: 0px 0px 6px #000000;">Contactez nous !</h1>

<?php

/*
	**************
	CONFIGURATION
	**************
*/
$destinataire = 'biilel_95@hotmail.fr';
 

$copie = 'oui';
 
// Action du formulaire (si votre page a des paramètres dans l'URL)
$form_action = '';
 
// Messages de confirmation du mail
$message_envoye = "Votre message nous est bien parvenu !";
$message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer SVP.";
 
// Message d'erreur du formulaire
$message_formulaire_invalide = ' <div class="alert alert-danger" role="alert">Vérifiez que tous les champs soient bien remplis et que l\'email soit sans erreur.</div>';
 
/*
	********************************************************************************************
	FIN DE LA CONFIGURATION
	********************************************************************************************
*/
 
/*
  nettoyage et enregistrer texte
 */

function Rec($text)
{
	$text = htmlspecialchars(trim($text), ENT_QUOTES);
	if (1 === get_magic_quotes_gpc())
	{
		$text = stripslashes($text);
	}
 
	$text = nl2br($text);
	return $text;
};
 
/*
 vérifier la syntaxe de l'email
 */
function IsEmail($email)
{
	$value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
	return (($value === 0) || ($value === false)) ? false : true;
}
 
// récupère tous les champs
$nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
$email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
$objet   = (isset($_POST['objet']))   ? Rec($_POST['objet'])   : '';
$message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';
 
// vérifier les variables et l'email ...
$email = (IsEmail($email)) ? $email : ''; 
$err_formulaire = false; 
 
if (isset($_POST['envoi']))
{
	if (($nom != '') && ($email != '') && ($objet != '') && ($message != ''))
	{
		
		$headers  = 'From:'.$nom.' <'.$email.'>' . "\r\n";
		//$headers .= 'Reply-To: '.$email. "\r\n" ;
		//$headers .= 'X-Mailer:PHP/'.phpversion();
 
		
		if ($copie == 'oui')
		{
			$cible = $destinataire.';'.$email;
		}
		else
		{
			$cible = $destinataire;
		};
 
		// Remplacement caractères spéciaux
		$caracteres_speciaux     = array('&#039;', '&#8217;', '&quot;', '<br>', '<br />', '&lt;', '&gt;', '&amp;', '…',   '&rsquo;', '&lsquo;');
		$caracteres_remplacement = array("'",      "'",        '"',      '',    '',       '<',    '>',    '&',     '...', '>>',      '<<'     );
 
		$objet = html_entity_decode($objet);
		$objet = str_replace($caracteres_speciaux, $caracteres_remplacement, $objet);
 
		$message = html_entity_decode($message);
		$message = str_replace($caracteres_speciaux, $caracteres_remplacement, $message);
 
		// Envoi du mail
		$num_emails = 0;
		$tmp = explode(';', $cible);
		foreach($tmp as $email_destinataire)
		{
			if (mail($email_destinataire, $objet, $message, $headers))
				$num_emails++;
		}
 
		if ((($copie == 'oui') && ($num_emails == 2)) || (($copie == 'non') && ($num_emails == 1)))
		{
			echo '<p>'.$message_envoye.'</p>';
		}
		else
		{
			echo '<p>'.$message_non_envoye.'</p>';
		};
	}
	else
	{
		// une des 3 variables (ou plus) est vide ...
		echo '<p>'.$message_formulaire_invalide.'</p>';
		$err_formulaire = true;
	};
}; // fin du if (!isset($_POST['envoi']))
 
if (($err_formulaire) || (!isset($_POST['envoi'])))
{
	// afficher le formulaire
    echo '
    <div class="container" style="box-shadow: 0px 0px 22px 0px rgba(0,0,0,0.75);; display:flex; justify-content:center; margin-top:25px;margin-bottom:25px; padding-top:15px;">
	<form id="contact" method="post" action="'.$form_action.'">
	<fieldset><legend style="color:white;">Vos coordonnées</legend>
		<p><label for="nom" style="color:white;">Nom :</label><input type="text" id="nom" name="nom" value="'.stripslashes($nom).'" class="form-control"/></p>
		<p><label for="email" style="color:white;">Email :</label><input type="text" id="email" name="email" value="'.stripslashes($email).'"  class="form-control"/></p>
	</fieldset>
 
	<fieldset><legend style="color:white;">Votre message :</legend>
		<p><label for="objet" style="color:white;">Objet :</label><br><input type="text" id="objet" name="objet" value="'.stripslashes($objet).'" class="form-control" /></p>
		<p><label for="message" style="color:white;">Message :</label><br><textarea id="message" name="message" cols="30" rows="8" class="form-control">'.stripslashes($message).'</textarea></p>
	</fieldset>
 
	<div style="text-align:center;"><input type="submit" name="envoi" value="Envoyer le formulaire !" class="btn-light form-control" /></div>
    </form>
    </div>
    ';
};

//require_once('inc/header.php');
?>
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
    <link rel="stylesheet" href=" <?= URL . 'inc/css/style.css' ?>">
    
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
    <script src="<?= URL . 'inc/js/functions.js'?>"></script>

</head>
<body>

  
    <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top hautpage">
  <a class="navbar-brand" href="<?= URL ?>">Veville</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?= ($title == 'Accueil') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= URL ?>">Accueil <span class="sr-only">Accueil</span></a>
      </li>
      <li class="nav-item <?= ($title == 'Contact') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= URL . 'contact.php' ?>"> Contact </a>
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
                <a class="dropdown-item" href="<?= URL . 'commandes.php'?>">Mes Reservations</a>
                <a class="dropdown-item" href="<?= URL . 'connexion.php?action=deconnexion'?>">Déconnexion</a>
                
            </div>
        </li>
<!-- --------------------- -->

      <!-- <li class="nav-item  < ($title == 'Compte') ? 'active' : '' ?>">
        <a class="nav-link" href="< URL . 'compte.php' ?>">Mon compte</a>
      </li>
      <li class="nav-item  < ($title == 'Mes commandes') ? 'active' : '' ?>">
        <a class="nav-link" href="< URL . 'commandes.php' ?>">Mes commandes</a>
      </li>
      <li class="nav-item  < ($title == 'Connexion') ? 'active' : '' ?>">
        <a class="nav-link" href="< URL . 'connexion.php?action=deconnexion' ?>">Déconnexion</a>
      </li> -->

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
      <!-- <li class="nav-item  < ?= ($title == 'Panier') ? 'active' : '' ?>">
        <a class="nav-link" href="< ?= URL . 'panier.php' ?>"><i class="fas fa-shopping-cart"></i> < ?= nbArticles() ?></a>
      </li> -->
    </ul>
    <form method="post" action="<?= URL . 'recherche.php' ?>" class="form-inline mt-2 mt-md-0">
      <input class="form-control mr-sm-1" type="text" placeholder="Véhicules" aria-label="Search" name="critere" value="<?= $_POST['critere']?? ''?>">
      <button class="btn btn-outline-secondary my-2 my-sm" type="submit">Recherche</button>
    </form>
  </div>
</nav>
    </header>


</div>

<?php
//require_once('inc/footer.php');
?>

    <footer class="container-fluid">
    <div class="row text-center piedpage">

        <div class="container rgpd pb-4">
        <small class="row">
           <div class="rgpd2">  <a href="<?= URL . 'cgv.php' ?>">CGV</a> </div>
           <div class="rgpd2">   <a href="<?= URL . 'mention.php' ?>"  >Mentions Légales</a> </div>
           <div class="rgpd2">   <a href="<?= URL . 'rgpd.php' ?>">RGPD</a>   </div>
           <div class="rgpd2">     <a href="<?= URL . 'cookies.php' ?>">Cookies</a>  </div>
                    </small>
        </div>
        <div class="container-fluid">
            <div class="row bas">                        
                <div class="copyrights col-12">
                    &copy; <?= date('Y') ?> - Veville -
                            Bilel OUFKIR
                    <p> Ce site n'a aucun but commercial, il est réalisé dans le cadre d'un atelier pédagogique  au sein de l'organisme de formation IFOCOP. </p>

        </div>
            </div>
                </div>
    </div>
</footer>


<script type="text/javascript">



$.extend($.fn.datetimepicker.Constructor.Default, {
    icons: {
        time: 'far fa-clock h4 ',
        date: 'fas fa-calendar-alt h4 ',
        up: 'fas fa-arrow-up ',
        down: 'fas fa-arrow-down ',
        previous: 'fas fa-chevron-left ',
        next: 'fas fa-chevron-right ',
        today: 'fas fa-calendar-check-o ',
        clear: 'fas fa-trash',
        close: 'fas fa-times'
    } 
});



    $(function () {
        $('#datetimepicker7').datetimepicker({
            stepping: 30,
           // forceMinuteStep: true
           // defaultDate: new Date(),

        });
        $('#datetimepicker8').datetimepicker({
            stepping: 30,
            useCurrent: false,
           // forceMinuteStep: true
           // defaultDate: new Date(),

        });
  
        
        $("#datetimepicker7").on("change.datetimepicker", function (e) {
            $('#datetimepicker8').datetimepicker('minDate', e.date);
        });
        $("#datetimepicker8").on("change.datetimepicker", function (e) {
            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });


        $('#datetimepicker8').datetimepicker({
            useCurrent: false,   
        });


          
        

});  
  
</script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        
        <!-- Icons -->
        <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
        <script>
          feather.replace()
        </script>


	
  </body>
</html>