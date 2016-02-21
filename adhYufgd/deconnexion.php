<?php
session_start();
include("libraries/send_head.inc.php");
	// Connexion base
	require("libraries/secure/config.req.php");
	require("libraries/secure/bdconnect.req.php");
	require("libraries/secure/fonctions.req.php");
	
	// Log bdd de la déconnexion
	$request_log_string = "INSERT INTO connexionlog VALUES ('', '".bdd_prepare($_SESSION["current_uniq_id"])."', '".bdd_prepare(date("Y-m-d H:i:s"))."', '".bdd_prepare($_SERVER["REMOTE_ADDR"])."', -1, '".bdd_prepare("Déconnexion")."' )";
	$request = mysql_query($request_log_string);
	
	
	// Destruction des données de session qui étaient enregistrées.
	unset($_SESSION);
	session_destroy();
	
	
	// Redirection vers la page de connexion
	header("location: http://".$_SERVER['HTTP_HOST']."/".$config["root"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title>Déconnexion</title>
</head>

<body>
</body>
</html>