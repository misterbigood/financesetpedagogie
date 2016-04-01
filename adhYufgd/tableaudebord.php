<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);

include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title><?php echo $config["html-title"]?>Tableau de bord</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/visualize.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
<script  type="text/javascript" src="libraries/jquery.min.js"></script>
<script  type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script  type="text/javascript" src="libraries/visualize.jQuery.js"></script>
<script type="text/javascript" src="libraries/jquery.dataTables.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	 	$( "#organisable" ).sortable();
		$( "#organisable" ).disableSelection();
		$( "#Tabos").dataTable({
		"aaSorting": [[ 1, "desc" ]],
		"sDom": 't'
		});
		$( "#TlastLI").dataTable({
		"aaSorting": [[ 2, "desc" ]],
		"sDom": 't'
		});
		
		$("#Tstats_abo").visualize({ type: 'line', lineWeight: 2, appendTitle: false, height: 300 });
		$("#Tstats_li").visualize({ type: 'line', lineWeight: 2, appendTitle: false, height: 300 });
 });
</script>
</head>
<body>
<div role="navigation">
	<div id="header">
    	
    </div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item selection"><span><a href="tableaudebord.php" title="Tableau de bord">Tableau de bord</a></span></li>
            <li class="item"><span><a href="lettredinformation.php" title="Gestion des lettres d'information">Lettre d'information</a></span></li>
            <li class="item"><span><a href="abonnes.php" title="Gestion des abonnés">Abonnés</a></span></li>
            <?php if (isset($_SESSION["droit"]) && $_SESSION["droit"] == 1) { ?>
            <li class="item"><span><a href="utilisateurs.php" title="Gestion des utilisateurs">Utilisateurs</a></span></li>
            <li class="item"><span><a href="outils.php" title="Outils, maintenance">Outils</a></span></li>
            <?php } ?>
            <li class="quit-item"><span><a href="deconnexion.php" title="Déconnexion de l'espace d'administration"><img src="medias/icones/exit.png" width="48" alt="Déconnexion de l'espace d'administration" /></a></span></li>
        </ul>
    </div>
