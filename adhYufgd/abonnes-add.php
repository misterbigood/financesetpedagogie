<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");

// FORMULAIRE ENVOYE --------------------------------------------------------------------------
if(isset($_POST["form_send"]) && filter_input(INPUT_POST, 'form_send')==1)
{
	// Assignation des variables du formulaire
	$mail = filter_input(INPUT_POST, 'mail');
	$actif_user = (filter_input(INPUT_POST, 'actif_user')>0) ? 1 : 0;
	
	// Vérification des variables assignées
	$varerror["mail"]	=	is_valid_mail($mail);
	
	
	// Si aucune erreur dans les variables, ajout dans la base de données **********************
	if($varerror["mail"]["etat"] == TRUE)
	{
		$data[0] = array( "mail" => mysql_real_escape_string($mail), "actif_user" => $actif_user);
		$bdderror["bdd"] = ajout_abonnes($data);
                
                

	}
	else $bdderror["bdd"] = array( "etat" => TRUE, "texte" => "BDD non sollicitée" );
	// Définition du contenu à afficher en fonction des erreurs détectées **********************
		ob_start(); // Mise en tampon des contenus
		// Compte le nombre d'erreurs enregistrées pour les variables et la bdd
		$countvarerror = 0;
		$countbdderror = 0;
		foreach($varerror as $key => $value) { if($value["etat"] == FALSE) $countvarerror += 1; }
		foreach($bdderror as $key => $value) { if($value["etat"] == FALSE) $countbdderror += 1; }
		
		if($countvarerror > 0)
		{ // Si le mail n'est pas valide, afficher le formulaire à nouveau
			?>
            <p class="titre">Ajout d'un nouvel abonné</p>
            <p class="erreur"><?php foreach ($varerror as $key => $value) echo $value["texte"]."<br />"; ?></p>
            <form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
                <label for="mail" <?php if($varerror["mail"]["etat"] == FALSE) echo "class='erreur'"; ?>>Adresse mail</label>
                <input type="text" name="mail" id="mail" value="<?php if($mail <> "") echo $mail; ?>" />
                <label for="actif">Adresse confirmée</label>
                <span>(attention, si vous cochez cette case, l'abonné sera considéré avoir confirmé son inscription et aucun mail de confirmation ne lui sera envoyé)</span>
                <input type="checkbox" name="actif_user" id="actif" value="1" <?php if($actif_user > 0) echo "checked='checked'"; ?> />
                <input type="hidden" name="form_send" value="1" />
                <input type="submit" name="ajouter" id="ajouter" value="Ajouter" />
            </form>
            <?php
		}
		elseif($countbdderror > 0)
		{
			?>
            <p class="titre">Ajout d'un nouvel abonné</p>
			<p class="erreur"><?php foreach ($bdderror as $key => $value) echo $value["texte"]."<br />"; ?></p>
            <p><?php echo $config["contact-administrateur"]; ?></p>
            <?php
		}
		else
		{
		// Définition de la sortie du contenu principal
		?>
		<p class="titre">Confirmation de l'ajout d'un abonné</p>
		<p><?php echo $bdderror["bdd"]["texte"]; ?></p>
		<?php
		// Envoi du mail de confirmation à l'abonné si il n'est pas coché actif
		if($actif_user == 0) {
			include_once("libraries/secure/class.phpmailer.php");
			$email             = new PHPMailer();
			$email->IsSendmail();
			$email->CharSet = "UTF-8";
			$email->Subject = "Paroles d'argent - Confirmation de votre abonnement";
			$email->AddAddress($mail, "");
			$email->From       = "mdfconseil@finances-pedagogie.fr";
			$email->FromName   = "Noreply";
			// 3: Préparation du mail
			$body             = $email->getFile("libraries/html-php/confirmation-part1.html");
			$body			 .= "?uniq_id=".$bdderror["bdd"]["uniq_id"];
			$body			 .= $email->getFile("libraries/html-php/confirmation-part2.html");
			$email->MsgHTML($body);
			$email->Send();
		}
		// Fin d'envoi du mail
		}
		$content = ob_get_clean();
} // --------------------FIN TRAITEMENT FORMULAIRE ENVOYE --------------------------------------
else 
// FORMULAIRE NON ENVOYE (AFFICHAGE SIMPLE) ----------------------------------------------------
{
	ob_start();
	?>
    <p class="titre">Ajout d'un nouvel abonné</p>
	<form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
	    <label for="mail">Adresse mail</label>
	    <input type="text" name="mail" id="mail" value="" />
	  	<label for="actif">Adresse confirmée</label>
        <span>(attention, si vous cochez cette case, l'abonné sera considéré avoir confirmé son inscription et aucun mail de confirmation ne lui sera envoyé)</span>
	    <input type="checkbox" name="actif_user" id="actif" value="1" />
        <input type="hidden" name="form_send" value="1" />
        <input type="submit" name="ajouter" id="ajouter" value="Ajouter" />
	</form>
    <?php
	$content = ob_get_clean();
} // ----------------------- FIN TRAITEMENT FORMULAIRE SIMPLE ----------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title><?php echo $config["html-title"]?>Ajout d'un compte abonné</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item"><span><a href="tableaudebord.php" title="Tableau de bord">Tableau de bord</a></span></li>
            <li class="item"><span><a href="lettredinformation.php" title="Gestion des lettres d'information">Lettre d'information</a></span></li>
            <li class="item selection"><span><a href="abonnes.php" title="Gestion des abonnés">Abonnés</a></span></li>
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
    <?php 
	// ---------------------------- AFFICHAGE DU CONTENU FORMULAIRE OU RESULTAT DE L'ENVOI DU FORMULAIRE ----------------------------
	echo $content;
	// ---------------------------- FIN AFFICHAGE DU CONTENU FORMULAIRE OU RESULTAT DE L'ENVOI DU FORMULAIRE ----------------------------
	?>
    </div>
<div id="menu">
	<ul>
        <li><a href="abonnes.php" title="Retour à la liste des abonnés"><img src="medias/icones/retour.png" height="64" alt="Retour à la liste des abonnés" /></a></li>
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
<?php require("libraries/secure/bddisconnect.req.php"); ?>
