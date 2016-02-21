<?php 
session_start();
/* --------------------------------------------------------------------------------- */
/* 							Script d'inscription									 */
/* --------------------------------------------------------------------------------- */
// Connexion base
	require("./ressources/config.req.php");
	require("./ressources/bdconnect.req.php");
	require("./ressources/fonctions.connexion.req.php");
	include_once('./ressources/class.phpmailer.php');
	
if(isset($_POST["form_send"]) && $_POST["form_send"] == 1) {
	// Vérification du mail envoyé
	if(isset($_POST["email"])) $email = $_POST["email"]; else $email = "";
	$varerror["mail"]	=	is_valid_mail($email);
		
	// Vérification du captcha
	if(isset($_POST['iQapTcha']) && empty($_POST['iQapTcha']) && isset($_SESSION['iQaptcha']) && $_SESSION['iQaptcha'] == 1)
	{
		// Formulaire OK
		if($varerror["mail"]["etat"] == TRUE) {
			// Enregistrer dans la base
			$data[0] = array( "mail" => mysql_real_escape_string($email), "actif_user" => 0);
			$bdderror["bdd"] = ajout_abonnes($data);
			
			if($bdderror["bdd"]["etat"] == TRUE)
			{
				// Envoyer le mail
				$mail             = new PHPMailer();
				$mail->IsSendmail();
				$mail->CharSet = "UTF-8";
				$mail->Subject = "Paroles d'argent - Confirmation de votre abonnement";
				$mail->AddAddress($email, "");
				$mail->From       = "noreply@finances-pedagogie.fr";
				$mail->FromName   = "Noreply";
				// 3: Préparation du mail
				$body             = $mail->getFile("ressources/confirmation-part1.html");
				$body			 .= "?uniq_id=".$bdderror["bdd"]["uniq_id"];
				$body			 .= $mail->getFile("ressources/confirmation-part2.html");
				$mail->MsgHTML($body);
				$mailerror = $mail->Send();
				if($mailerror <> FALSE) { // Aucune erreur, confirmation envoi mail et tout et tout
					ob_start();
					?>
                    <p>Votre demande a été prise en compte.</p>
                    <p>Un message de confirmation vous a été envoyé.</p>
                    <p>Nous vous remercions de votre confiance et de votre intérêt.</p>
                    <?php
					$content = ob_get_clean();
				}
				else
				{
					ob_start();
					?>
                    <p>Votre demande a été prise en compte.</p>
                    <p class="erreur">Pour des raisons techniques, le message de confirmation n'a malheureusement pas été envoyé. Nous vous invitons à nous contacter par mail à l'adresse ci-dessous pour une confirmation manuelle en indiquant le code ci-après : <?php echo $bdderror["bdd"]["uniq_id"]; ?></p>
                    <p><?php echo $config["contact-administrateur"]; ?></p>
                    <?php
					$content = ob_get_clean();
				}
			}
			else {
			ob_start;
			?>
            <p class="erreur">Votre inscription n'a pas été enregistrée.</p>
            <p><?php echo $config["contact-administrateur"]; ?></p>
			<?php
			$content = ob_get_clean();
			}
		}
		else
		{
			ob_start();
			?>
            <p class="erreur">L'adresse mail que vous avez entrée n'est pas valide. Merci de renouveler votre inscription.</p>
            <form method="post">
                <label for="email">Entrez votre adresse mail ici</label>
                <input type="text" name="email" value="" />
                <p>Pour déverrouiller le formulaire, glissez le curseur vers la droite.</p>
               <div class="QapTcha"></div>
               <input type="hidden" name="form_send" value="1" />
              <input type="submit" />
		 	</form>
            <?php
			$content = ob_get_clean();
		}
	}
	else
	{
		// Formulaire pas OK
		ob_start();
		?>
		<form method="post">
			<label for="email">Entrez votre adresse mail ici</label>
			<input type="text" name="email" value="" />
			<p>Pour déverrouiller le formulaire, glissez le curseur vers la droite.</p>
		   <div class="QapTcha"></div>
		   <input type="hidden" name="form_send" value="1" />
		  <input type="submit" />
		  </form>
		<?php
		$content = ob_get_clean();
	}
	
}
else // Formulaire non envoyé
{
	ob_start();
	?>
    <form method="post">
      	<label for="email">Entrez votre adresse mail ici</label>
      	<input type="text" name="email" value="" />
        <p>Pour déverrouiller le formulaire, glissez le curseur vers la droite.</p>
       <div class="QapTcha"></div>
       <input type="hidden" name="form_send" value="1" />
      <input type="submit" />
      </form>
    <?php
	$content = ob_get_clean();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title>Finances et pédagogie - Inscription à la lettre d'information - &copy; MDF 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<!-- include CSS & JS files -->
<!-- CSS file -->
<link rel="stylesheet" type="text/css" href="ressources/Qaptcha_v3.0/jquery/QapTcha.jquery.css" media="screen" />
<!-- jQuery files -->
<script type="text/javascript" src="ressources/Qaptcha_v3.0/jquery/jquery.js"></script>
<script type="text/javascript" src="ressources/Qaptcha_v3.0/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="ressources/Qaptcha_v3.0/jquery/jquery.ui.touch.js"></script>
<script type="text/javascript" src="ressources/Qaptcha_v3.0/jquery/QapTcha.jquery.js"></script>
<script>
$(document).ready(function() {
	$('.QapTcha').QapTcha({
			txtLock : 'Le formulaire est verrouillé',
			txtUnlock : 'Vous avez déverouillé le formulaire',
			disabledSubmit : true,
			autoRevert : true,
			PHPfile : 'ressources/Qaptcha_v3.0/php/Qaptcha.jquery.php'});
});
</script>
</head>

<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item selection"><span><a>Finances & Pédagogie - Inscription</a></span></li>
        </ul>
    </div>
</div>
<div id="main">
    <div id="contenu">

<div class="tableaudebord">
      
     <?php echo $content; ?>
     <br /><a href="http://www.finances-pedagogie.fr/" title="Retour vers le site Finances & pédagogie">Retourner sur le site de Finances & Pédagogie</a>
     
</div>

<div id="backtotheflux"></div>
</div>
</div>

    <div id="footer">
    </div>
</body>
</html>
<?php require("./ressources/bddisconnect.req.php");