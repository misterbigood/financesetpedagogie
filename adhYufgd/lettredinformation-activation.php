<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");

//Récupération de l'ID
if(isset($_GET["uniq_id"])) $uniq_id = $_GET["uniq_id"];
if(isset($_POST["uniq_id"])) $uniq_id = $_POST["uniq_id"];

// Chercher l'enregistrement correspondant à l'ID
// Récupération des données pour ce uniq_id
$tableau_classe = liste_li("","uniq_id='".$uniq_id."'","","1");

ob_start();
// Traitement du formulaire envoyé ou non
if( isset($_POST["form_send"]) && $_POST["form_send"] == 1 )
{
	// Formulaire envoyé
	include("lettredinformation-activation.inc.php");
	
	if($bdderror["bdd"]["etat"] == TRUE) {?>
    <p class="titre">Confirmation de l'activation de la lettre d'information n°<?php echo $numero; ?></p>
    <p>La lettre d'information a été modifiée.</p>
     <p>La lettre d'information a été modifiée.<br />Vous pouvez <a href="lettredinformation.php" title="Retour à la liste des lettres d'information">retourner à la liste des lettres d'information</a>.</p>
    <?php
	}
	else
	{
		?>
    <p class="titre">Echec de l'activation de la lettre d'information n°<?php echo $numero; ?></p>
    <p>La lettre d'information n'a pas été modifiée.</p>
    <p class="erreur"><?php echo $bdderror["bdd"]["texte"]; ?></p>
    <p><?php echo $config["contact-administrateur"]; ?></p>
    <?php
	}
}
else
{
	// Formulaire non envoyé
	?>
    <p class="titre">Activation de la lettre d'information n°<?php echo $tableau_classe[0]["numero"]; ?></p>
	<form name="formulaire" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" class="formulaire">
    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
    <input type="hidden" name="uniq_id" value="<?php echo $uniq_id; ?>" />
    <input type="hidden" name="numero" value="<?php echo $tableau_classe[0]["numero"]; ?>" />
    <p class="erreur">L'envoi de cette lettre d'information est achevé. Vous ne pouvez pas la modifier.<br />Si vous cochez la case ci-dessous, l'envoi sera réactivé.<br />Seuls les abonnés ne l'ayant pas encore reçue seront concernés par la nouvelle séquence d'envoi.</p>							        <fieldset>
        
          <legend>Paramètres d'envoi</legend>
          <label for="actif">Activation de la lettre d'information</label>
          <span>(Attention, si vous cochez cette case, la lettre d'information sera envoyée à partir de la date spécifiée; si vous ne spécifiez aucune date, les envois démarreront immédiatemment)</span>
          <input type="checkbox" name="actif_li" id="actif_li" value="<?php if ($tableau_classe[0]["actif_li"] <> -2) {echo "1";} ?>" <?php if ($tableau_classe[0]["actif_li"]> 0) {echo "checked='checked'";} ?> />
          <input type="hidden" name="actif_li_old" value="<?php echo $tableau_classe[0]["actif_li"]; ?>" />
          <label for="dateactivationenvoi">Date d'activation de l'envoi</label>
          <span>(Vous pouvez spécifier une date sans valider la lettre d'information; dans ce cas, tant que la lettre d'information ne sera pas validée via la case à cocher ci-dessus, aucun envoi ne sera effectué)</span>
           <span>(Format de la date d'envoi: JJ/MM/AAAA)</span>
	      <input type="text" name="dateactivationenvoi" id="dateactivationenvoi" value="<?php echo datetime_to_fr($tableau_classe[0]["dateactivationenvoi"]); ?>" />
	    </fieldset>
        <input type="hidden" name="form_send" value="1" />
        <input type="submit" name="ajouter" id="ajouter" value="Appliquer les modifications" />
        <input type="reset" name="annuler" id="annuler" value="Annuler les modifications" />
	</form>
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
<title><?php echo $config["html-title"]?>Edition d'un abonné</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.8.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="libraries/jquery.min.js"></script>
<script type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="libraries/jquery.ui.datepicker-fr.js"></script>
<script>
	$(function() {
		$( "#dateactivationenvoi" ).datepicker();
	});
	</script>

</head>
<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item"><span><a href="tableaudebord.php" title="Tableau de bord">Tableau de bord</a></span></li>
            <li class="item selection"><span><a href="lettredinformation.php" title="Gestion des lettres d'information">Lettre d'information</a></span></li>
            <li class="item"><span><a href="abonnes.php" title="Gestion des abonnés">Abonnés</a></span></li>
            <?php if (isset($_SESSION["droit"]) && $_SESSION["droit"] == 1) { ?>
            <li class="item"><span><a href="utilisateurs.php" title="Gestion des utilisateurs">Utilisateurs</a></span></li>
            <li class="item"><span><a href="outils.php" title="Outils, maintenance">Outils</a></span></li>
            <?php } ?>
            <li class="quit-item"><span><a href="deconnexion.php" title="Déconnexion de l'espace d'administration"><img src="medias/icones/exit.png" width="48" alt="Déconnexion de l'espace d'administration" /></a></span></li>
        </ul>
    </div>
</div>
<div id="main">
    <div id="contenu">
    <div class="tableaudebord">
    <?php echo $content; ?>
    </div>
<div id="menu">
	<ul>
        <li><a href="lettredinformation.php"><img src="medias/icones/retour.png" height="64" /></a></li>
        <li><a href="lettredinformation-sup.php?uniq_id=<?php echo $uniq_id; ?>"><img src="medias/icones/news-del.png" height="64" /></a></li>
    </ul>
</div>

<div id="backtotheflux"></div>
</div>
</div>

    <div id="footer">
    <?php include("libraries/html-php/footer.inc.php"); ?>
    </div>
</body>
</html>
<?php require("libraries/secure/bddisconnect.req.php");
