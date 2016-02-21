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
	$suppr = (isset($_POST["suppr"]) && $_POST["suppr"]>0) ? 1 : 0;
	
	// Vérification des variables assignées
		// Aucune variable à vérifier
	
	// Si aucune erreur dans les variables, modification dans la base de données **********************
	if($suppr == 1)
	{
		$data[0] = array( "uniq_id" => $uniq_id, "mail" => $tableau_classe[0]["mail"]);
		$bdderror["bdd"] = suppression_abonnes($data);
                
                
	}
	else 
        {
            $bdderror["bdd"] = array( "etat" => TRUE, "texte" => "Vous n'avez pas confirmé la suppression. La suppression n'a pas été effectuée.<br />Vous pouvez :<br /><a href='".$_SERVER["PHP_SELF"]."?uniq_id=".$uniq_id."'>Confirmer la suppression</a><br /><a href='abonnes.php'>Retourner à la liste des abonnés</a>" );
        }
        
	// Définition du contenu à afficher en fonction des erreurs détectées **********************
		ob_start(); // Mise en tampon des contenus
		// Compte le nombre d'erreurs enregistrées pour les variables et la bdd
		$countbdderror = 0;
                foreach($bdderror as $key => $value) { if($value["etat"] == FALSE) { $countbdderror += 1;} }
		
		if($countbdderror > 0)
		{ ?>
            <p class="titre">Suppression d'un abonné : <?php echo $tableau_classe[0]["mail"]; ?></p>
                        <p class="erreur"><?php foreach ($bdderror as $key => $value) {echo $value["texte"]."<br />";} ?></p>
            <p><?php echo $config["contact-administrateur"]; ?></p>
            <?php
		}
		else
		{
		// Définition de la sortie du contenu principal
		?>
		<p class="titre">Confirmation de la suppression d'un abonné</p>
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
       <p class="titre">Suppression d'un abonné : <?php echo $tableau_classe[0]["mail"]; ?></p>
        <form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
            <label for="mail">Adresse mail</label>
            <input name="mail" type="text" id="mail" value="<?php echo $tableau_classe[0]["mail"]; ?>" readonly="readonly" />
            <label for="suppr">Cochez pour confirmer la suppression</label>
            <input name="suppr" type="checkbox" id="suppr" value="1"  />
            <input type="hidden" name="form_send" value="1" />
            <input type="hidden" name="uniq_id" value="<?php echo $uniq_id; ?>" />
            <input type="submit" name="supprimer" id="supprimer" value="Supprimer" />
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
<title><?php echo $config["html-title"];?>Suppression d'un abonné</title>
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
