<?php 
/* --------------------------------------------------------------------------------- */
/* 							Script d'envoi du mailing 								 */
/* --------------------------------------------------------------------------------- */
// Connexion base
	require("./ressources/config.req.php");
	require("./ressources/bdconnect.req.php");
	require("./ressources/fonctions.connexion.req.php");
	include_once('./ressources/class.phpmailer.php');
	
// 1:  Déterminer quelle est la lettre d'information à envoyer
$log_file = fopen("journalenvoi.txt", "a+");
fputs($log_file, date("Y-m-d H:i:s")."Essai envoi\n");
 
$request_string_li = "SELECT id_li, uniq_id, li_titre, numero, datepremierenvoi, datedernierenvoi, actif_li FROM lettredinformation WHERE actif_li > 0 AND dateactivationenvoi < '".date("Y-m-d H:i:s")."' ORDER BY dateactivationenvoi ASC LIMIT 1";
$request = mysql_query($request_string_li);
$nbre_resultat = mysql_num_rows($request);

if ($nbre_resultat == 0) {
	echo "Aucune lettre d'information à envoyer";
        //fputs($log_file, date("Y-m-d H:i:s")."Stade 1: Aucune lettre à envoyer\n");
}
else {
        
        $resultat = mysql_fetch_array($request);
        $id_li = $resultat["id_li"];
	$uniq_id_li = utf8_encode($resultat["uniq_id"]);
	$li_titre = $resultat["li_titre"];
	$actif_li = utf8_encode($resultat["actif_li"]);
	$datepremierenvoi = utf8_encode($resultat["datepremierenvoi"]);
	$datedernierenvoi = utf8_encode($resultat["datedernierenvoi"]);
	$numero = $resultat["numero"];
        fputs($log_file, date("Y-m-d H:i:s")."Stade 2: Traitement LI: $uniq_id_li - N°: $numero ------------------------------ \n");
	
	// 2: Déterminer les prochains destinataires
	$request_string_users = 	"SELECT A.uniq_id, A.mail FROM abonnes A WHERE A.actif_user = 1 AND A.uniq_id NOT IN (SELECT DISTINCT E.uniq_id_user FROM envois E WHERE E.uniq_id_li = '".$uniq_id_li."' AND E.statut = 'ok' ) ORDER BY A.dateabonnement ASC LIMIT ".$config["max_mail_periode"];
					
	$request_users = mysql_query($request_string_users);
        
        $nbre_abonnes_envoi = mysql_num_rows($request_users);
	
	if($nbre_abonnes_envoi > 0) {
                fputs($log_file, date("Y-m-d H:i:s")."Stade 3: Nbre abo: $nbre_abonnes_envoi\n");
		$mail             = new PHPMailer();
		$mail->IsSendmail();
		$mail->CharSet = "UTF-8";
		$mail->Subject = "N°".$numero." : ".utf8_encode(html_entity_decode(stripslashes($li_titre) ) );
		$mail->From       = "noreply@finances-pedagogie.fr";
		$mail->FromName   = "Noreply - Paroles d'argent";
		
		
		// 3: Préparation du mail
		$body             = $mail->getFile($config["li_html"].$uniq_id_li."-part1.html");
		

    // Envoi des mails à chacun des abonnés
		while($resultat_users = mysql_fetch_array($request_users))
		{
			
                        $mail->AddAddress(utf8_encode($resultat_users["mail"]), "Undisclosed Recipients");
			$uniq_id_u = $uniq_id_user[] = utf8_encode($resultat_users["uniq_id"]);
			$body             = $mail->getFile($config["li_html"].$uniq_id_li."-part1.html");
			$body			 .= "?uniq_id=".$uniq_id_u."&mail=".utf8_encode($resultat_users["mail"]);
			$body			 .= $mail->getFile($config["li_html"].$uniq_id_li."-part2.html");
			$mail->MsgHTML($body);
			$send_ok[$uniq_id_u]["sent"]=$mail->Send();
			$send_ok[$uniq_id_u]["error"]= $mail->ErrorInfo;
			$mail->ClearAddresses();
                        //fputs($log_file, date("Y-m-d H:i:s")."Stade 4: Envoi mail - User: $uniq_id_u / Mail: ".utf8_encode($resultat_users["mail"])." \n");
			
		}
	// Fin d'envoi des mails
	
	// Traitement des erreurs dans l'envoi des mails	
		foreach($uniq_id_user as $key => $value) {
				if($send_ok[$value]["sent"] <> FALSE)
				{
                                    //fputs($log_file, date("Y-m-d H:i:s")."Stade 5: Envoi ok\n");
                                    $date_print = date("Y-m-d H:i:s");
					$request_string = "INSERT INTO envois VALUES('', '".$value."', '".$uniq_id_li."', '".$date_print."', 'ok' )";
                                        $request_log = mysql_query($request_string);
					$request_string_user_lastli = "UPDATE abonnes SET lastnewsletter = '".$li_titre."', lastsent = '".$date_print."' WHERE uniq_id = '".$value."'";
					$request_user_lastli = mysql_query($request_string_user_lastli);
				   	if($request_log === FALSE) {
					   fputs($log_file, date("Y-m-d H:i:s")."user: ".$value."/ li:".$uniq_id_li.": échec log dans la base\n");
				   	}
				}
				else
				{
					fputs($log_file, date("Y-m-d H:i:s")."Stade 6: Envoi échec - User: $value\n");
                                        $request_string = "INSERT INTO envois VALUES('', '".$value."', '".$uniq_id_li."', '".date("Y-m-d H:i:s")."', '".$send_ok[$value]["error"]."' )";
				 	$request_log = mysql_query($request_string);
				  	if($request_log == FALSE) {
					   fputs($log_file, date("Y-m-d H:i:s").$value.":".$uniq_id_li.": échec log dans la base :".$mail->ErrorInfo."\n");
				   	}
				}
		}
		
		
	// Fin de traitement des erreurs dans l'envoi des mails
	
	// Traitement du premier envoi
		if($actif_li == 1) {
                        fputs($log_file, date("Y-m-d H:i:s")."Stade 7: 1er envoi\n");
			$request_string_premier_envoi = "UPDATE lettredinformation SET datepremierenvoi = '".date("Y-m-d H:i:s")."', actif_li = '2' WHERE uniq_id= '".$uniq_id_li."' LIMIT 1";
			$request_premier_envoi = mysql_query($request_string_premier_envoi);
		}
	// Traitement de la fin des envois, hypothèse 1: le nombre d'abonnés renvoyés est inférieur au nbre max par envoi
		if( ($nbre_abonnes_envoi < $config["max_mail_periode"]) && ($datedernierenvoi == "0000-00-00 00:00:00") ){
			fputs($log_file, date("Y-m-d H:i:s")."Stade 8: Fin des envois: hypothèse 1 - Renseignement de la base et suppression du log des envois\n");
                        $request_string_dernier_envoi = "UPDATE lettredinformation SET datedernierenvoi = '".date("Y-m-d H:i:s")."', actif_li = '-1' WHERE uniq_id= '".$uniq_id_li."' LIMIT 1";
			$request_dernier_envoi = mysql_query($request_string_dernier_envoi);
		}
	}
	else
	{
		fputs($log_file, date("Y-m-d H:i:s")."Stade 9: Pas d'abo à envoyer\n");
                // Hypothèse n°2 de fin d'envoi: aucun abonné à qui envoyer la lettre, alors vérification que le dernier envoi a été renseigné, sinon renseignement
		if($datedernierenvoi == "0000-00-00 00:00:00" && $request_users <> FALSE) {
			fputs($log_file, date("Y-m-d H:i:s")."Stade 10: Fin des envois: hypothèse 2 - Renseignement de la base et suppression du log des envois\n");
                        $request_string_dernier_envoi = "UPDATE lettredinformation SET datedernierenvoi = '".date("Y-m-d H:i:s")."', actif_li = '-1' WHERE uniq_id= '".$uniq_id_li."' LIMIT 1";
			$request_dernier_envoi = mysql_query($request_string_dernier_envoi);
                }
                else {
                    fputs($log_file, date("Y-m-d H:i:s")."Stade 11: Erreur request_users\n");
                    
                    // Erreur car fichier trop important --> dump de la base, archivage des news et vidage des envois terminés

                    $request_string_li_sup = "SELECT id_li, uniq_id FROM lettredinformation WHERE actif_li = '-1' ORDER BY id_li ASC";
                    $request_sup = mysql_query($request_string_li_sup);
                    $nbre_resultat_sup = mysql_num_rows($request);
                    if($nbre_resultat_sup>1) {
                        while($resultat_sup = mysql_fetch_array($request_sup)) {
                            $uniq_id_table_to_del[] = $resultat_sup['uniq_id'];
                        }
                    }
                    array_pop($uniq_id_table_to_del); // on garde le dernier enregistré
                    
                    // Archivage des lettres d'information et suppression des logs
                    foreach($uniq_id_table_to_del as $uniq_id_del)
                    {
                        $request_string_archive = "UPDATE lettredinformation  SET actif_li = '-2' WHERE uniq_id = '".$uniq_id_del."'";
                        print_r($request_string_archive.'<br>');
                        $request_archive = mysql_query($request_string_archive);
                        
                        $request_string_del_envois ="DELETE FROM envois WHERE uniq_id_li = '".$uniq_id_del."'";
                        print_r($request_string_del_envois.'<br>');
                        $request_del_envois = mysql_query($request_string_del_envois);
                    }
                }
	}
}
fclose($log_file);
?>
<?php require("./ressources/bddisconnect.req.php");