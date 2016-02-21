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

// FORMULAIRE ENVOYE --------------------------------------------------------------------------
if(isset($_POST["form_send"]) && $_POST["form_send"]==1)
{
	// Assignation des variables du formulaire
	$login		=  	$_POST["login"];
	$password	=  	$_POST["password"];
	$prenom		=	$_POST["prenom"];
	$nom		=	$_POST["nom"];
	$mail 		=	$_POST["mail"];
	(isset($_POST["actif"]) && $_POST["actif"]>0) ? $actif=1 : $actif=0;
	
	// Vérification des variables assignées
	if (strlen($login) > 0) {
        $varerror["loginstr"] = is_alphanumeric_strict($login, "l'identifiant");
    } else {
        $varerror["loginstr"] = array("etat" => FALSE, "texte" => "Vous n'avez pas entré de login.");
    }
    if (strlen($password) > 0) {
        $varerror["passwordstr"] = is_alphanumeric_strict($password, "le mot de passe");
    } else {
        $varerror["passwordstr"] = array("etat" => FALSE, "texte" => "Vous n'avez pas entré de mot de passe.");
    }
    if (strlen($prenom) > 0) {
        $varerror["prenomstr"] = is_alphanumeric_large($prenom, "le prénom");
    } else {
        $varerror["prenomstr"] = array("etat" => FALSE, "texte" => "Vous n'avez pas entré de prénom.");
    }
    if (strlen($nom) > 0) {
        $varerror["nomstr"] = is_alphanumeric_large($nom, "le nom");
    } else {
        $varerror["nomstr"] = array("etat" => FALSE, "texte" => "Vous n'avez pas entré de nom.");
    }
    $varerror["login"]			=	is_valid_login($login);
	$varerror["password"]		=	is_valid_password($password);
	if(strlen($mail) > 0)	{ $varerror["mail"]				=	is_valid_mail($mail); $varerror["mailstr"] = array("etat" => TRUE, "texte" => "");  }
							else { $varerror["mailstr"] = array("etat" => FALSE, "texte" => "Vous n'avez pas entré de mail."); $varerror["mail"]	= array("etat" => TRUE, "texte" => "");  }
	
	// Si aucune erreur dans les variables, ajout dans la base de données **********************
	$countvarerror = 0;
	foreach($varerror as $key => $value) {            if ($value["etat"] == FALSE) {
            $countvarerror += 1;
        }
    }
    if ($countvarerror == 0) {
        $data[0] = array("login" => $login,
            "password" => md5($password),
            "prenom" => $prenom,
            "nom" => $nom,
            "mail" => $mail,
            "createurcompte" => $_SESSION["current_uniq_id"],
            "actif" => $actif
        );
        $bdderror["bdd"] = ajout_utilisateurs($data);
    } else {
        $bdderror["bdd"] = array("etat" => TRUE, "texte" => "BDD non sollicitée");
    }
    // Définition du contenu à afficher en fonction des erreurs détectées **********************
		ob_start(); // Mise en tampon des contenus
		// Compte le nombre d'erreurs enregistrées pour les variables et la bdd
		$countbdderror = 0;
		foreach($bdderror as $key => $value) {                    if ($value["etat"] == FALSE) {
            $countbdderror += 1;
        }
    }
		
		if($countvarerror > 0)
		{ // Si le mail n'est pas valide, afficher le formulaire à nouveau
			?>
            <p class="titre">Ajout d'un nouvel utilisateur</p>
            <p class="erreur"><?php                                        foreach ($varerror as $key => $value) {
                                            if ($value["etat"] == FALSE) {
                                echo $value["texte"] . "<br />";
                            }
                        }
                        ?></p>
            <form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
                <label for="login"  <?php                                        if ($varerror["login"]["etat"] == FALSE || $varerror["loginstr"]["etat"] == FALSE) {
                            echo "class='erreur'";
                        }
                        ?>>Identifiant</label>
                <input type="text" name="login" id="login" value="<?php                        if ($login <> "") {
                            echo $login;
                        }
                        ?>" />
                <label for="password"  <?php                        if ($varerror["password"]["etat"] == FALSE || $varerror["passwordstr"]["etat"] == FALSE) {
                            echo "class='erreur'";
                        }
                        ?>>Mot de passe</label>
                <span>(6 caractères minimum)</span>
                <input type="text" name="password" id="password" value="<?php                if ($password <> "") {
                            echo $password;
                        }
                        ?>" />
                <label for="prenom"  <?php                        if ($varerror["prenomstr"]["etat"] == FALSE) {
                            echo "class='erreur'";
                        }
                        ?>>Prénom</label>
                <input type="text" name="prenom" id="prenom" value="<?php                if ($prenom <> "") {
                            echo $prenom;
                        }
                        ?>" />
                <label for="nom"  <?php                        if ($varerror["nomstr"]["etat"] == FALSE) {
                            echo "class='erreur'";
                        }
                        ?>>Nom</label>
                <input type="text" name="nom" id="nom" value="<?php                if ($nom <> "") {
                            echo $nom;
                        }
                        ?>" />
                <label for="mail" <?php                        if ($varerror["mail"]["etat"] == FALSE || $varerror["mailstr"]["etat"] == FALSE) {
                            echo "class='erreur'";
                        }
                        ?>>Adresse mail</label>
                <input type="text" name="mail" id="mail" value="<?php                if ($mail <> "") {
                            echo $mail;
                        }
                        ?>"  />
                <label for="actif">Compte actif</label>
                <span>(cochez cette case pour activer le compte de l'utilisateur)</span>
                <input type="checkbox" name="actif" id="actif" value="1" <?php                        if ($actif > 0) {
                            echo "checked='checked'";
                        }
                        ?> />
                <input type="hidden" name="form_send" value="1" />
                <input type="submit" name="ajouter" id="ajouter" value="Ajouter" />
            </form>
            <?php
		}
		elseif($countbdderror > 0)
		{
			?>
            <p class="titre">Ajout d'un nouvel utilisateur</p>
            <p class="erreur"><?php                            foreach ($bdderror as $key => $value) {
                            echo $value["texte"] . "<br />";
                        }
                        ?></p>
            <p><?php echo $config["contact-administrateur"]; ?></p>
            <?php
		}
		else
		{
		// Définition de la sortie du contenu principal
		?>
		<p class="titre">Confirmation de l'ajout d'un utilisateur</p>
		<p><?php echo $bdderror["bdd"]["texte"]; ?></p>
		<?php
		}
		$content = ob_get_clean();
} // --------------------FIN TRAITEMENT FORMULAIRE ENVOYE --------------------------------------
else 
// FORMULAIRE NON ENVOYE (AFFICHAGE SIMPLE) ----------------------------------------------------
{
	ob_start();
	?>
    <p class="titre">Ajout d'un nouvel utilisateur</p>
	<form id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="formulaire">
	    <label for="login">Identifiant</label>
	    <input type="text" name="login" id="login" value="" />
        <label for="password">Mot de passe</label>
	    <span>(6 caractères minimum)</span>
        <input type="text" name="password" id="password" value="" />
        <label for="prenom">Prénom</label>
	    <input type="text" name="prenom" id="prenom" value="" />
        <label for="nom">Nom</label>
	    <input type="text" name="nom" id="nom" value="" />
        <label for="mail">Adresse mail</label>
	    <input type="text" name="mail" id="mail" value="" />
        <label for="actif">Compte actif</label>
        <span>(cochez cette case pour activer le compte de l'utilisateur)</span>
        <input type="checkbox" name="actif" id="actif" value="1" />
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
<title><?php echo $config["html-title"]?>Ajout d'un compte utilisateur</title>
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
            <li class="item selection"><span><a href="utilisateurs.php" title="Gestion des utilisateurs">Utilisateurs</a></span></li>
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
        <li><a href="utilisateurs.php" title="Retour à la liste des utilisateurs de l'administration"><img src="medias/icones/retour.png" height="64" alt="Retour à la liste des utilisateurs de l'administration" /></a></li>
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