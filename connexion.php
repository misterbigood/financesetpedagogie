<?php
// Vérification de l'accès:
session_start();
ob_start(); // Mise en tampon des contenus
if(!isset($_SESSION["connexion_attempt"])) $_SESSION["connexion_attempt"] = 3;
else $_SESSION["connexion_attempt"]--;

// FORMULAIRE ENVOYE --------------------------------------------------------------------------
if(isset($_POST["form_send"]) && $_POST["form_send"]==1 && $_SESSION["connexion_attempt"] >= 0)
{
	
	// Connexion base
	require("./ressources/config.req.php");
	require("./ressources/bdconnect.req.php");
	require("./ressources/fonctions.connexion.req.php");
	// Assignation des variables du formulaire
	$login 			= $_POST["identifiant"];
	$password		= $_POST["motdepasse"];
		
	// Vérification des variables assignées
	if(strlen($login) > 0)	$varerror["loginstr"]			= 	is_alphanumeric_strict( $login, "l'identifiant");
							else $varerror["loginstr"]	 	= array("etat" => FALSE, "texte" => "Vous n'avez pas entré d'identifiant.");
	if($password <> "d41d8cd98f00b204e9800998ecf8427e")	$varerror["passwordstr"]	= 	is_alphanumeric_strict( $password, "le mot de passe");
							else $varerror["passwordstr"] 	= array("etat" => FALSE, "texte" => "Vous n'avez pas entré de mot de passe.");
	
	$countvarerror = 0;
	foreach($varerror as $key => $value) { if($value["etat"] == FALSE) $countvarerror += 1; }
	if($countvarerror == 0 )
	{
		$data = array( 	"login" => $login, 
						"password" => $password
						);
		$bdderror["bdd"] = verification_connexion($data);
	}
	else $bdderror["bdd"] = array( "etat" => FALSE, "texte" => "" );
	
	// Définition du contenu à afficher en fonction des erreurs détectées **********************
		
		// Compte le nombre d'erreurs enregistrées pour les variables et la bdd
		$countbdderror = 0;
		foreach($bdderror as $key => $value) { if($value["etat"] == FALSE) $countbdderror += 1; }
		if($countvarerror > 0 || $countbdderror > 0)
		{
			
			if($_SESSION["connexion_attempt"] <= 0)
			{
				?>
                <p class="titre">Connexion à l'interface d'administration</p>
                 <p class="erreur"><?php foreach ($varerror as $key => $value) if ($value["etat"] == FALSE) echo $value["texte"]."<br />"; ?></p>
           		 <p class="erreur"><?php foreach ($bdderror as $key => $value) if ($value["etat"] == FALSE) echo $value["texte"]."<br />"; ?></p>
                <p class="erreur"><strong>Vous avez dépassé le nombre de tentatives autorisées pour cette session. Merci de vous reconnecter.</strong></p>
				<?php
			}
			else
			{// Réaffichage du formulaire avec les erreurs
			?>
            <p class="titre">Connexion à l'interface d'administration</p>
            <p>Tentatives de connexion restantes : <?php echo ($_SESSION["connexion_attempt"]);?></p>
            <p class="erreur"><?php foreach ($varerror as $key => $value) if ($value["etat"] == FALSE) echo $value["texte"]."<br />"; ?></p>
            <p class="erreur"><?php foreach ($bdderror as $key => $value) if ($value["etat"] == FALSE) echo $value["texte"]."<br />"; ?></p>
            <form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
                <label for="identifiant">Identifiant</label>
                <input type="text" name="identifiant" id="identifiant" />
           	 <label for="motdepasse">Mot de passe</label>
                <input type="password" name="motdepasse" id="motdepasse" />
                <input type="hidden" name="form_send" value="1" />
                <input onClick="loginValidation();" type="submit" name="valider" id="valider" value="Connexion" />
     	 </form>
	  <?php	}
		}
		else header("location: http://".$_SERVER['HTTP_HOST'].$config['root']."/adhYufgd/tableaudebord.php"); // Connexion OK, on va sur le tableau de bord
}
else
{
	?>
    <p class="titre">Connexion à l'interface d'administration</p>
    <p>Tentatives de connexion restantes : <?php echo ($_SESSION["connexion_attempt"]);?></p>
            <form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
                <label for="identifiant">Identifiant</label>
                <input type="text" name="identifiant" id="identifiant" />
           	 <label for="motdepasse">Mot de passe</label>
                <input type="password" name="motdepasse" id="motdepasse" />
                <input type="hidden" name="form_send" value="1" />
                <input onClick="loginValidation();" type="submit" name="valider" id="valider" value="Connexion" />
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
<title>Finances et pédagogie - Système de gestion de lettres d'information - &copy; MDF 2011-2016</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="./ressources/validation.js"></script> 
</head>

<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item selection"><span><a>Finances & Pédagogie - Administration de la lettre d'information</a></span></li>
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