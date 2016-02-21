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
<title><?php echo $config["html-title"]?>Gestion des utilisateurs</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
<script  type="text/javascript" src="libraries/jquery.min.js"></script>
<script  type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="libraries/jquery.dataTables.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
	 	$('.tablesorter').dataTable({
		"oLanguage": {
            "sLengthMenu": "Afficher _MENU_ utilisateurs par page",
            "sZeroRecords": "Aucun utilisateur",
            "sInfo": "Utilisateurs n°_START_ à _END_ sur _TOTAL_ enregistrés",
            "sInfoEmpty": "Aucun enregistrement correspondant",
            "sInfoFiltered": "(filtrés sur _MAX_ utilisateurs)"
        },
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
            <li class="item"><span><a href="abonnes.php" title="Gestion des abonnés">Abonnés</a></span></li>
            <?php if (isset($_SESSION["droit"]) && $_SESSION["droit"] == 1) { ?>
            <li class="item selection"><span><a href="utilisateurs.php" title="Gestion des utilisateurs">Utilisateurs</a></span></li>
            <li class="item"><span><a href="outils.php" title="Outils, maintenance">Outils</a></span></li>
            <?php } ?>
            <li class="quit-item"><span><a href="deconnexion.php" title="Déconnexion de l'espace d'administration"><img src="medias/icones/exit.png" width="48" alt="Déconnexion de l'espace d'administration" /></a></span></li>
        </ul>
    </div>
</div>
<div id="main">
    <div id="contenu">

        <div class="tableaudebord">
                <p class="titre">Liste des utilisateurs</p>
               <table id="Tusers" class="tablesorter" summary="Liste des utilisateurs de l'interface newsletter">
                 <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Login</th>
                        <th>Dernière connexion</th>
                        <th>Actif</th>
                        <th>Actions</th>
                    </tr>
                 </thead>
                 <tbody>
                 <?php
				 // Récupération des données
				 $tableau_classe = liste_users("","utilisateurs.actif<>-1","","");
                 foreach($tableau_classe as $key => $value)
                 {
                 ?>
                    <tr>
                        <td><?php echo strtoupper($value["nom"]); ?></td>
                        <td><?php echo ucwords($value["prenom"]); ?></td>
                        <td><?php echo strtolower($value["login"]); ?></td>
                        <td><?php echo $value["lastconnexion"]; ?></td>
                        <td><?php if($value["actif"]<>0) {?><img src="medias/icones/admin-act.png" /><?php } else { ?><img src="medias/icones/admin-dea.png" /><?php } ?></td>
                        <td><a href="utilisateurs-edit.php?uniq_id=<?php echo $value["uniq_id"]; ?>" title="Modifier le compte utilisateur"><img src="medias/icones/admin-edit.png" alt="Modifier le compte utilisateur" /></a><a href="utilisateurs-sup.php?uniq_id=<?php echo $value["uniq_id"]; ?>" title="Supprimer le compte utilisateur"><img src="medias/icones/admin-del.png" alt="Supprimer le compte utilisateur" /></a></td>
                    </tr>
                 <?php
                 }?>
                 </tbody>
                 <tfoot>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Login</th>
                        <th>Dernière connexion</th>
                        <th>Actif</th>
                        <th>Actions</th>
                    </tr>
                 </tfoot>
               </table>
        </div>
        <div id="menu">
            <ul>
                <li><a href="utilisateurs-add.php" title="Créer un nouvel utilisateur de l'administration"><img src="medias/icones/admin-add.png" height="64" alt="Créer un nouvel utilisateur de l'administration"/></a></li>
                <!-- <li><a href="utilisateurs-print.php" title="Imprimer la liste des utilisateurs de l'administration"><img src="medias/icones/admin-print.png" height="64" alt="Imprimer la liste des utilisateurs de l'administration" /></a></li> -->
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
