<?php 
/* --------------------------------------------------------------------------------- */
/* 							Script de confirmation d'abonnement						 */
/* --------------------------------------------------------------------------------- */
// Connexion base
	require("./ressources/config.req.php");
	require("./ressources/bdconnect.req.php");
	
// 1: Déterminer quelle est l'abonné à activer
if(isset($_GET["uniq_id"])) {
	$uniq_id = $_GET["uniq_id"];
	$request_string = "UPDATE abonnes SET actif_user='1' WHERE uniq_id='".mysql_real_escape_string($uniq_id)."' AND actif_user<>'-1' LIMIT 1";
	$request = mysql_query($request_string);
}

// 2: Contenu
ob_start();
if(isset($request) && $request<>FALSE) {
	?>
    <p class="titre">Confirmation d'inscription</p>
    <p>Votre inscription est confirmée et votre compte abonné a été activé.</p><p>Notre prochaine lettre d'information vous sera envoyée.</p>
    <p>Nous vous remercions de votre confiance et de votre intérêt.</p>
    <?php
}
else {
	?>
    <p class="titre">Echec de l'inscription</p>
    <p class="erreur">La confirmation de votre inscription a échoué. Votre compte abonné n'a pu être activé.</p>
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
<title>Finances et pédagogie - Confirmation d'inscription - &copy; MDF 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="./ressources/validation.js"></script> 
</head>

<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item selection"><span><a>Finances & Pédagogie - Activation du compte abonné</a></span></li>
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