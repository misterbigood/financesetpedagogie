<?php
// Vérification de l'accès:
session_start();
ob_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
(isset($_SESSION["droit"]) && ($_SESSION["droit"] == 1) ) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");	

// Traitement du formulaire envoyé ou non
if( isset($_POST["form_send_stat"]) && $_POST["form_send_stat"] == 1 )
{
	$stat_type		= $_POST["stat_type"];
	$stat_unite		= $_POST["stat_unite"];
	$stat_duree		= $_POST["stat_duree"];
	$stat_date_fin	= fr_to_datetime($_POST["stat_date_fin"]);
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title><?php echo $config["html-title"]?>Statistiques</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/visualize.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.8.css" rel="stylesheet" type="text/css" />
<script  type="text/javascript" src="libraries/jquery.min.js"></script>
<script  type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script  type="text/javascript" src="libraries/visualize.jQuery.js"></script>
<script type="text/javascript" src="libraries/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="libraries/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	 	$(".stats").dataTable();
		$(".stats").visualize({ type: 'line', lineWeight: 2, appendTitle: false, height: 300 });
		$( "#stat_date_fin" ).datepicker();
});
</script>
</head>
<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item"><span><a href="tableaudebord.php" title="Tableau de bord">Tableau de bord</a></span></li>
            <li class="item"><span><a href="lettredinformation.php" title="Gestion des lettres d'information">Lettre d'information</a></span></li>
            <li class="item"><span><a href="abonnes.php" title="Gestion des abonnés">Abonnés</a></span></li>
            <?php if (isset($_SESSION["droit"]) && $_SESSION["droit"] == 1) { ?>
            <li class="item"><span><a href="utilisateurs.php" title="Gestion des utilisateurs">Utilisateurs</a></span></li>
            <li class="item selection"><span><a href="outils.php" title="Outils, maintenance">Outils</a></span></li>
            <?php } ?>
            <li class="quit-item"><span><a href="deconnexion.php" title="Déconnexion de l'espace d'administration"><img src="medias/icones/exit.png" width="48" alt="Déconnexion de l'espace d'administration" /></a></span></li>
        </ul>
    </div>
