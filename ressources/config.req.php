<?php
//------------------------------Base de données-----------------------
$db_login							= "mdfconseildb";

$db_password						= "lt7kCdjE";

$db_name							= "mdfconseildb";

$db_host							= "mdfconseildb.mysql.db";

// Configuration erreurs et divers
$config["html-title"] = "Lettre information - Administration - ";
$config["mail-administrateur"] = "info@marquedefabrique.net";
$config["contact-administrateur"] = "Nous vous prions d'excuser ce contretemps.<br />Merci de prendre contact avec l'<a href='mailto:".$config["mail-administrateur"]."'>administrateur</a>.";

// COnfiguration chemins
$config["serveur"] 		= "http://fep.mdfconseil.fr/";
//$config["root"] 		= "lettreinfo/";
$config["root"] 		= "";
$config["fromadmin"]	= "../";
$config["newsletter"]	="newsletter/";
$config["images"] 		= $config["newsletter"]."upload_li/";
$config["li_images"] 	= $config["newsletter"]."images/";
$config["li_html"] 		= $config["newsletter"]."html/";

// Configuration mailing
$config["max_mail_periode"] = 10;
$config["delai_periode_cron"] = 10; // Délai en minutes entre l'appel des tâches cron

// Configuration administration
$config["statut_li"]= array("-2" => "Archivée", "-1" => "Envoi clos", "0" => "Brouillon", "1" => "Activée", "2" => "Envoi en cours");
