<?php
/* *************************** FONCTIONS D'INSERTION DANS LA BASE DE DONNEES ******************************************** */
/* ***************************                                                                                     ****** */
/* ***************************       Toutes les fonctions d'insertion                                              ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */

/*---------------- Fonction d'ajout des abonnés -------------*/
function ajout_abonnes( $data )
{
	// Déclaration des variables
	
	
	//Requête
	foreach($data as $key => $value)
	{
		$dateabonnement = date("Y-m-d H:i:s");
		$uniq_id = md5($value["mail"].$dateabonnement);
		$request_string = "INSERT IGNORE INTO abonnes VALUES ('', '".bdd_prepare($uniq_id)."', '".bdd_prepare(strtolower($value["mail"]))."', '0', '0000-00-00 00:00:00', '".$dateabonnement."', '0000-00-00 00:00:00', '".$value["actif_user"]."' ); ";
	}
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Ajout confirmé.<br />L'enregistrement est stocké sous l'intitulé <em>".$value["mail"]."</em>.<br />Vous pouvez <a href='abonnes.php'>retourner à la liste des abonnés.</a>" ); }
	$resultatrequete["uniq_id"] = $uniq_id;
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/
/*---------------- Fonction d'ajout des utilisateurs -------------*/
function ajout_utilisateurs( $data )
{
	//Requête
	foreach($data as $key => $value)
	{
		$creationcompte = date("Y-m-d H:i:s");
		$uniq_id = md5($value["mail"].$creationcompte.$value["login"]);
		$request_string = "INSERT INTO utilisateurs VALUES ('', '".bdd_prepare($uniq_id)."', '".bdd_prepare(strtolower($value["login"]))."', '".bdd_prepare($value["password"])."', '".bdd_prepare(strtolower($value["nom"]))."', '".bdd_prepare(strtolower($value["prenom"]))."', '".bdd_prepare(strtolower($value["mail"]))."', '', '".$creationcompte."', '".bdd_prepare($value["createurcompte"])."', '".$creationcompte."', '0000-00-00 00:00:00', '".bdd_prepare($value["actif"])."' ); ";
	}
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Ajout confirmé. <br />Vous pouvez <a href='utilisateurs.php'>retourner à la liste des utilisateurs.</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/*---------------- Fonction d'ajout des li -------------*/
function ajout_li( $data )
{
	//Requête
	foreach($data as $key => $value)
	{
		$datecreation = $datemodification = date("Y-m-d H:i:s");
		
		if ($value["actif_li"]>0) { $actif_li = 1; }
		else { $actif_li = 0; }
		
		$uniq_id = md5($value["dataseries"].$datecreation);
		
		$request_string = "INSERT INTO lettredinformation VALUES ('', '".$uniq_id."', '".$value["numero"]."', '".$value["li_titre"]."', '".$value["dataseries"]."', '".$value["lienpdffr"]."', '".$value["lienpdfen"]."', '".$datecreation."', '".$datemodification."', '".$value["dateactivationenvoi"]."', '0000-00-00 00:00', '0000-00-00 00:00', '".$actif_li."' ); ";
	}
	$request 	= mysql_query(	$request_string ) or die("erreur");
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Ajout confirmé. La lettre d'information est stockée sous le numéro ".$value["numero"].".<br />Vous pouvez <a href='lettredinformation.php'>retourner à la liste des lettres d'information.</a>", "uniq_id" => $uniq_id );
	 }
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/* *************************** FIN DES FONCTIONS D'INSERTION DANS LA BASE DE DONNEES ************************************ */


/* *************************** FONCTIONS DE MODIFICATION DANS LA BASE DE DONNEES **************************************** */
/* ***************************                                                                                     ****** */
/* ***************************       Toutes les fonctions de modification                                          ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */

/*---------------- Fonction d'édition des abonnés -------------*/
function edition_abonnes( $data )
{
	// Déclaration des variables
	
	
	//Requête
	foreach($data as $key => $value)
	{
		$request_string = "UPDATE abonnes SET abonnes.actif_user='".$value["actif_user"]."', abonnes.mail='".bdd_prepare($value["mail"])."' WHERE abonnes.uniq_id='".bdd_prepare($value["uniq_id"])."' ; ";
	}
	$request 	= mysql_query(	$request_string ) or die("erreur");
	
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Modification confirmée.<br />L'enregistrement est stocké sous l'intitulé <em>".$value["mail"]."</em>.<br />Vous pouvez <a href='abonnes.php'>retourner à la liste des abonnés.</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/*---------------- Fonction d'édition des abonnés -------------*/
function edition_utilisateurs( $data )
{
	// Déclaration des variables
	
	
	//Requête
	$modificationcompte = date("Y-m-d H:i:s");
	$request_string = "UPDATE utilisateurs SET ";
	foreach($data[0] as $key => $value)
	{
		$request_string .= "utilisateurs.".$key."='".bdd_prepare($value)."', ";
	}
	$request_string .= "utilisateurs.modificationcompte='".$modificationcompte."' WHERE utilisateurs.uniq_id='".$data[0]["uniq_id"]."' ; ";
	$request 	= mysql_query(	$request_string );
	
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Modification confirmée.<br />Vous pouvez <a href='utilisateurs.php'>retourner à la liste des utilisateurs.</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/*---------------- Fonction de modification des li -------------*/
function modification_li( $data )
{
	// Déclaration des variables
	//Requête
	foreach($data as $key => $value)
	{
		$datemodification = date("Y-m-d H:i:s");
		
		
		$request_string = "UPDATE lettredinformation SET ";
		foreach($value as $key1 => $value1)
		{
			$request_string .= "lettredinformation.".$key1."='".$value1."', ";
		}
		$request_string .= "lettredinformation.datemodification='".$datemodification."' WHERE lettredinformation.uniq_id='".$data[0]["uniq_id"]."' ; ";
	}
		
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Modification confirmée. La lettre d'information est enregistrée sous le n°".$value["numero"].".<br />Vous pouvez <a href='lettredinformation.php'>retourner à la liste des lettres d'information.</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/


/* *************************** FIN DES FONCTIONS DE MODIFICATION DANS LA BASE DE DONNEES ******************************** */


/* *************************** FONCTIONS DE SUPPRESSION DANS LA BASE DE DONNEES ***************************************** */
/* ***************************                                                                                     ****** */
/* ***************************       Toutes les fonctions de suppression                                           ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */

/*---------------- Fonction de suppression des abonnés -------------*/
function suppression_abonnes( $data )
{
	// Déclaration des variables
	
	
	//Requête
	foreach($data as $key => $value)
	{
		$newmail = md5($value["mail"].rand()).substr($value["mail"],strpos($value["mail"],"@"));
		$request_string = "UPDATE abonnes SET abonnes.datedesabonnement='".date("Y-m-d H:i:s")."', abonnes.actif_user=-1, abonnes.mail='".$newmail."' WHERE abonnes.uniq_id='".$value["uniq_id"]."' ; ";
	}
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Suppression confirmée.<br />L'enregistrement est stocké sous l'intitulé <em>".$newmail."</em>.<br />Vous pouvez <a href='abonnes.php'>retourner à la liste des abonnés.</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/*---------------- Fonction de suppression des abonnés -------------*/
function suppression_utilisateurs( $data )
{
	// Déclaration des variables
	
	
	//Requête
	foreach($data as $key => $value)
	{
		$request_string = "DELETE FROM utilisateurs WHERE utilisateurs.uniq_id='".$value["uniq_id"]."' ; ";
	}
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Suppression confirmée.<br />Vous pouvez <a href='utilisateurs.php'>retourner à la liste des utilisateurs.</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/*---------------- Fonction de suppression des abonnés -------------*/
function archivage_li( $data )
{
	// Déclaration des variables
	
	
	//Requête
	foreach($data as $key => $value)
	{
		$request_string = "UPDATE lettredinformation SET lettredinformation.actif_li='-2' WHERE lettredinformation.uniq_id='".$value["uniq_id"]."' ; ";
	}
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Archivage confirmé.<br />Vous pouvez <a href='lettredinformation.php'>retourner à la liste des lettres d'information</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/*---------------- Fonction de suppression des abonnés -------------*/
function suppression_li( $data )
{
	// Déclaration des variables
	
	
	//Requête
	foreach($data as $key => $value)
	{
		$request_string = "DELETE FROM lettredinformation WHERE lettredinformation.uniq_id='".$value["uniq_id"]."' ; ";
	}
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "Suppression confirmée.<br />Vous pouvez <a href='lettredinformation.php'>retourner à la liste des lettres d'information</a>" ); }
	
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/

/* *************************** FIN DES FONCTIONS DE SUPPRESSION DANS LA BASE DE DONNEES ********************************* */

/* *************************** FONCTIONS DE LISTE DE LA BASE DE DONNEES ************************************************* */
/* ***************************                                                                                     ****** */
/* ***************************       Toutes les fonctions de liste                                                 ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */

/*---------------- Fonction de liste des utilisateurs -------------*/
function liste_users( $data_select, $data_where, $data_order, $data_limit )
{
	// Déclaration des variables
	
	
	//Requête
	$data_select<>"" ? $request_string = "SELECT ".$data_select." FROM utilisateurs" : $request_string = "SELECT * FROM utilisateurs";
	$data_where <>"" ? $request_string .= " WHERE ".$data_where :  FALSE;
	$data_order <>"" ? $request_string .= " ORDER BY ".$data_order :  FALSE;
	$data_limit <>"" ? $request_string .= " LIMIT ".$data_limit : FALSE;
	
	$request 	= mysql_query(	$request_string );
	
		// Traitement de la requête
		while($resultat=mysql_fetch_array($request))
		{
			$id_user			=	utf8_encode($resultat["id_user"]);
			$uniq_id			=	utf8_encode($resultat["uniq_id"]);
			$login				=	utf8_encode($resultat["login"]);
			$password			=	utf8_encode($resultat["password"]);
			$nom				=	utf8_encode($resultat["nom"]);
			$prenom				=	utf8_encode($resultat["prenom"]);
			$mail				=	utf8_encode($resultat["mail"]);
			$creationcompte		=	utf8_encode($resultat["creationcompte"]);
			$createurcompte		=	utf8_encode($resultat["createurcompte"]);
			$lastconnexion		=	utf8_encode($resultat["lastconnexion"]);
			$actif				=	utf8_encode($resultat["actif"]);
			
			$tableau_classe[] 	= array (	"id_user" 			=> $id_user, 
											"uniq_id" 			=> $uniq_id, 
											"login" 			=> $login, 
											"password" 			=> $password, 
											"nom" 				=> $nom, 
											"prenom" 			=> $prenom, 
											"mail"				=> $mail,
											"creationcompte" 	=> $creationcompte,
											"createurcompte"	=> $createurcompte,
											"lastconnexion"		=> $lastconnexion,
											"actif"				=> $actif);
		}
	
	// Traitement de la requête
	if ( $request == TRUE && isset($tableau_classe) ) return( $tableau_classe );
	elseif ( $request == TRUE && !isset($tableau_classe) ) return( FALSE );
	elseif ( $request == FALSE ) $resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." );
}
/* ------------------------Fin de fonction ---------------------------*/


/*---------------- Fonction de liste des abonnés -------------*/
function liste_abonnes( $data_select, $data_where, $data_order, $data_limit )
{
	// Déclaration des variables
	
	
	//Requête
	$data_select<>"" ? $request_string = "SELECT ".$data_select." FROM abonnes" : $request_string = "SELECT * FROM abonnes";
	$data_where <>"" ? $request_string .= " WHERE ".$data_where :  FALSE;
	$data_order <>"" ? $request_string .= " ORDER BY ".$data_order :  FALSE;
	$data_limit <>"" ? $request_string .= " LIMIT ".$data_limit : FALSE;
	
	$request 	= mysql_query(	$request_string );
	
	// Traitement de la requête
	while($resultat=mysql_fetch_array($request))
  	{
	    $id_user			=	utf8_encode($resultat["id_user"]);
		$uniq_id			=	utf8_encode($resultat["uniq_id"]);
		$mail				=	utf8_encode($resultat["mail"]);
		$lastnewsletter		=	utf8_encode($resultat["lastnewsletter"]);
	    $lastsent			=	utf8_encode($resultat["lastsent"]);
	    $dateabonnement		=	utf8_encode($resultat["dateabonnement"]);
	    $datedesabonnement	=	utf8_encode($resultat["datedesabonnement"]);
		$actif_user			=	utf8_encode($resultat["actif_user"]);
		
		
		$tableau_classe[] 	= array (	"id_user" 			=> $id_user, 
										"uniq_id" 			=> $uniq_id, 
										"mail" 				=> $mail, 
										"lastnewsletter" 	=> $lastnewsletter, 
										"lastsent" 			=> $lastsent, 
										"dateabonnement" 	=> $dateabonnement, 
										"datedesabonnement" => $datedesabonnement,
										"actif_user"		=> $actif_user);
	}
	return($tableau_classe);
}
/* ------------------------Fin de fonction ---------------------------*/

/*---------------- Fonction de liste des li -------------*/
function liste_li( $data_select, $data_where, $data_order, $data_limit )
{
	// Déclaration des variables
	
	
	//Requête
	$data_select<>"" ? $request_string = "SELECT ".$data_select." FROM lettredinformation" : $request_string = "SELECT * FROM lettredinformation";
	$data_where <>"" ? $request_string .= " WHERE ".$data_where :  FALSE;
	$data_order <>"" ? $request_string .= " ORDER BY ".$data_order :  FALSE;
	$data_limit <>"" ? $request_string .= " LIMIT ".$data_limit : FALSE;
	
	$request 	= mysql_query(	$request_string );
	
	// Traitement de la requête
	while($resultat=mysql_fetch_array($request))
  	{
	    $id_li				=	utf8_encode($resultat["id_li"]);
		$uniq_id			=	utf8_encode($resultat["uniq_id"]);
		$numero				=	utf8_encode($resultat["numero"]);
		$li_titre			=	utf8_encode($resultat["li_titre"]);
		$dataseries			=	$resultat["dataseries"];
		$lienpdffr			=	utf8_encode($resultat["lienpdffr"]);
		$lienpdfen			=	utf8_encode($resultat["lienpdfen"]);
	    $datecreation		=	utf8_encode($resultat["datecreation"]);
	    $datemodification	=	utf8_encode($resultat["datemodification"]);
	    $dateactivationenvoi=	utf8_encode($resultat["dateactivationenvoi"]);
		$datepremierenvoi	=	utf8_encode($resultat["datepremierenvoi"]);
	    $datedernierenvoi	=	utf8_encode($resultat["datedernierenvoi"]);
		$actif_li			=	utf8_encode($resultat["actif_li"]);
		
		
		$tableau_classe[] 	= array (	"id_li" 				=> $id_li, 
										"uniq_id" 				=> $uniq_id,
										"numero"				=> $numero,
										"li_titre"				=> $li_titre,
										"dataseries"			=> $dataseries, 
										"lienpdffr" 			=> $lienpdffr, 
										"lienpdfen" 			=> $lienpdfen, 
										"datecreation" 			=> $datecreation, 
										"datemodification" 		=> $datemodification,
										"dateactivationenvoi" 	=> $dateactivationenvoi, 
										"datepremierenvoi" 		=> $datepremierenvoi,
										"datedernierenvoi"		=> $datedernierenvoi,
										"actif_li"				=> $actif_li);
	}
	return($tableau_classe);
}
/* ------------------------Fin de fonction ---------------------------*/


/* *************************** FIN DES FONCTIONS DE LISTE DANS LA BASE DE DONNEES *************************************** */





/* *************************** FONCTIONS DE PREPARATION ET DE TRAITEMENT DES DONNEES POUR INSERTION OU LECTURE BDD ****** */
/* ***************************                                                                                     ****** */
/* ***************************         Fonctions de formatage des données, fonctions de sécurité avant insertion   ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */

/* ------------------------Fonction de timestamp-----------------------*/
function formatdateheure($datetime) {
	return strftime("%d %b %Y à %H:%M", strtotime($datetime));
}

/* -------------------------Fin foncton de timestamp -----------------*/

/* ------------------------Conversion date time vers JJ/MM/AAAA-----------------------*/
function datetime_to_fr($datetime) {
	$tab = explode("-", substr($datetime, 0, 10) );
	
	return ( trim($tab[2])."/".trim($tab[1])."/".trim($tab[0]) );
}

/* -------------------------Fin foncton  -----------------*/

/* ------------------------Conversion  JJ/MM/AAAA vers date time-----------------------*/
function fr_to_datetime($string) {
	$date_detail	=	explode("/",$string);
	$datetime		=	trim($date_detail[2])."-".sprintf("%02s",trim($date_detail[1]))."-".sprintf("%02s",trim($date_detail[0]))." 00:00:01";
	
	return ( $datetime );
}

/* -------------------------Fin foncton  -----------------*/





/* ------------------------ Fonction de préparation des données pour la bdd -----------------------*/
function bdd_prepare ($string) {
	return (mysql_real_escape_string(htmlentities($string, ENT_COMPAT, 'UTF-8')));
	return ($string);
}
/* *********************** FIN FONCTIONS DE PREPARATION ET DE TRAITEMENT DES DONNEES POUR INSERTION OU LECTURE BDD ****** */







/* ********************************* FONCTIONS DEVERIFICATION DES DONNEES FORMULAIRES *********************************** */
/* ***************************                                                                                     ****** */
/* ***************************      Fonction de vérification des formats de mail, dates, etc.                      ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */
function is_valid_mail($mail) {
	$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
	$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
                               
	$regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
	'(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
                                	// séparés par des caractères autorisés avant l'arobase
	'@' .                           // Suivis d'un arobase
	'(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
                                	// séparés par des points
	$domain . '{2,63}$/i';          // Suivi de 2 à 63 caractères autorisés pour le nom de domaine
	
	
	if(preg_match($regex, $mail)) $verifmail = array( "etat" => TRUE, "texte" => "" );
	else $verifmail = array( "etat" => FALSE, "texte" => "Le mail indiqué n'est pas valide. Merci de le corriger." );
	return ( $verifmail );
}

function is_valid_date($date) {
	// SOurce: preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)
	
	$regex	= "/^(20[0-9]{2})-([01]{1}[0-9]{1})-([0-3]{1}[0-9]{1})\s([0-2]{1}[0-9]{1}):([0-5]{1}[0-9]{1}):([0-5]{1}[0-9]{1})$/";
	if( preg_match($regex, $date, $parts) )
	{
		if( checkdate($parts[2], $parts[3], $parts[1]) ) $verifdate = array( "etat" => TRUE, "texte" => "" );
		else $verifdate = array( "etat" => FALSE, "texte" => "La date indiquée n'est pas valide. Merci de la corriger." );
	}
	return( $verifdate );
}
/* Vérification des doublons dans les logins */
function is_valid_login($login) {
	$reponse_requete = liste_users("","utilisateurs.login='".mysql_real_escape_string($login)."'","","");
	if( $reponse_requete == FALSE ) $verif_login = array("etat" => TRUE, "texte" => "" );  // No match, le login n'est pas encore enregistré
	elseif( isset($reponse_requete["etat"]) && $reponse_requete["etat"]== FALSE ) $verif_login = array("etat" => FALSE, "texte" => $reponse_requete["texte"] );
	else $verif_login = array("etat" => FALSE, "texte" => "Ce login a déjà été enregistré. Merci d'en changer ou d'annuler l'inscription." );
	return( $verif_login );
}

/* Vérification de la taille mini du mot de passe */
function is_valid_password($password) {
	if(strlen($password) < 6) $verif_password =  array( "etat" => FALSE, "texte" => "Le mot de passe fait moins de six caractères." );
	else $verif_password = array( "etat" => TRUE, "texte" => "" );
	return( $verif_password );
}

/* Vérification des caractères utilisés */
function is_alphanumeric_strict($string, $object) {
	if( !preg_match("/^[0-9A-Za-z]+$/", $string) )  $verif_string =  array( "etat" => FALSE, "texte" => "Vous avez employé des caractères non autorisés dans ".$object."." );
	else $verif_string = array( "etat" => TRUE, "texte" => "" );
	return( $verif_string );
}

function is_alphanumeric_large($string, $object) {
	if( !preg_match("/^[0-9A-Za-zàâäéèêëîïôöûü]+$/", $string) )  $verif_string =  array( "etat" => FALSE, "texte" => "Vous avez employé des caractères non autorisés dans ".$object."." );
	else $verif_string = array( "etat" => TRUE, "texte" => "" );
	return( $verif_string );
}

function is_numerique($string, $object) {
	if( !preg_match("/^[0-9]+$/", $string) )  $verif_string =  array( "etat" => FALSE, "texte" => "Vous avez employé des caractères non autorisés dans ".$object."." );
	else $verif_string = array( "etat" => TRUE, "texte" => "" );
	return( $verif_string );
}


/* ************************* FIN DES FONCTIONS DE VERIFICATION DES DONNEES FORMULAIRES ********************************** */

/* ****************************************** FONCTIONS DE STATISTIQUES ************************************************* */
/* ***************************                                                                                     ****** */
/* ***************************       								                                               ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */

/*---------------- Retourne les valeurs tableau des statistiques -------------*/
function statistiques ($unite, $nombre_unite, $date_fin )
{
	$nombre_unite++;
	$jours_tab 		= array("", "lu.", "ma.", "me.", "je.", "ve.", "sa.", "di.");
	$mois_tab		= array("", "jan.", "fév.", "mars", "avr.", "mai", "juin", "jui.", "août", "sep.", "oct.", "nov.", "déc.");
	$stamp_date_fin = strtotime($date_fin);
	$stamp_date_fin = $stamp_date_fin - 60*60*24; // Démarre la veille car enregistrement avant minuit
	$request_string = "SELECT * FROM statistiques WHERE date_stat LIKE ";
	
	// Chercher dans la base de données, table statistiques, les valeurs des jours concernés
	switch( $unite ) {
		case "jour":
			for($i=0; $i <= $nombre_unite; $i++)
			{
				$date_a_chercher = date("Y-m-d", $stamp_date_fin -  $i*60*60*24);
				$request_string .= "'".$date_a_chercher."%'";
				if($i < $nombre_unite  ) $request_string .= " OR date_stat LIKE ";
			}
		break;
		
		case "semaine":
			$base_calcul = 60*60*24*7;
			for($i=0; $i <= $nombre_unite; $i++)
			{
				$date_a_chercher = date("Y-m-d", $stamp_date_fin -  $i*$base_calcul);
				$request_string .= "'".$date_a_chercher."%'";
				if($i < $nombre_unite ) $request_string .= " OR date_stat LIKE ";
			}
		break;
		
		case "mois":
			$base_calcul = floor(60*60*24*7*4.33);
			for($i=0; $i <= $nombre_unite; $i++)
			{
				$date_a_chercher = date("Y-m-d", $stamp_date_fin -  $i*$base_calcul);
				$request_string .= "'".$date_a_chercher."%'";
				if($i < $nombre_unite ) $request_string .= " OR date_stat LIKE ";
			}
		break;
	}
	$request_string .= " ORDER BY date_stat ASC";
	$request = mysql_query( $request_string );
	$endcount = mysql_num_rows($request);
	if ($endcount > 1) {
		while( $resultat = mysql_fetch_array($request) )
		{
			switch($unite)
			{
				case "jour":
					$date_propre[]			= $jours_tab[date("N", strtotime($resultat["date_stat"]))]." ".date("j", strtotime($resultat["date_stat"]));
					break;
				case "semaine":
					$date_propre[]			= "Sem. ".date("W", strtotime($resultat["date_stat"]));
					break;
				case "mois":
					$date_propre[]			= $mois_tab[date("n", strtotime($resultat["date_stat"]))]."-".date("y", strtotime($resultat["date_stat"]));
					break;
			}
			$date_stat[]					= $resultat["date_stat"];
			$nbre_abo_total[] 				= $resultat["nbre_abo_total"];
			$nbre_abo_actif[] 				= $resultat["nbre_abo_actif"];
			$nbre_abo_inactif[] 			= $resultat["nbre_abo_inactif"];
			$nbre_abo_suppr[] 				= $resultat["nbre_abo_suppr"];
			$nbre_inscriptions_cumulees[]	= $resultat["nbre_abo_total"] + $resultat["nbre_abo_suppr"];
			
			$nbre_li_envoyees[] 		= $resultat["nbre_li_envoyees"];		// Cumul des li envoyées
			$nbre_abo_destinataires[] 	= $resultat["nbre_abo_destinataires"]; // Destinataires uniques
			$nbre_mess_envoyes[] 		= $resultat["nbre_mess_envoyes"];      // Cumul des messages envoyés
			$nbre_li_envoyees_j[]		= $resultat["nbre_li_envoyees_j"];		
			$nbre_abo_destinataires_j[] = $resultat["nbre_abo_destinataires_j"];
			$nbre_mess_envoyes_j[] 		= $resultat["nbre_mess_envoyes_j"];
		}
		
		$tendance_abo_init = $nbre_abo_total[1];
		$tendance_abo_end  = $nbre_abo_total[$endcount-1];
		$tendance_abo_var  = ($tendance_abo_end - $tendance_abo_init) / ($endcount-1);
		
		$nbre_desinscriptions_j[0]	= "";
		$nbre_inscriptions_j[0] 	= "";
		$tendance_abo_j[0] 	= "";
		$tendance_abo_per[0]	= "";
		
		for($i=1; $i < $endcount; $i++)
		{
			$nbre_desinscriptions_j[$i]		= $nbre_abo_suppr[$i] - $nbre_abo_suppr[$i-1];
			$nbre_inscriptions_j[$i]		= $nbre_abo_total[$i] - $nbre_abo_total[$i-1] + $nbre_desinscriptions_j[$i];
			$tendance_abo_j[$i]				= $nbre_abo_total[$i] - $nbre_abo_total[$i-1];
			$tendance_abo_per[$i]			= sprintf("%+.2f", $i*$tendance_abo_var );
			$nbre_li_envoyees_j[$i]			= $nbre_li_envoyees[$i] - $nbre_li_envoyees[$i -1];
			$nbre_abo_destinataires_j[$i]	= $nbre_abo_destinataires[$i] -  $nbre_abo_destinataires[$i-1];
			$nbre_mess_envoyes_j[$i]		= $nbre_mess_envoyes[$i] - $nbre_mess_envoyes[$i-1];			
		}
		
		
		
		
		$eraser = array_shift($date_propre);
		$eraser = array_shift($date_stat);
		$eraser = array_shift($nbre_abo_total);
		$eraser = array_shift($nbre_abo_actif);
		$eraser = array_shift($nbre_abo_inactif);
		$eraser = array_shift($nbre_abo_suppr);
		$eraser = array_shift($nbre_inscriptions_cumulees);
		$eraser = array_shift($nbre_inscriptions_j);
		$eraser = array_shift($nbre_desinscriptions_j);
		$eraser = array_shift($tendance_abo_j);
		$eraser = array_shift($tendance_abo_per);
		$eraser = array_shift($nbre_li_envoyees);
		$eraser = array_shift($nbre_abo_destinataires);
		$eraser = array_shift($nbre_mess_envoyes);
		$eraser = array_shift($nbre_li_envoyees_j);
		$eraser = array_shift($nbre_abo_destinataires_j);
		$eraser = array_shift($nbre_mess_envoyes_j);
		
		return( array("etat" => TRUE, "date_propre" => $date_propre, "date_stat" => $date_stat, "nbre_abo_total" => $nbre_abo_total, "nbre_abo_actif" => $nbre_abo_actif, "nbre_abo_inactif" => $nbre_abo_inactif, "nbre_abo_suppr" => $nbre_abo_suppr, "nbre_inscriptions_cumulees" => $nbre_inscriptions_cumulees, "nbre_inscriptions_j" => $nbre_inscriptions_j, "nbre_desinscriptions_j" => $nbre_desinscriptions_j, "tendance_abo_j" => $tendance_abo_j, "tendance_abo_per" => $tendance_abo_per, "nbre_li_envoyees" => $nbre_li_envoyees, "nbre_abo_destinataires" => $nbre_abo_destinataires, "nbre_mess_envoyes" => $nbre_mess_envoyes, "nbre_li_envoyees_j" => $nbre_li_envoyees_j, "nbre_abo_destinataires_j" => $nbre_abo_destinataires_j, "nbre_mess_envoyes_j" => $nbre_mess_envoyes_j ) );
	}
	else return( array("etat" => FALSE, "texte" => "Aucun élément chiffré n'est disponible aux dates et avec les paramètres que vous avez spécifiés."));
}




/*---------------- Fonction de calcul et formatage de date de début et de fin -------------*/
// A partir d'une date de fin, d'une unité (jour, semaine, mois ou annee) et du nombre d'unité, calcul automatique de la date de début
// et retour sous forme Y-m-d H:i:s et formatée pour affichage.
function retour_date_debut_fin($unite, $nombre_unite, $date_fin)
{
	$stamp_date_fin = strtotime($date_fin);
	switch($unite)
	{
		case "jour":
			for($index = 0; $index < $nombre_unite; $index++) {
				$stamp_date_debut			=	$stamp_date_fin - ( $index * 60 * 60 * 24);
				$date[$index]["recherche"]["debut"]	=	date("Y-m-d 00:00:01", $stamp_date_debut);
				$date[$index]["format"]["debut"]	=	date("m-d", $stamp_date_debut);
				$date[$index]["recherche"]["fin"]	=	date("Y-m-d 23:59:59", $stamp_date_debut);
				$date[$index]["format"]["fin"]		=	date("m-d", $stamp_date_debut);
			}
		break;
		
		case "semaine":
			for($index = 0; $index < $nombre_unite; $index++) {
				$stamp_date_debut	=	$stamp_date_fin ;
				$temp_date_debut	= 	explode ("-", date("Y-m-d", $stamp_date_debut) );
				$temp_date_fin		= 	explode ("-", date("Y-m-d", $stamp_date_fin) );
				
				$date[$index]["format"]["debut"]	=	"Sem. ".date("W, Y", $stamp_date_debut);
				$date[$index]["format"]["fin"]		=	"Sem. ".date(" W, Y", $stamp_date_fin);
				$date[$index]["recherche"]["debut"]	=	date("Y-m-d H:i:s", mktime(0,0,1,$temp_date_debut[1], $temp_date_debut[2] - date("w", $stamp_date_debut) + 1, $temp_date_debut[0]));
				$date[$index]["recherche"]["fin"]	=	date("Y-m-d H:i:s", mktime(23,59,59,$temp_date_fin[1], $temp_date_fin[2] - date("w", $stamp_date_fin) + 7, $temp_date_fin[0]));
				
				$stamp_date_fin		= 	$stamp_date_fin - ( 60 * 60 * 24 * 7);
			}
		break;
		
		case "mois":
			for($index = 0; $index < $nombre_unite; $index++) {
				$num_mois_fin 	= date("m", $stamp_date_fin);
				$num_annee_fin	= date("Y", $stamp_date_fin);
				$nbre_joursdanslemois	=	date("t", $stamp_date_fin);
				
				$nbre_annees	= floor($nombre_unite / 12);
				$nbre_mois		= $nombre_unite % 12;
				
				$num_annee_debut= $num_annee_fin - $nbre_annees;
				if($nbre_mois >= $num_mois_fin) { $num_annee_debut--; $num_mois_debut = 13 - ($nbre_mois - $num_mois_fin);  }
				else $num_mois_debut = $num_mois_fin + 1 - $nbre_mois;
				
				$date[$index]["recherche"]["debut"]	=	date($num_annee_debut."-".$num_mois_debut."-01 00:00:01");
				$date[$index]["recherche"]["fin"]	=	date($num_annee_fin."-".$num_mois_fin."-".$nbre_joursdanslemois." 23:59:59");
				$date[$index]["format"]["debut"]	=	date($num_annee_debut."-".$num_mois_debut);
				$date[$index]["format"]["fin"]		=	date($num_annee_fin."-".$num_mois_fin);
				$stamp_date_fin		=	$stamp_date_fin - $nbre_joursdanslemois;
			}
		break;
		
		case "annee":
			for($index = 0; $index < $nombre_unite; $index++) {
				$num_annee_fin	=	date("Y", $stamp_date_fin);
				$num_annee_debut=	$num_annee_fin - ($nombre_unite - 1);
				$date["format"]["debut"]	=	$num_annee_debut;
				$date["format"]["fin"]		=	date("Y", $stamp_date_fin);
				$date["recherche"]["debut"]	=	date($num_annee_debut."-01-01 00:00:01");
				$date["recherche"]["fin"]	=	date("Y-12-31 23:59:59", $stamp_date_fin);
			}
		break;
	}
	
	return($date);
}



function calcul_date_fin($date, $max_mail, $delai)
{
	$resultat = liste_abonnes("","actif_user = '1'","dateabonnement DESC","");
	$nbre_abonnes = count($resultat);
	$nbre_envois = floor($nbre_abonnes / $max_mail);
	return( date("Y-m-d H:i", strtotime($date) + (60 * $nbre_envois * $delai) ) );
}

?>
