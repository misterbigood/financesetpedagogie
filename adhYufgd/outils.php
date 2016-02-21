<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
(isset($_SESSION["droit"]) && ($_SESSION["droit"] == 1) ) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
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
<title><?php echo $config["html-title"]?>Outils</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.8.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="libraries/jquery.min.js"></script>
<script type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="libraries/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="libraries/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	 	$('.tablesorter').dataTable({
     "bFilter": false,
     "aaSorting": [[ 0, "desc" ]]
     });
});
	$(function() {
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

        <div class="tableaudebord">
                <p class="titre">Outils</p><br/>
          <div class="outils">
           	<p class="titre">Import d'une base de données</p>
            <form id="formulaire" name="formulaire" enctype="multipart/form-data" method="post" action="import.php" class="formulaire">
                      <label for="fichier">Fichier à importer</label>
                      <span>(Fichier enregistré au format csv contenant les adresses mails sur une colonne et, de manière facultative, le statut (actif:1 ou non:0) sur la seconde colonne)</span>
                      <input type="file" name="fichier" id="fichier" />
                      <input type="submit" name="envoyer" id="envoyer" value="Envoyer" />
            </form>
            </div>
            <div class="outils right">
                <p class="titre">Statistiques</p>
                <p>Vous pouvez éditer une statistique en sélectionnant le type désiré (sur les abonnés ou les envois de lettre d'information), une durée et la date de fin. La statistique éditée couvrira la durée choisie jusqu'à la date de fin spécifiée.</p>
                <form name="formulaire" method="POST" action="statistiques.php" enctype="multipart/form-data" class="formulaire" id="formulaire">
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
            <div class="outils">
                <p class="titre">Lancement manuel d'un mailing</p>
                <p>Cette fonction permet de lancer une séquence de mailing à la place de celle programmée sur le serveur. Sauf exception, merci de ne pas vous en servir afin de ne pas charger le serveur.</p>
                <a href="../_mailing.php" target="_blank">Lancer</a>
            </div>
            <div class="outils">
                <p class="titre">Log des connexions</p>
                <table id="Tusers" class="tablesorter" summary="Liste des utilisateurs de l'interface newsletter">
                 <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>IP</th>
                        <th>Op.</th>
                    </tr>
                 </thead>
                 <tbody>
                 <?php
				 // Récupération des données
				 $tableau_classe = liste_users_log("","","","");
                 foreach($tableau_classe as $key => $value)
                 {
                 ?>
                    <tr>
                        <td><?php echo $value["dateconnexion"]; ?></td>
                        <td><?php echo strtoupper($value["nom"]); ?></td>
                        <td><?php echo $value["ipconnexion"]; ?></td>
                        <td><?php $op=array(-9=>"Echec", -1=>"Out", 1=> "In");
                                  echo $op[$value["statutconnexion"]]; ?></td>
                   </tr>
                 <?php
                 }?>
                 </tbody>
              </table>
            </div>
            <div class="outils right">
                <p class="titre">Liste des abonnés supprimés</p>
                <table id="Tabos" class="tablesorter" summary="Liste des utilisateurs de l'interface newsletter">
      	 <thead>
       		<tr>
       			    <th>Id</th>
                <th>Insc.</th>
                <th>Dés.</th>
                <th>Envoi</th>
       		</tr>
      	 </thead>
         <tbody>
         	<?php 
			// Récupération des données
			$tableau_classe_abo = liste_abonnes("","abonnes.actif_user='-1'","","","");
			 foreach($tableau_classe_abo as $key => $value)
			 {
			 ?>
                                <tr <?php if(strtotime($value["datedesabonnement"])< (strtotime($value["lastsent"])+(60*60*24*7)) ) {echo "style='background: red;'";} ?>>
					<td><?php echo $value["id_user"]; ?></td>
					<td><?php echo $value["dateabonnement"]; ?></td>
					<td><?php echo $value["datedesabonnement"]; ?></td>
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
<?php require("libraries/secure/bddisconnect.req.php");