</div>
<div id="main">
    <div id="contenu">
    <div class="tableaudebord">
    		<p class="titre">Bienvenue sur votre tableau de bord</p>
            <p>Bonjour <?php echo ucwords($_SESSION["prenom"])." ".strtoupper($_SESSION["nom"]);?>.</p>
            <p>Si vous n'êtes pas <?php echo ucwords($_SESSION["prenom"]) ?>, merci de vous déconnecter.</p>
            <p>Dernière connexion: <?php echo formatdateheure($_SESSION["lastconnexion"]);?> </p>
    </div>	
    <div id="organisable">
     <div id="org_lettredinformation" class="tableaudebord">
           <p class="titre">Dernières lettres d'information</p>
            <table id="TlastLI" class="tablesorter" summary="Liste des utilisateurs de l'interface newsletter">
      	 <thead>
       		<tr>
       			<th>Numero</th>
                <th>Date pub.</th>
                <th>Statut</th>
                <th>Début envoi</th>
                <th>Fin prévue</th>
                <th>Fin envoi</th>
       		</tr>
      	 </thead>
         <tbody>
         <?php 
			// Récupération des données
			$tableau_classe = liste_li("","","datecreation DESC","5");
			 foreach($tableau_classe as $key => $value)
			 {
				 $data_unseries = unserialize($value["dataseries"]);
			 ?>
				<tr>
					<td><?php echo $value["numero"]; ?></td>
                    <td><?php echo $data_unseries["mois"]." ".$data_unseries["annee"]; ?></td>
					<td><?php echo utf8_encode($config["statut_li"][$value["actif_li"]]); ?></td>
                                        <td><?php if($value["datepremierenvoi"] <> "0000-00-00 00:00:00") {echo $value["datepremierenvoi"];} else {echo "Envoi non débuté";}?></td>
                    <td><?php if($value["actif_li"] == 2 || $value["actif_li"] < 0) {echo calcul_date_fin($value["uniq_id"], $value["datepremierenvoi"], $config["max_mail_periode"],  $config["delai_periode_cron"]);} else {echo "Envoi non débuté";} ?></td>
                    <td><?php if($value["datedernierenvoi"] <> "0000-00-00 00:00:00") {echo $value["datedernierenvoi"];} else {echo "Envoi non terminé";} ?></td>
				</tr>
                
			 <?php
			 }?>
         </tbody>
       </table>
    </div>
    <div id="org_stats" class="tableaudebord">
          <p class="titre">Statistiques</p>
          <?php 
			// Récupération des données
			$retour_stat = statistiques ("jour", 7, date("Y-m-d H:i:s"));
			 if ($retour_stat["etat"] == TRUE) {?>
            <table id="Tstats_abo" class="tablesorter stats" summary="Statistique des abonnements et désabonnements">
            <caption>Evolution des inscriptions, désinscriptions et nombre d'abonnés</caption>
      	 <thead>
       		<tr>
       			<td></td>
                <?php foreach($retour_stat["date_propre"] as $key => $value) {echo "<th scope='col'>".$value."</th>";} ?>
       		</tr>
      	 </thead>
         <tbody>
         	<tr>
            	<th scope="row">Inscriptions cumulées</th>
                <?php foreach($retour_stat["nbre_inscriptions_cumulees"] as $key => $value) {echo "<td>".$value."</td>";} ?>
            </tr>
            <tr>
            	<th scope="row">Abonnés total</th>
                <?php foreach($retour_stat["nbre_abo_total"] as $key => $value) {echo "<td>".$value."</td>";} ?>
            </tr>
            <tr>
            	<th scope="row">Abonnés supprimés</th>
                <?php foreach($retour_stat["nbre_abo_suppr"] as $key => $value) {echo "<td>".$value."</td>";} ?>
            </tr>
          </tbody>
       </table>
       <?php } else { echo "<p class='erreur'>".$retour_stat["texte"]."</p>"; } ?>
       <p>Vous pouvez éditer une statistique en sélectionnant le type désiré (sur les abonnés ou les envois de lettre d'information), une durée et la date de fin. La statistique éditée couvrira la durée choisie jusqu'à la date de fin spécifiée.</p>
                <form name="formulaire" method="POST" action="statistiquestb.php" enctype="multipart/form-data" class="formulaire" id="formulaire">
                	<label for="stat_type">Sélectionnez le type de statistique :</label>
                    <select name="stat_type">
                    	<option value="abo" selected="selected">Statistiques abonnés</option>
                        <option value="li">Statistiques lettres d'information</option>
                    </select>
                    <label for="stat_duree">Sélectionnez la durée de la statistique :</label>
                    <select name="stat_duree">
                    	<option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6" selected="selected">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                    <select name="stat_unite">
                    	<option value="jour">Jours</option>
                        <option value="semaine" selected="selected">Semaines</option>
                        <option value="mois">Mois</option>
                    </select>
                    <label for="stat_date_fin">Sélectionnez la date de fin :</label>
                    <span>(Par défaut, la statistique sera calculée jusqu'à aujourd'hui)</span>
                    <input name="stat_date_fin" id="stat_date_fin" value="<?php echo date("d/m/Y"); ?>">
                    <input type="hidden" name="form_send_stat" value="1" />
                    <input type="submit" name="editer" value="Editer" />
                </form>
      </div>
      <div id="org_inscriptions" class="tableaudebord">
            <p class="titre">Dernières inscriptions</p>
            <table id="Tabos" class="tablesorter" summary="Liste des utilisateurs de l'interface newsletter">
      	 <thead>
       		<tr>
       			<th>Mail</th>
                <th>Inscription</th>
                <th>Actif</th>
                <th>Dernière LI</th>
                <th>Envoyée le</th>
       		</tr>
      	 </thead>
         <tbody>
         <?php
		 $tableau_classe_abo = liste_abonnes("","actif_user <> '-1'","dateabonnement DESC",10,"");
			 foreach($tableau_classe_abo as $key => $value)
			 {
			 ?>
				<tr>
					<td><?php echo $value["mail"]; ?></td>
					<td><?php echo $value["dateabonnement"]; ?></td>
					<td class="hiddentext">
					<?php switch($value["actif_user"]) {
						case 0:  ?><img src="medias/icones/abo-dea.png" />1<?php ; break;
						case 1:  ?><img src="medias/icones/abo-act.png" />0<?php ; break;
						case -1: ?><img src="medias/icones/abo-supr.png" />0<?php ; break;
					} ?></td>
					<td><?php echo $value["lastnewsletter"]; ?></td>
                    <td><?php echo $value["lastsent"]; ?></td>
				</tr>
			 <?php
			 }?>
         </tbody>
       </table>
    </div>
      </div>
      <div id="backtotheflux"></div>	
    </div>
</div>

    <div id="footer">
    <?php include("libraries/html-php/footer.inc.php"); ?>
    </div>
</body>
</html>