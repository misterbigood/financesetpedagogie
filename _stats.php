<?php 
/* --------------------------------------------------------------------------------- */
/* 							Script de statistiques	 								 */
/* --------------------------------------------------------------------------------- */
// Connexion base
	require("./ressources/config.req.php");
	require("./ressources/bdconnect.req.php");
	require("./ressources/fonctions.connexion.req.php");
	
// 1: Récupération des données statistiques
$request_string_nbre_abo_actif 			= "SELECT COUNT(*) FROM abonnes WHERE actif_user = '1'";
$request_string_nbre_abo_inactif 		= "SELECT COUNT(*) FROM abonnes WHERE actif_user = '0'";
$request_string_nbre_abo_suppr 			= "SELECT COUNT(*) FROM abonnes WHERE actif_user = '-1'";
$request_string_nbre_li_envoyees		= "SELECT COUNT(DISTINCT uniq_id_li) FROM envois WHERE statut='ok'";
$request_string_nbre_abo_dest			= "SELECT COUNT(DISTINCT uniq_id_user) FROM envois WHERE statut='ok'"; 
$request_string_nbre_mess_envoyes		= "SELECT COUNT(*) FROM envois WHERE statut='ok'";

$request_nbre_abo_actif 		= mysql_fetch_row( mysql_query( $request_string_nbre_abo_actif ) );
$request_nbre_abo_inactif 		= mysql_fetch_row( mysql_query( $request_string_nbre_abo_inactif ) );
$request_nbre_abo_suppr 		= mysql_fetch_row( mysql_query( $request_string_nbre_abo_suppr ) );
$request_nbre_li_envoyees		= mysql_fetch_row( mysql_query( $request_string_nbre_li_envoyees ) );
$request_nbre_abo_dest			= mysql_fetch_row( mysql_query( $request_string_nbre_abo_dest ) );
$request_nbre_mess_envoyes		= mysql_fetch_row( mysql_query( $request_string_nbre_mess_envoyes ) );

$request_string_stats = "INSERT INTO statistiques VALUES('', '".date("Y-m-d H:i:s")."', '".($request_nbre_abo_actif[0] + $request_nbre_abo_inactif[0])."', '".$request_nbre_abo_actif[0]."', '".$request_nbre_abo_inactif[0]."', '".$request_nbre_abo_suppr[0]."', '".$request_nbre_li_envoyees[0]."', '".$request_nbre_abo_dest[0]."', '".$request_nbre_mess_envoyes[0]."')";
$request_stats = mysql_query( $request_string_stats );

?>
<?php require("./ressources/bddisconnect.req.php");