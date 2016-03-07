<?php
// Fichier de traitement du formulaire envoyé lettre d'information, ajout
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
//1 : Traitement des variables
// 1.1: Assignation des variables
	// Variables de l'entete de la li
	$lienfrancais   = isset($_POST["lienfrancais"])     ?   $_POST["lienfrancais"]:"";
	$lienanglais    = isset($_POST["lienanglais"])      ?   $_POST["lienanglais"]:"";
	$numero         = isset($_POST["numero"])            ?   $_POST["numero"]:"";
	$mois           = isset($_POST["mois"])              ?   $_POST["mois"]:"";
	$annee          = isset($_POST["annee"])             ?   $_POST["annee"]:"";
	$li_chapeau     = isset($_POST["li_chapeau"])        ?   $_POST["li_chapeau"]:"";
	$li_titre       = isset($_POST["li_titre"])          ?   $_POST["li_titre"]:"";
	
	// Variables de la rubrique à la une
	$alu_titre      = isset($_POST["alu_titre"])             ?   $_POST["alu_titre"]:"";
        $alu_chapeau    = isset($_POST["alu_chapeau"])          ?   $_POST["alu_chapeau"]:"";
	$alu_article    = isset($_POST["alu_article"])          ?   $_POST["alu_article"]:"";
	$alu_lien       = isset($_POST["alu_lien"])                ?   $_POST["alu_lien"]:"";
	$alu_intitule_lien = isset($_POST["alu_intitule_lien"]) ? $_POST["alu_intitule_lien"]:"";
        $alu_image_old  = isset($_POST["alu_image_old"])    ? $_POST["alu_image_old"]:"";
        $alu_suppr        = isset($_POST["alu_suppr"])    ? $_POST["alu_suppr"]:"";
	
	// Variables de la rubrique arrêt métier
	$am_titre       = isset($_POST["am_titre"])               ?   $_POST["am_titre"]:"";
	$am_chapeau     = isset($_POST["am_chapeau"])           ?   $_POST["am_chapeau"]:"";
	$am_article     = isset($_POST["am_article"])           ?   $_POST["am_article"]:"";
	$am_lien        = isset($_POST["am_lien"])                 ?   $_POST["am_lien"]:"";
	$am_intitule_lien = isset($_POST["am_intitule_lien"])   ?   $_POST["am_intitule_lien"]:"";
        $am_image_old  = isset($_POST["am_image_old"])    ? $_POST["am_image_old"]:"";
	$am_suppr        = isset($_POST["am_suppr"])    ? $_POST["am_suppr"]:"";
	
	// Variables de la rubrique zoom
	$z_titre        = isset($_POST["z_titre"])                 ?   $_POST["z_titre"]:"";
	$z_article      = isset($_POST["z_article"])             ?   $_POST["z_article"]:"";
	$z_lien         = isset($_POST["z_lien"])                   ?   $_POST["z_lien"]:"";
	$z_intitule_lien = isset($_POST["z_intitule_lien"]) ?   $_POST["z_intitule_lien"]:"";
	
	// Variables de la rubrique arrêt métier
	$no_titre       = isset($_POST["no_titre"])               ?   $_POST["no_titre"]:"";
	$no_chapeau     = isset($_POST["no_chapeau"])           ?   $_POST["no_chapeau"]:"";
	$no_article     = isset($_POST["no_article"])           ?   $_POST["no_article"]:"";
        
        // Variables de la rubrique actualités
	$act1_titre     = isset($_POST["act1_titre"])           ?   $_POST["act1_titre"]:"";
	$act1_article   = isset($_POST["act1_article"])       ?   $_POST["act1_article"]:"";
	$act1_lien      = isset($_POST["act1_lien"])             ?   $_POST["act1_lien"]:"";
	$act1_intitule_lien = isset($_POST["act1_intitule_lien"])   ?   $_POST["act1_intitule_lien"]:"";
        $act1_image_old  = isset($_POST["act1_image_old"])    ? $_POST["act1_image_old"]:"";
	$act1_suppr        = isset($_POST["act1_suppr"])    ? $_POST["act1_suppr"]:"";
	$act2_titre     = isset($_POST["act2_titre"])           ?   $_POST["act2_titre"]:"";
	$act2_article   = isset($_POST["act2_article"])       ?   $_POST["act2_article"]:"";
	$act2_lien      = isset($_POST["act2_lien"])             ?   $_POST["act2_lien"]:"";
	$act2_intitule_lien = isset($_POST["act2_intitule_lien"])   ?   $_POST["act2_intitule_lien"]:"";
        $act2_image_old  = isset($_POST["act2_image_old"])    ? $_POST["act2_image_old"]:"";
	$act2_suppr        = isset($_POST["act2_suppr"])    ? $_POST["act2_suppr"]:"";
	
        
	// Variables de la rubrique autres titres: remplacement des paragraphes ou listes préformatées par nouvelle liste préformatée
	$at_article     = isset($_POST["at_article"])           ?   "<ul class='at_article'>".str_replace("</p>", "</li>", str_replace("<p>", "<li>", str_replace("<ul>", "", str_replace("</ul>", "", $_POST["at_article"])) ) )."</ul>":"";
	
	// Variables de la rubrique mentions légales
	$ml_article     = isset($_POST["ml_article"])           ?   $_POST["ml_article"]:"";
	
	// Variables des paramètres d'envoi
	$actif_li       = (isset($_POST["actif_li"])&& $_POST["actif_li"] == 1 )  ?   $_POST["actif_li"]:0;
	$dateactivationenvoi = (isset($_POST["dateactivationenvoi"]) && $_POST["dateactivationenvoi"] <> ""  )  ?   fr_to_datetime($_POST["dateactivationenvoi"]):date("Y-m-d 00:00:01");
	
	
