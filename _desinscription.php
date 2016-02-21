<?php 
/* --------------------------------------------------------------------------------- */
/* 							Script de désinscription								 */
/* --------------------------------------------------------------------------------- */
// Connexion base
	require("./ressources/config.req.php");
	require("./ressources/bdconnect.req.php");
	
// 1: Déterminer quel est l'abonné à désinscrire
if(isset($_GET["uniq_id"]) && isset($_GET["mail"])) {
	$uniq_id = $_GET["uniq_id"];
	$mail = $_GET["mail"];
	$newmail = md5($mail.rand()).substr($mail,strpos($mail,"@"));
	$request_string = "UPDATE abonnes SET abonnes.datedesabonnement='".date("Y-m-d H:i:s")."', abonnes.actif_user=-1, abonnes.mail='".mysql_real_escape_string($newmail)."' WHERE abonnes.uniq_id='".mysql_real_escape_string($uniq_id)."' ; ";
	
	$request = mysql_query($request_string);	
}

// 2: Contenu
ob_start();
if(isset($request) ) {
	?>
    <p class="titre">Confirmation de votre désinscription</p>
    <p>Votre désinscription est confirmée et votre compte abonné a été supprimé.</p>
    <p>Nous vous remercions de votre confiance.</p>
    <?php
}
else {
	?>
    <p class="titre">Echec de la désinscription</p>
    <p class="erreur">Votre désinscription a échoué. Votre compte abonné n'a pu être supprimé.</p>
    <p><?php echo $config["contact-administrateur"]; ?></p>
    <?php
}

$content = ob_get_clean();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title>Finances et pédagogie - Confirmation de désinscription - &copy; MDF 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="./ressources/validation.js"></script> 
</head>

<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item selection"><span><a>Finances & Pédagogie - Désinscription de l'abonné</a></span></li>
        </ul>
    </div>
</div>
<div id="main">
    <div id="contenu">

<div class="tableaudebord">
      <?php echo $content; ?>
     
</div>
<div id="backtotheflux"></div>
</div>
</div>

    <div id="footer">
    </div>
</body>
</html>
<?php require("./ressources/bddisconnect.req.php");