</div>
<div id="main">
    <div id="contenu">
      
      <div id="org_stats" class="tableaudebord">
          <p class="titre">Statistiques</p>
          <?php 
		  // Récupération des données
			$retour_stat = statistiques ($stat_unite, $stat_duree, $stat_date_fin);
			if($retour_stat["etat"] == TRUE) {
				if($stat_type == "abo") { ?>
			  <table id="Tstats1" class="tablesorter stats" summary="Statistique des abonnements et désabonnements">
				<caption>Evolution des inscriptions, désinscriptions et nombre d'abonnés (données cumulées)</caption>
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
					<th scope="row">Abonnés actifs</th>
                                        <?php foreach($retour_stat["nbre_abo_actif"] as $key => $value) {echo "<td>".$value."</td>";} ?>
				</tr>
				<tr>
					<th scope="row">Abonnés inactifs</th>
                                        <?php foreach($retour_stat["nbre_abo_inactif"] as $key => $value) {echo "<td>".$value."</td>";} ?>
				</tr>
				<tr>
					<th scope="row">Abonnés supprimés</th>
                                        <?php foreach($retour_stat["nbre_abo_suppr"] as $key => $value) {echo "<td>".$value."</td>";} ?>
				</tr>
			  </tbody>
		   </table>
            <table id="Tstats2" class="tablesorter stats" summary="Statistique des abonnements et désabonnements">
				<caption>Evolution des inscriptions, désinscriptions et tendances (par période échue)</caption>
			 <thead>
				<tr>
					<td></td>
                                        <?php foreach($retour_stat["date_propre"] as $key => $value) {echo "<th scope='col'>".$value."</th>";} ?>
				</tr>
			 </thead>
			 <tbody>
				<tr>
            	<th scope="row">Inscriptions période</th>
                <?php foreach($retour_stat["nbre_inscriptions_j"] as $key => $value) {echo "<td>".$value."</td>";} ?>
            </tr>
             <tr>
            	<th scope="row">Désinscriptions période</th>
                <?php foreach($retour_stat["nbre_desinscriptions_j"] as $key => $value) {echo "<td>".$value."</td>";} ?>
            </tr>
             <tr>
            	<th scope="row">Variation abonnés jour</th>
                <?php foreach($retour_stat["tendance_abo_j"] as $key => $value) {echo "<td>".$value."</td>";} ?>
            </tr>
            <tr>
            	<th scope="row">Variation abonnés période</th>
                <?php foreach($retour_stat["tendance_abo_per"] as $key => $value) {echo "<td>".$value."</td>";} ?>
            </tr>
			  </tbody>
		   </table>
		   <?php }
		   else { ?>
		   <table id="Tstats" class="tablesorter stats" summary="Statistiques d'envoi des lettres d'information">
				<caption>Statistiques d'envoi des lettres d'information</caption>
			 <thead>
				<tr>
					<td></td>
                                        <?php foreach($retour_stat["date_propre"] as $key => $value) {echo "<th scope='col'>".$value."</th>";} ?>
				</tr>
			 </thead>
			 <tbody>
				<tr>
					<th scope="row">Nbre li envoyées</th>
                                        <?php foreach($retour_stat["nbre_li_envoyees"] as $key => $value) {echo "<td>".$value."</td>";} ?>
				</tr>
				<tr>
					<th scope="row">Nbre abonnés destinataires</th>
                                        <?php foreach($retour_stat["nbre_abo_destinataires"] as $key => $value) {echo "<td>".$value."</td>";} ?>
				</tr>
				<tr>
					<th scope="row">Nbre messages envoyés</th>
                                        <?php foreach($retour_stat["nbre_mess_envoyes"] as $key => $value) {echo "<td>".$value."</td>";} ?>
				</tr>
			 </tbody>
		   </table>
		   <?php }
                        } else {echo "<p class='erreur'>".$retour_stat["texte"]."</p>";}?>
      </div>
      </div>
      <div id="menu">
        <ul>
            <li><a href="outils.php" title="Retour aux outils de maintenance"><img src="medias/icones/retour.png" height="64" alt="Retour aux outils de maintenance" /></a></li>
        </ul>
    <form name="formulaire" method="POST" action="statistiques.php" enctype="multipart/form-data" class="formulaire small" id="formulaire">
                	<label for="stat_type">Type&nbsp;:</label>
                    <select name="stat_type">
                        <option value="abo" <?php if($stat_type == "abo") {echo "selected='selected'";}?>>Abonnés</option>
                        <option value="li" <?php if($stat_type == "li") {echo "selected='selected'";}?>>Lettres d'information</option>
                    </select>
                    <label for="stat_duree">Durée&nbsp;:</label>
                    <select name="stat_duree">
                        <option value="2" <?php if($stat_duree == 2) {echo "selected='selected'";}?>>2</option>
                        <option value="3" <?php if($stat_duree == 3) {echo "selected='selected'";}?>>3</option>
                        <option value="4" <?php if($stat_duree == 4) {echo "selected='selected'";}?>>4</option>
                        <option value="5" <?php if($stat_duree == 5) {echo "selected='selected'";}?>>5</option>
                        <option value="6" <?php if($stat_duree == 6) {echo "selected='selected'";}?>>6</option>
                        <option value="7" <?php if($stat_duree == 7) {echo "selected='selected'";}?>>7</option>
                        <option value="8" <?php if($stat_duree == 8) {echo "selected='selected'";}?>>8</option>
                        <option value="9" <?php if($stat_duree == 9) {echo "selected='selected'";}?>>9</option>
                        <option value="10" <?php if($stat_duree == 10) {echo "selected='selected'";}?>>10</option>
                        <option value="11" <?php if($stat_duree == 11) {echo "selected='selected'";}?>>11</option>
                        <option value="12" <?php if($stat_duree == 12) {echo "selected='selected'";}?>>12</option>
                    </select>
                    <select name="stat_unite">
                        <option value="jour" <?php if($stat_unite == "jour") {echo "selected='selected'";}?>>Jours</option>
                        <option value="semaine"<?php if($stat_unite == "semaine") {echo "selected='selected'";}?>>Semaines</option>
                        <option value="mois"<?php if($stat_unite == "mois") {echo "selected='selected'";}?>>Mois</option>
                    </select>
                    <label for="stat_date_fin">Fin le &nbsp; :</label>
                    <input name="stat_date_fin" id="stat_date_fin" value="<?php echo datetime_to_fr($stat_date_fin)?>">
                    <input type="hidden" name="form_send_stat" value="1" />
                    <input type="submit" name="editer" value="Editer" />
                </form>
</div>
      <div id="backtotheflux"></div>	
    </div>
</div>

    <div id="footer">
    <?php include("libraries/html-php/footer.inc.php"); ?>
    </div>
</body>

</html>
<?php } 
require("./libraries/secure/bddisconnect.req.php"); 
$content=ob_get_flush(); ?>