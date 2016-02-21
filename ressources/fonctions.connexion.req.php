<?php
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
/*---------------- Fonction d'ajout des abonnés -------------*/
function ajout_abonnes( $data )
{
	// Déclaration des variables
	
	
	//Requête
	foreach($data as $key => $value)
	{
		$dateabonnement = date("Y-m-d H:i:s");
		$uniq_id = md5($value["mail"].$dateabonnement.rand());
		$request_string = "INSERT IGNORE INTO abonnes VALUES ('', '".bdd_prepare($uniq_id)."', '".bdd_prepare(strtolower($value["mail"]))."', '0', '0000-00-00 00:00:00', '".$dateabonnement."', '0000-00-00 00:00:00', '".$value["actif_user"]."' ); ";
	}
	$request 	= mysql_query(	$request_string );
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête vers la base de données n'a pas abouti.<br />Erreur : ".mysql_errno()." : ".mysql_error()."." ); }
	else { 
	// Si aucune erreur dans le traitement, l'abonné est enregistré
	$resultatrequete = array("etat" => TRUE, "texte" => "" ); }
	$resultatrequete["uniq_id"] = $uniq_id;
	// Traitement de la requête
	return( $resultatrequete );
}
/* ------------------------Fin de fonction ---------------------------*/
/*---------------- Fonction de connexion des utilisateurs -------------*/
function verification_connexion( $data )
{
	//Requête
	$request_string = "SELECT * FROM utilisateurs WHERE login = '".bdd_prepare($data["login"])."' AND password = '".bdd_prepare($data["password"])."' LIMIT 1";
	
	$request 	= mysql_query(	$request_string );
	
	if($request == FALSE) { 
	// Si une erreur s'est produite dans le traitement de la requête
	$resultatrequete = array("etat" => FALSE, "texte" => "La requête n'a pu aboutir. Erreur." );
	}
	else { 
		// Si aucune erreur dans le traitement, récupération des informations utilisateur
		while($resultat=mysql_fetch_array($request))
		{
			$uniq_id		=	utf8_encode($resultat["uniq_id"]);
			$nom			=	utf8_encode($resultat["nom"]);
			$prenom			=	utf8_encode($resultat["prenom"]);
			$lastconnexion	=	utf8_encode($resultat["lastconnexion"]);
			$actif			=	utf8_encode($resultat["actif"]);
			$droit			=	utf8_encode($resultat["droit"]);
		}
		if(isset($actif))
		{
			if($actif == 1)
			{ // Le compte est actif, on enregistre les variables de session
				$_SESSION["CONNEXION_VALIDE"] = 1;
				$_SESSION["current_uniq_id"] = $uniq_id;
				$_SESSION["nom"] = $nom;
				$_SESSION["prenom"] = $prenom;
				$_SESSION["lastconnexion"] = $lastconnexion;
				$_SESSION["droit"] = $droit;
				
				// Enregistrement de la nouvelle date de connexion et enregistrement  du log
				$new_lastconnexion	=	date("Y-m-d H:i:s");
				$request_string	=	"UPDATE utilisateurs SET lastconnexion = '".bdd_prepare($new_lastconnexion)."' WHERE uniq_id = '".bdd_prepare($uniq_id)."'";
				$request = mysql_query($request_string);
				$request_log_string = "INSERT INTO connexionlog VALUES ('', '".bdd_prepare($uniq_id)."', '".bdd_prepare($new_lastconnexion)."', '".bdd_prepare($_SERVER["REMOTE_ADDR"])."', 1, '".bdd_prepare("Connexion réussie - Utilisateur actif")."' )";
								
				$resultatrequete = array("etat" =>TRUE, "texte" => "" );
			}
			else
			{ // Le compte n'est pas actif, message d'erreur
				$resultatrequete = array("etat" => FALSE, "texte" => "Le compte auquel vous tentez de vous connecter n'est pas actif." );
				$request_log_string	=	"INSERT INTO connexionlog VALUES ('', '".bdd_prepare($uniq_id)."', '".bdd_prepare(date("Y-m-d H:i:s"))."', '".bdd_prepare($_SERVER["REMOTE_ADDR"])."', 0, '".bdd_prepare("Connexion échouée - Utilisateur inactif")."' )";
			}
		}
		else 
		{
			$resultatrequete = array("etat" => FALSE, "texte" => "Votre identifiant et/ou votre mot de passe n'est pas enregistré." );
			$request_log_string	=	"INSERT INTO connexionlog VALUES ('', '0', '".bdd_prepare(date("Y-m-d H:i:s"))."', '".bdd_prepare($_SERVER["REMOTE_ADDR"])."', -9, '".bdd_prepare("Connexion échouée - Couple login-password inexistant")."' )";
		}
	}
		// Enregistrement de la journalisation des connexions à l'interface d'administration
		$request = mysql_query( $request_log_string );
		
		//Retour des données d'erreur
		return($resultatrequete);
}
/* ------------------------Fin de fonction ---------------------------*/


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

/* ------------------------ Fonction de préparation des données pour la bdd -----------------------*/
function bdd_prepare ($string) {
	return (mysql_real_escape_string(htmlentities($string, ENT_COMPAT, 'UTF-8')));
}
/* *********************** FIN FONCTIONS DE PREPARATION ET DE TRAITEMENT DES DONNEES POUR INSERTION OU LECTURE BDD ****** */


/* ********************************* FONCTIONS DEVERIFICATION DES DONNEES FORMULAIRES *********************************** */
/* ***************************                                                                                     ****** */
/* ***************************      Fonction de vérification des formats de mail, dates, etc.                      ****** */
/* ***************************                                                                                     ****** */
/* ********************************************************************************************************************** */

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

/* ************************* FIN DES FONCTIONS DE VERIFICATION DES DONNEES FORMULAIRES ********************************** */


?>
