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
<title><?php echo $config["html-title"]?>Gestion des abonnés</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
<script  type="text/javascript" src="libraries/jquery.min.js"></script>
<script  type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="libraries/jquery.dataTables.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	 	$('.tablesorter').dataTable({
		"oLanguage": {
            "sLengthMenu": "Afficher _MENU_ abonnés par page",
            "sZeroRecords": "Aucun abonné",
            "sInfo": "Abonnés n°_START_ à _END_ sur _TOTAL_ enregistrés",
            "sInfoEmpty": "Aucun enregistrement correspondant",
            "sInfoFiltered": "(filtrés sur _MAX_ abonnés)"
        },
		"iDisplayLength": 50,
		"aaSorting": [[ 0, "asc" ]],
		"aoColumns": [
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
            <li class="item"><span><a href="lettredinformation.php" title="Gestion des lettres d'information">Lettre d'information</a></span></li>
            <li class="item selection"><span><a href="abonnes.php" title="Gestion des abonnés">Abonnés</a></span></li>
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
       <p class="titre">Liste des abonnés</p>
       <table id="Tabos" class="tablesorter" summary="Liste des utilisateurs de l'interface newsletter">
      	 <thead>
       		<tr>
       			<th>Mail</th>
                <th>Inscription</th>
                <th>Actif</th>
                <th>Dernière LI</th>
                <th>Envoyée le</th>
                <th>Actions</th>
       		</tr>
      	 </thead>
         <tbody>
         	<?php 
			// Récupération des données
			$tableau_classe = liste_abonnes("","abonnes.actif_user<>-1","","","");
                        
                            
                            foreach($tableau_classe as $key => $value)
                            {
                            ?>
                                   <tr>
                                           <td><?php echo $value["mail"]; ?></td>
                                           <td><?php echo $value["dateabonnement"]; ?></td>
                                           <td><?php if($value["actif_user"]<>0) {?><img src="medias/icones/abo-act.png" /><?php } else { ?><img src="medias/icones/abo-dea.png" /><?php } ?></td>
                                           <td><?php echo $value["lastnewsletter"]; ?></td>
                                           <td><?php echo $value["lastsent"]; ?></td>
                                           <td><a href="abonnes-edit.php?uniq_id=<?php echo $value["uniq_id"]; ?>" title="Modifier les informations du compte abonné"><img src="medias/icones/abo-edit.png" alt="Modifier les informations du compte abonné" /></a><a href="abonnes-sup.php?uniq_id=<?php echo $value["uniq_id"]; ?>" title="Supprimer le compte abonné"><img src="medias/icones/abo-del.png" alt="Supprimer le compte abonné" /></a></td>
                                   </tr>

                            <?php
                            }
                         ?>
         </tbody>
         <tfoot>
       		<tr>
       			<th>Mail</th>
                <th>Inscription</th>
                <th>Actif</th>
                <th>Dernière LI</th>
                <th>Envoyée le</th>
                <th>Actions</th>
       		</tr>
      	 </tfoot>
       </table>
</div>
<div id="menu">
	<ul>
    	<li><a href="abonnes-add.php" title="Ajouter un compte abonné"><img src="medias/icones/abo-add.png" height="64" alt="Ajouter un compte abonné" /></a></li>
        <li><a href="abonnes-export.php" title="Export de la base abonné"><img src="medias/icones/export.png" height="64" alt="Export de la base abonné" /></a></li>
        <!-- <li><a href="abonnes-print.php" title="Imprimer la liste des abonnés"><img src="medias/icones/abo-print.png" height="64" alt="Imprimer la liste des abonnés" /></a></li> -->
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
<?php require("libraries/secure/bddisconnect.req.php"); ?>
