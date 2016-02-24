<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");

// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");
//Récupération de l'ID)
if(isset($_GET["uniq_id"])) $uniq_id = $_GET["uniq_id"];
if(isset($_POST["uniq_id"])) $uniq_id = $_POST["uniq_id"];

// Chercher l'enregistrement correspondant à l'ID
// Récupération des données pour ce uniq_id
$tableau_classe = liste_abonnes("","uniq_id='".$uniq_id."'","","1","");

// FORMULAIRE ENVOYE ---------------------------------------------------------------------------------
if(isset($_POST["form_send"]) && $_POST["form_send"]==1)
{
	// Assignation des variables du formulaire
	$uniq_id = $_POST["uniq_id"];
	$mail = $_POST["mail"];
	$actif_user = (isset($_POST["actif_user"]) && $_POST["actif_user"]>0) ? 1 : 0;
	
	// Vérification des variables assignées
	$varerror["mail"] = is_valid_mail($mail);
	
	// Si aucune erreur dans les variables, modification dans la base de données **********************
	if($varerror["mail"]["etat"] == TRUE)
	{
		$data[0] = array( "uniq_id" => $uniq_id, "mail" => $mail, "actif_user" => $actif_user);
		$bdderror["bdd"] = edition_abonnes($data);
                
                
	}
	else 
        {
            $bdderror["bdd"] = array( "etat" => TRUE, "texte" => "BDD non sollicitée" );
        }
	
	// Définition du contenu à afficher en fonction des erreurs détectées **********************
		ob_start(); // Mise en tampon des contenus
		// Compte le nombre d'erreurs enregistrées pour les variables et la bdd
		$countvarerror = 0;
		$countbdderror = 0;
                foreach($varerror as $key => $value) { if($value["etat"] == FALSE) { $countvarerror += 1;} }
                foreach($bdderror as $key => $value) { if($value["etat"] == FALSE) { $countbdderror += 1;} }
		
		if($countvarerror > 0)
		{ // Si une variable n'est pas valide, afficher le formulaire à nouveau
			?>
            <p class="titre">Edition d'un abonné : <?php echo $mail; ?></p>
            <p class="erreur"><?php foreach ($varerror as $key => $value) { echo $value["texte"]."<br />"; } ?></p>
            <form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
                <label for="mail" <?php if($varerror["mail"]["etat"] == FALSE) { echo "class='erreur'"; } ?>>Adresse mail</label>
                <input type="text" name="mail" id="mail" value="<?php if($mail <> "") { echo $mail; } ?>" />
                <label for="actif">Adresse confirmée (attention, si vous cochez cette case, l'abonné sera considéré avoir confirmé son inscription et aucun mail de confirmation ne lui sera envoyé)</label>
                <input type="checkbox" name="actif_user" id="actif" value="1" <?php if($actif_user > 0) { echo "checked='checked'"; } ?> />
                <input type="hidden" name="uniq_id" value="<?php echo $uniq_id; ?>" />
                 <input type="hidden" name="form_send" value="1" />
                <input type="submit" name="Modifier" id="Modifier" value="Modifier" />
            </form>
            <?php
		}
		elseif($countbdderror > 0)
		{
			?>
            <p class="titre">Edition d'un abonné : <?php echo $mail; ?></p>
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
		}
		$content = ob_get_clean();
}
// FIN DE TRAITEMENT DU FORMULAIRE ENVOYE ------------------------------------------------------------
else
// FORMULAIRE NON ENVOYE -----------------------------------------------------------------------------
{
		ob_start();
		?>
        <p class="titre">Edition d'un abonné : <?php echo $tableau_classe[0]["mail"]; ?></p>
        <form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
            <label for="mail">Adresse mail</label>
            <input type="text" name="mail" id="mail" value="<?php echo $tableau_classe[0]["mail"]; ?>" />
            <label for="actif">Adresse confirmée (attention, si vous cochez cette case, l'abonné sera considéré avoir confirmé son inscription et aucun mail de confirmation ne lui sera envoyé)</label>
            <input type="checkbox" name="actif_user" id="actif" value="1" <?php echo ( ($tableau_classe[0]["actif_user"]<>0) ? "checked='checked'" : ""); ?> />
            <input type="hidden" name="uniq_id" value="<?php echo $uniq_id; ?>" />
             <input type="hidden" name="form_send" value="1" />
            <input type="submit" name="Modifier" id="Modifier" value="Modifier" />
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
<title><?php echo $config["html-title"]?>Edition d'un abonné</title>
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
    <?php echo $content ?>
    </div>
<div id="menu">
	<ul>
        <li><a href="abonnes.php" title="Retour à la liste des abonnés"><img src="medias/icones/retour.png" height="64" alt="Retour à la liste des abonnés" /></a></li>
    	<li><a href="abonnes-sup.php?uniq_id=<?php echo $uniq_id; ?>" title="Supprimer ce compte abonné"><img src="medias/icones/abo-del.png" height="64" alt="Supprimer ce compte abonné" /></a></li>
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