// 1.2: Traitement des images, cas particulier
// 2.1 : Vérification des variables numériques
	$varerror["date"] = is_valid_date( $dateactivationenvoi );

// 2.2 : Récupérer les fichiers envoyés (format images, png, gif ou jpeg)
$extensions_valides = array( "jpg", "jpeg", "gif", "png");
if(isset($_FILES["alu_image"]) && $_FILES["alu_image"]["name"] <> "")
{
	switch ($_FILES["alu_image"]["error"] )
	{
		case UPLOAD_ERR_NO_FILE:
			$alu_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à la rubrique 'A la une' a échoué.");
			break;
		case UPLOAD_ERR_INI_SIZE:
			$alu_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à la rubrique 'A la une' est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_FORM_SIZE:
			$alu_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à la rubrique 'A la une' est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_PARTIAL:
			$alu_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à la rubrique 'A la une' a échoué.");
			break;
		default: 
			$alu_imgerror[]  = array( "etat" => TRUE, "texte" => "");
			$extension_alu_image = strtolower(  substr(  strrchr($_FILES['alu_image']['name'], '.')  ,1)  );
                        if( !in_array($extension_alu_image,$extensions_valides) ) { $alu_imgerror[] = array( "etat" => FALSE, "texte" => "L'extension de l'image associée à la rubrique 'A la une' n'est pas autorisée."); }

	}
	$alu_countimgerrors = 0;
        foreach($alu_imgerror as $key => $value) if($value["etat"] == FALSE) { $alu_countimgerrors++; }
	if($alu_countimgerrors == 0) {
		$alu_image_name = md5(uniqid(rand(), true)).".".$extension_alu_image;
		$alu_resultat = move_uploaded_file($_FILES["alu_image"]["tmp_name"], "../".$config["images"].$alu_image_name);
	}
        else { $alu_image_name=$alu_image_old; }
if (!isset($alu_resultat) || $alu_resultat == FALSE) {$alu_imgerror[] = array( "etat" => FALSE, "texte" => "L'image associée à la rubrique 'A la une' n'a pu être transférée.");}
}
else {
	$alu_imgerror[] = array( "etat" => TRUE, "texte" => "");
	$alu_image_name=$alu_image_old;
}
// Suppression de l'ancienne image si demandé
if($_FILES["alu_image"]["name"] == "" && $alu_suppr <> "")
{
	$alu_image_name="";
        if(!unlink($alu_suppr)) {$alu_imgerror[] = array( "etat" => FALSE, "texte" => "La suppression sur le serveur de l'image associée à la rubrique 'A la une' a échoué. Elle a cependant été supprimée de la base de données.");}
}

