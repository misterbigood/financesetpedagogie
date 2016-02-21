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
<title><?php echo $config["html-title"]?>Gestion des lettres d'information</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
<script  type="text/javascript" src="libraries/jquery.min.js"></script>
<script  type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="libraries/jquery.dataTables.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	 	$('.tablesorter').dataTable({
		"oLanguage": {
            "sLengthMenu": "Afficher _MENU_ lettres d'information par page",
            "sZeroRecords": "Aucune lettre d'information",
            "sInfo": "Lettres d'information n°_START_ à _END_ sur _TOTAL_ enregistrées",
            "sInfoEmpty": "Affichage de 0 à 0 sur 0 enregistrements",
            "sInfoFiltered": "(filtrés sur _MAX_ lettres d'informations)"
        },
		"aaSorting": [[ 4, "asc" ]],
		"aoColumns": [
            null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			{ "asSorting": [ null ] }
        ]
		
		});
});
</script>
</head>
<body>
<div role="navigation">
	<div id="header"></div>
	<div id="menu-horizontal">
    	<ul>
        	<li class="item"><span><a href="tableaudebord.php" title="Tableau de bord">Tableau de bord</a></span></li>
            <li class="item selection"><span><a href="lettredinformation.php" title="Gestion des lettres d'information">Lettre d'information</a></span></li>
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
       <p class="titre">Liste des lettres d'information</p>
       <table id="Tli" class="tablesorter" summary="Liste des lettres d'information">
      	 <thead>
       		<tr>
       			<th>N&deg;</th>
                <th>Titre / Objet</th>
                <th>Date pub.</th>
                <th>Statut</th>
                <th>Date création</th>
                <th>Date activation</th>
                <th>Premier envoi</th>
                <th>Dernier envoi</th>
                <th>Actions</th>
       		</tr>
      	 </thead>
         <tbody>
         	<?php 
			// Récupération des données
			$tableau_classe = liste_li("","actif_li <> '-2'","","");
			 foreach($tableau_classe as $key => $value)
			 {
				 $data_unseries = unserialize($value["dataseries"]);
			 ?>
				<tr>
					<td><?php echo $value["numero"]; ?></td>
                    <td><?php if($value["li_titre"]<>"" ) {echo stripslashes($value["li_titre"]);} else {echo "-"; }?></td>
                    <td><?php echo $data_unseries["mois"]." ".$data_unseries["annee"]; ?></td>
					<td><?php echo utf8_encode($config["statut_li"][$value["actif_li"]]); ?></td>
					<td><?php echo $value["datecreation"]; ?></td>
					<td><?php echo $value["dateactivationenvoi"]; ?></td>
                    <td><?php if( $value["datepremierenvoi"]<> "0000-00-00 00:00:00" ){ echo $value["datepremierenvoi"]; } else { echo "-";} ?></td>
                    <td><?php if( $value["datedernierenvoi"]<> "0000-00-00 00:00:00" ){ echo $value["datedernierenvoi"]; } else { echo "-";} ?></td>
                    <td><?php if($value["actif_li"]>=0) {?><a href="lettredinformation-edit.php?uniq_id=<?php echo $value["uniq_id"]; ?>" title="Modifier la lettre d'information"><img src="medias/icones/news-edit.png" alt="Modifier la lettre d'information" /></a><?php } ?>
                    <?php if($value["actif_li"] ==-1) {?><a href="lettredinformation-activation.php?uniq_id=<?php echo $value["uniq_id"]; ?>" title="Réactiver la lettre d'information"><img src="medias/icones/news-edit.png" alt="Réactiver la lettre d'information" /></a><?php } ?><a href="lettredinformation-sup.php?uniq_id=<?php echo $value["uniq_id"]; ?>" title="Supprimer la lettre d'information"><img src="medias/icones/news-del.png" alt="Supprimer la lettre d'information" /></a><a href="lettredinformation-html-create.php?uniq_id=<?php echo $value["uniq_id"]; ?>" target="_blank" title="Afficher la lettre d'information"><img src="medias/icones/news-visu.png" alt="Afficher les détails de la lettre d'information"/></a></td>
				</tr>
                
			 <?php
			 }?>
         </tbody>
         <tfoot>
       		<tr>
       			<th>N&deg;</th>
                <th>Titre / Objet</th>
                <th>Date pub.</th>
                <th>Statut</th>
                <th>Date création</th>
                <th>Date activation</th>
                <th>Premier envoi</th>
                <th>Dernier envoi</th>
                <th>Actions</th>
       		</tr>
      	 </tfoot>
       </table>
</div>
<div id="menu">
	<ul>
    	<li><a href="lettredinformation-add.php" title="Créer une nouvelle lettre d'information"><img src="medias/icones/news-add.png" height="64" alt="Créer une nouvelle lettre d'information" /></a></li>
        <!-- <li><a href="lettredinformation-print.php" title="Imprimer la liste des lettres d'information"><img src="medias/icones/news-print.png" height="64" alt="Imprimer la liste des lettres d'information" /></a></li> -->
    </ul>
    
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