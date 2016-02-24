<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
(isset($_SESSION["droit"]) && ($_SESSION["droit"] == 1) ) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");

if(isset($_FILES["fichier"]["name"])) { $fichier	=	$_FILES["fichier"]["name"]; }

if(isset($fichier)) // Si le fichier existe
{
	
	$varerror["fichier"] = array("etat" => TRUE, "texte" => "");
	
	// Ouverture du fichier temporaire
	$fp	=	fopen($_FILES["fichier"]["tmp_name"], "r");
        if($fp == FALSE) {$varerror["ouverture"] = array("etat" => FALSE, "texte" => "L'ouverture du fichier a échoué.");}
	else
	{
		$varerror["ouverture"] = array("etat" => TRUE, "texte" => "");
		$compteur_mail = 0;
		$compteur_ignore = 0;
		$compteur_non_valide = -1;
		// Tableau de la liste actuelle
		$tableau_classe = liste_abonnes("","abonnes.actif_user<>-1","","","");
		foreach($tableau_classe as $key => $value)
		{
			$listemail[] = $value["mail"];
		}
		// Importation des données dans un tableau
		while(!feof($fp))
		{
			// Récupération des données
			$ligne = fgets($fp,4096);
			$liste = explode(";", $ligne);
			// Traitement de la liste: $liste[0] devrait contenir le mail; vérification que $liste[0] est un mail
			$mailok = trim($liste[0]);
                        if(isset($liste[1])) {$actifok = trim($liste[1]);} else {$actifok = 0;}
			$error = is_valid_mail($mailok);
			if($error["etat"] == TRUE) // Si le mail est valide
			{
				// Vérification qu'il n'est pas encore enregistré dans la base de données
				$key = array_search($mailok, $listemail);
				if($key === FALSE) // Pas d'enregistrement concordant
					{ // Enregistrement du mail dans la bdd
						$compteur_mail++;
						$data[0] = array( "mail" => mysql_real_escape_string($mailok), "actif_user" => $actifok);
						$bdderror["bdd"] = ajout_abonnes($data);	
					}
                                        else { $compteur_ignore++; }
				
			}
			else {
				$compteur_non_valide++;
				echo "Mail non valide n°".$compteur_non_valide.":".$mailok."<br />";
			}
		}
	}
}
else	// Si le fichier est inconnu
{
	$varerror["fichier"] = array("etat" => FALSE, "texte" => "L'upload du fichier a échoué.");
}
	$varerror[0]["etat"] = TRUE;
	$bdderror[0]["etat"] = TRUE;
	$countvarerror = 0;
        foreach($varerror as $key => $value) { if($value["etat"] == FALSE) {$countvarerror += 1;} }
	$countbdderror = 0;
        foreach($bdderror as $key => $value) { if($value["etat"] == FALSE) {$countbdderror += 1;} }
ob_start();
// Si aucune erreur
if($countvarerror == 0 && $countbdderror == 0)
{
	?>
            <p class="titre">Import de données</p>
			<p>Importation réussie :<br />
            Nombre de mails importés: <?php echo $compteur_mail; ?><br />
            Nombre de mails ignorés (déjà enregistrés): <?php echo $compteur_ignore; ?><br />
            Nombre de mails non valides: <?php echo $compteur_non_valide; ?></p>
    <?php
}
else
{
	?>
            <p class="titre">Import de données</p>
			<p class="erreur">
                        <?php foreach ($varerror as $key => $value) { if($value["etat"]==FALSE) { echo $value["texte"]."<br />"; } } ?>
                        <?php foreach ($bdderror as $key => $value) { if($value["etat"]==FALSE) { echo $value["texte"]."<br />"; } } ?></p>
            <p><?php echo $config["contact-administrateur"]; ?></p>
    <?php
}
$content	=	ob_get_clean();
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title><?php echo $config["html-title"]?>Import de données</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item"><span><a href="tableaudebord.php" title="Tableau de bord">Tableau de bord</a></span></li>
            <li class="item"><span><a href="lettredinformation.php" title="Gestion des lettres d'information">Lettre d'information</a></span></li>
            <li class="item"><span><a href="abonnes.php" title="Gestion des abonnés">Abonnés</a></span></li>
            <?php if (isset($_SESSION["droit"]) && $_SESSION["droit"] == 1) { ?>
            <li class="item"><span><a href="utilisateurs.php" title="Gestion des utilisateurs">Utilisateurs</a></span></li>
            <li class="item selection"><span><a href="outils.php" title="Outils, maintenance">Outils</a></span></li>
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
                 <li><a href="outils.php"><img src="medias/icones/retour.png" height="64" /></a></li>
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