if(isset($_FILES["am_image"]) && $_FILES["am_image"]["name"] <> "")
{
	switch ($_FILES["am_image"]["error"] ) 
	{
		case UPLOAD_ERR_NO_FILE:
			$am_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à la rubrique 'Arrêt métier' a échoué.");
			break;
		case UPLOAD_ERR_INI_SIZE:
			$am_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à la rubrique 'Arrêt métier' est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_FORM_SIZE:
			$am_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à la rubrique 'Arrêt métier' est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_PARTIAL:
			$am_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à la rubrique 'Arrêt métier' a échoué.");
			break;
		default: 
			$am_imgerror[]  = array( "etat" => TRUE, "texte" => "");
			$extension_am_image = strtolower(  substr(  strrchr($_FILES['am_image']['name'], '.')  ,1)  );
                        if( !in_array($extension_am_image,$extensions_valides) ) {$am_imgerror[] = array( "etat" => FALSE, "texte" => "L'extension de l'image associée à la rubrique 'Arrêt métier' n'est pas autorisée.");}
	}
	$am_countimgerrors = 0;
        foreach($am_imgerror as $key => $value) {if($value["etat"] == FALSE) {$am_countimgerrors++;} }
	if($am_countimgerrors == 0) {	
		$am_image_name = md5(uniqid(rand(), true)).".".$extension_am_image;
		$am_resultat = move_uploaded_file($_FILES["am_image"]["tmp_name"], "../".$config["images"].$am_image_name);
	}
        else { $am_image_name=$am_image_old;}

if (!isset($am_resultat) || $am_resultat == FALSE) {$am_imgerror[] = array( "etat" => FALSE, "texte" => "L'image associée à la rubrique 'Arrêt métier' n'a pu être transférée.");}
}
else { 
	$am_imgerror[] = array( "etat" => TRUE, "texte" => "");
	$am_image_name=$am_image_old;
}
// Suppression de l'ancienne image si demandé
if($_FILES["am_image"]["name"]=="" && $am_suppr <> "")
{
	$am_image_name = "";
        if(!unlink($am_suppr)) {$am_imgerror[] = array( "etat" => FALSE, "texte" => "La suppression sur le serveur de l'image associée à la rubrique 'Arrêt métier' a échoué. Elle a cependant été supprimée de la base de données.");}
}

if(isset($_FILES["act1_image"]) && $_FILES["act1_image"]["name"] <> "")
{
	switch ($_FILES["act1_image"]["error"] )
	{
		case UPLOAD_ERR_NO_FILE:
			$act1_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à l'actualité 1 a échoué.");
			break;
		case UPLOAD_ERR_INI_SIZE:
			$act1_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à l'actualité 1 est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_FORM_SIZE:
			$act1_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à l'actualité 1 est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_PARTIAL:
			$act1_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à l'actualité 1 a échoué.");
			break;
		default: 
			$act1_imgerror[]  = array( "etat" => TRUE, "texte" => "");
			$extension_act1_image = strtolower(  substr(  strrchr($_FILES['act1_image']['name'], '.')  ,1)  );
                        if( !in_array($extension_act1_image,$extensions_valides) ) { $act1_imgerror[] = array( "etat" => FALSE, "texte" => "L'extension de l'image associée à l'actualité 1 n'est pas autorisée."); }

	}
	$act1_countimgerrors = 0;
        foreach($act1_imgerror as $key => $value) if($value["etat"] == FALSE) { $act1_countimgerrors++; }
	if($act1_countimgerrors == 0) {
		$act1_image_name = md5(uniqid(rand(), true)).".".$extension_act1_image;
		$act1_resultat = move_uploaded_file($_FILES["act1_image"]["tmp_name"], "../".$config["images"].$act1_image_name);
	}
        else { $act1_image_name=$act1_image_old; }
if (!isset($act1_resultat) || $act1_resultat == FALSE) {$act1_imgerror[] = array( "etat" => FALSE, "texte" => "L'image associée à l'actualité 1 n'a pu être transférée.");}
}
else {
	$act1_imgerror[] = array( "etat" => TRUE, "texte" => "");
	$act1_image_name=$act1_image_old;
}
// Suppression de l'ancienne image si demandé
if($_FILES["act1_image"]["name"] == "" && $act1_suppr <> "")
{
	$act1_image_name="";
        if(!unlink($act1_suppr)) {$act1_imgerror[] = array( "etat" => FALSE, "texte" => "La suppression sur le serveur de l'image associée à l'actualité 1 a échoué. Elle a cependant été supprimée de la base de données.");}
}

