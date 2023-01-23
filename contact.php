<?php

require_once('inc/init.php');


$title = 'Contact';

?>

<div class="container-fluid bg d-flex justify-content-center align-items-center flex-column">
<h1 style="margin-top:30px; text-shadow: 0px 0px 6px #000000;">Contactez nous !</h1>

<?php
require_once('inc/header.php');

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

require_once('inc/footer.php');
?>


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