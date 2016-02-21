<?php
// Fichier de traitement du formulaire envoyé lettre d'information, ajout
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
//1 : Traitement des variables
// 1.1: Assignation des variables
        if(isset($_POST["numero"]) ) {					$numero	=	$_POST["numero"]; }
                else 	{ $numero	=	""; }
        if(isset($_POST["actif_li"]) )	{				$actif_li	=	1; }
                else 	{ $actif_li	=	-1; }
        if( isset( $_POST["dateactivationenvoi"] )	&& $_POST["dateactivationenvoi"] <> ""  ) {	$dateactivationenvoi		=	fr_to_datetime($_POST["dateactivationenvoi"]); }
                else {	$dateactivationenvoi	=	date("Y-m-d 00:00:01"); }
        if($actif_li == 1) { $datedernierenvoi = "0000-00-00 00:00:00"; }

	$varerror["date"] = is_valid_date( $dateactivationenvoi );

// Enregistrement dans la base de données
if(isset($varerror["date"]["etat"]) && $varerror["date"]["etat"] <> FALSE)
{
$data[0] = array ( "uniq_id" => $uniq_id,  "actif_li" => $actif_li, "datedernierenvoi" => $datedernierenvoi, "dateactivationenvoi" => bdd_prepare($dateactivationenvoi), "numero" => $numero );

// Si aucune erreur sur les variables
$bdderror["bdd"] = modification_li( $data );
}
else
{
$data[0] = array ( "uniq_id" => $uniq_id, "actif_li" => $actif_li, "datedernierenvoi" => $datedernierenvoi, "dateactivationenvoi" => "0000-00-00 00:00:00", "numero" => $numero );

// Si aucune erreur sur les variables
$bdderror["bdd"] = modification_li( $data );	
}