<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");

/* Si le formulaire a été envoyé, récupération des données*/
// FORMULAIRE ENVOYE ---------------------------------------------------------------------------------
if(isset($_POST["form_send"]) && $_POST["form_send"]==1)
{
		// Récupération des variables de formulaire
		$data_select = "mail, dateabonnement, datedesabonnement, actif_user";
		
                if($_POST["ffiltre1"]<>"") {$tabfiltre[] = 	stripslashes($_POST["ffiltre1"]);}
                if($_POST["ffiltre2"]<>"") {$tabfiltre[] = 	stripslashes($_POST["ffiltre2"]);}
                if($_POST["ffiltre3"]<>"") {$tabfiltre[] = 	stripslashes($_POST["ffiltre3"]);}
		$data_where = "actif_user='9' ";
		if(sizeof($tabfiltre)>0)
		{
			foreach($tabfiltre as $key => $value) {
				$data_where .= " OR ".$value;
			}
		}
		$data_order = stripslashes($_POST["ftri"]);
				
		
		// Envoi de la requête
		$fichier_export = ""; // "/kunden/homepages/21/d280878883/htdocs/lettreinfo/tmp/".date("Y-m-d-H-i-s")."-export.csv";
		$tableau_classe = liste_abonnes($data_select,$data_where,$data_order,"", $fichier_export);
		$contenu = "Mail;Date abonnement; Date désabonnement; Statut abonné\r\n";
		if(sizeof($tableau_classe)>0)
		{
			foreach($tableau_classe as $key => $value)
			{
				$contenu .= $value["mail"].";".datetime_to_fr_with_time($value["dateabonnement"]).";".datetime_to_fr_with_time($value["datedesabonnement"]).";";
				$value["actif_user"]==0 ? $contenu .= "Inactif" : ($value["actif_user"] == 1 ? $contenu .= "Actif" : $contenu .= "Supprimé");
				$contenu .= "\r\n";
			}
		$filename = date("Y-m-d-H-i-s")."-export.csv";
		$file= "../tmp/".$filename;
		$fp=fopen($file,"w" ); // ouverture du fichier 
		fputs($fp,utf8_decode($contenu)); // enregistrement des données ds le fichier 
		fclose($fp);
		header("Content-Type: application/force-download" );

		header("Content-Length: ".filesize($file));

		header("Content-Disposition: attachment; filename=".$filename);

		readfile($file);

		unlink($file);
		/*ob_start(); // Mise en tampon des contenus
		?>
        <p>Export réussi. Pour télécharger votre fichier, cliquez <a href="http://www.finances-pedagogie.fr/lettreinfo/tmp/<?php echo $file ?>">ici</a>.</p>
		<?php
		$content = ob_get_clean();*/
		}
		else
		{
		ob_start(); // Mise en tampon des contenus
		?>
        <p>Echec de l'export. Aucune donnée n'a été sélectionnée. Merci de revoir les critères de filtre et de tri que vous avez entrés.</p>
        <p>Revenir sur la <a href="abonnes-export.php">page d'export</a>.</p>
		<?php
		$content = ob_get_clean();
		}
}
// FIN DE TRAITEMENT DU FORMULAIRE ENVOYE ------------------------------------------------------------
else
// FORMULAIRE NON ENVOYE -----------------------------------------------------------------------------
{
		ob_start();
		?>
        <form id="form1" name="form1" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
       <p>Trier par...</p>
       <p>
          <label>
            <input name="ftri" type="radio" id="Fonctiondetri_0" value="mail ASC" checked="checked" />
            Mails (ordre ascendant)</label>
          <br />
          <label>
            <input type="radio" name="ftri" value="mail DESC" id="Fonctiondetri_1" />
            Mails (ordre descendant)</label>
          <br />
          <label>
            <input type="radio" name="ftri" value="dateabonnement ASC, mail ASC" id="Fonctiondetri_2" />
            Date d'inscription (ordre ascendant)</label>
          <br />
          <label>
            <input type="radio" name="ftri" value="dateabonnement DESC, mail ASC" id="Fonctiondetri_3" />
            Date d'inscription (ordre descendant)</label>
          <br />
          <label>
            <input type="radio" name="ftri" value="datedesabonnement ASC, mail ASC" id="Fonctiondetri_4" />
            Date de désinscription (ordre ascendant)</label>
          <br />
          <label>
            <input type="radio" name="ftri" value="datedesabonnement DESC, mail ASC" id="Fonctiondetri_5" />
            Date de désinscription (ordre descendant)</label>
          <br />
        </p>
        <p>Filtrer le statut...</p>
        <p>
          <label>
            <input name="ffiltre1" type="checkbox" id="CheckboxGroup1_0" value=" actif_user='1'" checked="checked" />
            Compte abonné actif</label>
          <br />
          <label>
            <input name="ffiltre2" type="checkbox" id="CheckboxGroup1_1" value=" actif_user='0'" checked="checked" />
            Compte abonné inactif</label>
          <br />
          <label>
            <input name="ffiltre3" type="checkbox" id="CheckboxGroup1_2" value=" actif_user='-1'" checked="checked" />
            Compte abonné supprimé</label>
          <br />
        </p>
         <input type="hidden" name="form_send" value="1" />
        <input name="Exporter" value="Exporter" type="submit" />
      </form>
		<?php
		$content = ob_get_clean();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title><?php echo $config["html-title"]?>Export de la base des abonnés</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
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
       <p class="titre">Export de la base abonnés</p>
        <?php echo $content ?>
      
</div>
<div id="menu">
<ul>
	<li><a href="abonnes-add.php" title="Ajouter un compte abonné"><img src="medias/icones/abo-add.png" height="64" alt="Ajouter un compte abonné" /></a></li>
    <li><a href="abonnes.php" title="Retour à la liste des abonnés"><img src="medias/icones/retour.png" height="64" alt="Retour à la liste des abonnés" /></a></li>
   
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
