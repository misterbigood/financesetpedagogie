<?php
// Vérification de l'accès:
//defined ("CONNEXION_VALIDE") or header("location: http://".$_SERVER['HTTP_HOST']);
$db = mysql_connect($db_host,$db_login,$db_password);
// Traitement erreur de connexion
if($db == FALSE) $bdderror["connexion"] = array( "etat" => FALSE, "texte" => "La connexion au serveur de base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." );

$selectdb = mysql_select_db($db_name,$db);
// Traitement erreur de connexion
if($selectdb == FALSE) $bdderror["selection"] = array( "etat" => FALSE, "texte" => "La connexion à la base de données choisie n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." );
?>