if(isset($_FILES["act2_image"]) && $_FILES["act2_image"]["name"] <> "")
{
	switch ($_FILES["act2_image"]["error"] )
	{
		case UPLOAD_ERR_NO_FILE:
			$act2_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à l'actualité 2 a échoué.");
			break;
		case UPLOAD_ERR_INI_SIZE:
			$act2_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à l'actualité 2 est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_FORM_SIZE:
			$act2_imgerror[] = array( "etat" => FALSE, "texte" => "La taille de l'image associée à l'actualité 2 est trop importante. L'image n'a pas été chargée.");
			break;
		case UPLOAD_ERR_PARTIAL:
			$act2_imgerror[] = array( "etat" => FALSE, "texte" => "Le transfert de l'image associée à l'actualité 2 a échoué.");
			break;
		default: 
			$act2_imgerror[]  = array( "etat" => TRUE, "texte" => "");
			$extension_act2_image = strtolower(  substr(  strrchr($_FILES['act2_image']['name'], '.')  ,1)  );
                        if( !in_array($extension_act2_image,$extensions_valides) ) { $act2_imgerror[] = array( "etat" => FALSE, "texte" => "L'extension de l'image associée à l'actualité 2 n'est pas autorisée."); }

	}
	$act2_countimgerrors = 0;
        foreach($act2_imgerror as $key => $value) if($value["etat"] == FALSE) { $act2_countimgerrors++; }
	if($act2_countimgerrors == 0) {
		$act2_image_name = md5(uniqid(rand(), true)).".".$extension_act2_image;
		$act2_resultat = move_uploaded_file($_FILES["act2_image"]["tmp_name"], "../".$config["images"].$act2_image_name);
	}
        else { $act2_image_name=$act2_image_old; }
if (!isset($act2_resultat) || $act2_resultat == FALSE) {$act2_imgerror[] = array( "etat" => FALSE, "texte" => "L'image associée à l'actualité 2 n'a pu être transférée.");}
}
else {
	$act2_imgerror[] = array( "etat" => TRUE, "texte" => "");
	$act2_image_name=$act2_image_old;
}
// Suppression de l'ancienne image si demandé
if($_FILES["act2_image"]["name"] == "" && $act2_suppr <> "")
{
	$act2_image_name="";
        if(!unlink($act2_suppr)) {$act2_imgerror[] = array( "etat" => FALSE, "texte" => "La suppression sur le serveur de l'image associée à l'actualité 2 a échoué. Elle a cependant été supprimée de la base de données.");}
}

// 3: COnstruction du tableau des variables à transmettre
$datatoserialize = array(
	"image_path"		=> $config["images"],
	"mois"				=> $mois,
	"annee"				=> $annee,
	"li_chapeau"		=> $li_chapeau,
	"alu_titre"			=> $alu_titre,
	"alu_chapeau"		=> $alu_chapeau,
	"alu_article"		=> $alu_article,
	"alu_lien"			=> $alu_lien,
	"alu_intitule_lien"	=> $alu_intitule_lien,
	"alu_image"			=> $alu_image_name,
	"am_titre"			=> $am_titre,
	"am_chapeau"		=> $am_chapeau,
	"am_article"		=> $am_article,
	"am_lien"			=> $am_lien,
	"am_intitule_lien"	=> $am_intitule_lien,
	"z_titre"			=> $z_titre,
	"z_article"			=> $z_article,
	"z_lien"			=> $z_lien,
	"z_intitule_lien"	=> $z_intitule_lien,
	"am_image"			=> $am_image_name,
        "no_titre"			=> $no_titre,
	"no_chapeau"		=> $no_chapeau,
	"no_article"		=> $no_article,
	"act1_titre"			=> $act1_titre,
	"act1_article"			=> $act1_article,
	"act1_lien"			=> $act1_lien,
	"act1_intitule_lien"	=> $act1_intitule_lien,
        "act1_image"            => $act1_image_name,
        "act2_titre"			=> $act2_titre,
	"act2_article"			=> $act2_article,
	"act2_lien"			=> $act2_lien,
	"act2_intitule_lien"	=> $act2_intitule_lien,
        "act2_image"            => $act2_image_name,
	"at_article"		=> $at_article,
	"ml_article"		=> $ml_article	
);

// Enregistrement dans la base de données
if(isset($varerror["date"]["etat"]) && $varerror["date"]["etat"] <> FALSE)
{
$data[0] = array ( "uniq_id" => $uniq_id, "numero" =>  bdd_prepare($numero), "li_titre" => bdd_prepare($li_titre), "actif_li" => $actif_li, "dateactivationenvoi" => bdd_prepare($dateactivationenvoi), "lienpdffr" => bdd_prepare($lienfrancais), "lienpdfen" => bdd_prepare($lienanglais), "dataseries" => mysql_real_escape_string(serialize($datatoserialize)) );

// Si aucune erreur sur les variables
$bdderror["bdd"] = modification_li( $data );
}
else
{
$data[0] = array ( "uniq_id" => $uniq_id, "numero" =>  bdd_prepare($numero), "li_titre" => bdd_prepare($li_titre), "actif_li" => $actif_li, "dateactivationenvoi" => "0000-00-00 00:00:00", "lienpdffr" => bdd_prepare($lienfrancais), "lienpdfen" => bdd_prepare($lienanglais), "dataseries" => mysql_real_escape_string(serialize($datatoserialize)) );

// Si aucune erreur sur les variables
$bdderror["bdd"] = modification_li( $data );	
}
?>