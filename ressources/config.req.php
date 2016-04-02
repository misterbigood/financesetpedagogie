<?php
//------------------------------Base de données-----------------------
$db_login							= "dbo394387949";

$db_password						= "jUGyT45d6";

$db_name							= "db394387949";

$db_host							= "db394387949.db.1and1.com:3306";

// Configuration erreurs et divers
$config["html-title"] = "Lettre information - Administration - ";
$config["mail-administrateur"] = "info@marquedefabrique.net";
$config["contact-administrateur"] = "Nous vous prions d'excuser ce contretemps.<br />Merci de prendre contact avec l'<a href='mailto:".$config["mail-administrateur"]."'>administrateur</a>.";

// COnfiguration chemins
$config["serveur"] 		= "http://www.finances-pedagogie.fr/";
$config["root"] 		= "lettreinfo/";
$config["fromadmin"]	= "../";
$config["newsletter"]	="newsletter/";
$config["images"] 		= $config["newsletter"]."upload_li/";
$config["li_images"] 	= $config["newsletter"]."images/";
$config["li_html"] 		= $config["newsletter"]."html/";

// Configuration mailing
$config["max_mail_periode"] = 100;
$config["delai_periode_cron"] = 10; // Délai en minutes entre l'appel des tâches cron

// Configuration administration
$config["statut_li"]= array("-2" => "Archiv&eacute;e", "-1" => "Envoi clos", "0" => "Brouillon", "1" => "Activ&eacute;e", "2" => "Envoi en cours");
?>
