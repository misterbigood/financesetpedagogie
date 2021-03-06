﻿<?php 
// Vérification de l'accès:
session_start();
ob_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");


if(isset($_GET["uniq_id"])) {
	// Connexion base
	require("./libraries/secure/config.req.php");
	require("./libraries/secure/bdconnect.req.php");
	require("./libraries/secure/fonctions.req.php"); 
	$uniq_id=$_GET["uniq_id"];
}
elseif(!isset($uniq_id)) header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);

// Récupération des informations de la lettre d'information
$tableau_classe = liste_li("","uniq_id='".$uniq_id."'","","1");
$data_unseries = unserialize($tableau_classe[0]["dataseries"]);
//$data_unseries = stripslashes($data_unseries);
// Attribution des variables
$numero					=	$tableau_classe[0]["numero"];
$mois					=	stripslashes($data_unseries["mois"]);	
$annee					=	stripslashes($data_unseries["annee"]);
$lienpdffr				=	$tableau_classe[0]["lienpdffr"];
$lienpdfen				=	$tableau_classe[0]["lienpdfen"];
$li_chapeau				=	stripslashes($data_unseries["li_chapeau"]);
$li_titre				=	stripslashes($tableau_classe[0]["li_titre"]);

$image_path				=	stripslashes($data_unseries["image_path"]);

$alu_titre				=	stripslashes($data_unseries["alu_titre"]);
$alu_chapeau			=	stripslashes($data_unseries["alu_chapeau"]);
$alu_article			=	stripslashes($data_unseries["alu_article"]);
$alu_image				=	stripslashes($data_unseries["alu_image"]);
$alu_lien				=	stripslashes($data_unseries["alu_lien"]);
$alu_intitule_lien		=	stripslashes($data_unseries["alu_intitule_lien"]);	

$am_titre				=	stripslashes($data_unseries["am_titre"]);
$am_chapeau				=	stripslashes($data_unseries["am_chapeau"]);
$am_article				=	stripslashes($data_unseries["am_article"]);
$am_lien				=	stripslashes($data_unseries["am_lien"]);
$am_intitule_lien		=	stripslashes($data_unseries["am_intitule_lien"]);
$am_image				=	stripslashes($data_unseries["am_image"]);

$z_titre				=	stripslashes($data_unseries["z_titre"]);
$z_article				=	stripslashes($data_unseries["z_article"]);
$z_lien					=	stripslashes($data_unseries["z_lien"]);
$z_intitule_lien		=	stripslashes($data_unseries["z_intitule_lien"]);

$no_titre				=	stripslashes($data_unseries["no_titre"]);
$no_chapeau				=	stripslashes($data_unseries["no_chapeau"]);
$no_article				=	stripslashes($data_unseries["no_article"]);

$act1_titre				=	stripslashes($data_unseries["act1_titre"]);
$act1_article				=	stripslashes($data_unseries["act1_article"]);
$act1_lien					=	stripslashes($data_unseries["act1_lien"]);
$act1_intitule_lien		=	stripslashes($data_unseries["act1_intitule_lien"]);

$act2_titre				=	stripslashes($data_unseries["act2_titre"]);
$act2_article				=	stripslashes($data_unseries["act2_article"]);
$act2_lien					=	stripslashes($data_unseries["act2_lien"]);
$act2_intitule_lien		=	stripslashes($data_unseries["act2_intitule_lien"]);

$at_article				=	stripslashes($data_unseries["at_article"]);
$ml_article				=	stripslashes($data_unseries["ml_article"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title>Finances et pédagogie - Lettre d'information n°<?php echo $numero; ?></title>

</head>
<body>
<style type="text/css">
body {	font-family: Georgia, "Times New Roman", Times, serif;	font-size: small;	background-color: #FFF;}
li {	list-style-image: url(http://www.finances-pedagogie.fr/lettreinfo/newsletter/images/fleche_vert.gif);}
h1 {	font-size: 1.5em;}
h2 {	font-size: 1.4em;	color:#7d112b;}
h3 {	font-size: 1.1em;	font-weight: normal;	font-style: italic;}
h4 {	font-size: 1.1em;	font-weight: normal;	color:#7d112b;}
p {	text-align:left;	font-size: 1em;}
a {	font-size: .9em;}
.small {	font-size: 0.9em;	vertical-align: middle;}
.small>img {	height: 9px;	margin: 0 5px 0 0;}
p + img {	margin: 20px 0 10px 0;}
.interview {	font-family:Georgia, "Times New Roman", Times, serif;	font-size: .85em;}
.question {	font-style: italic;	font-weight: bold;	color:#b1a116;}
table.lettreinfo {	border: 10px #BBAC1F solid;}
.lettreinfo td, th {	border: none;}
.mlegale {	font-size: 0.7em;}
</style>

<table width="640" border="0" cellpadding="8" cellspacing="0">
<tbody>
	<tr>
    	<td colspan="2" class="small">
        Si vous ne visualisez pas correctement la lettre d'information, <a href="<?php echo $config["serveur"].$config["root"].$config["li_html"].$uniq_id.".html"; ?>" target="_blank">cliquez ici</a> pour l'ouvrir dans votre navigateur web.
       	</td>
    </tr>
</tbody>
</table>
<table class="lettreinfo" width="640" cellpadding="10" cellspacing="0" summary="Lettre d'information n°<?php echo $numero; ?> - <?php echo $mois." ".$annee; ?>">
<tbody>
    <tr>
    	<td colspan="2"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>logo.gif" width="600" alt="Logo de paroles d'argent">
        <p>N°<?php echo $numero; ?> - <?php echo $mois." ".$annee; ?></p>
        <?php if ($li_chapeau <> "") {echo "<h4>".$li_chapeau."</h4>";} ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>titre_alaune.gif" width="382" alt="A la une"></td>
    </tr>
    <tr>
    	<td style="vertical-align:top">
        	<h2><?php echo $alu_titre; ?></h2>
             <?php if ($alu_chapeau <> "") {echo "<h3>".$alu_chapeau."</h3>";} ?>
            <?php echo $alu_article; ?>
             <?php if($alu_lien<>"" && $alu_intitule_lien<>"") { ?><p class="small"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>fleche_rouge2.gif" alt="Flèche rouge" /><a href="<?php echo $alu_lien; ?>"><?php echo $alu_intitule_lien; ?></a></p><?php } ?>
        	<img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>titre_arret.gif" width="382" alt="Arrêt métier">
            <h2><?php echo $am_titre; ?></h2>
           <?php if ($am_chapeau <> "") {echo "<h3>".$am_chapeau."</h3>";} ?>
            <?php echo $am_article; ?>
            <?php if($am_lien<>"" && $am_intitule_lien<>"") { ?><p class="small"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>fleche_rouge2.gif" alt="Flèche rouge" /><a href="<?php echo $am_lien; ?>"><?php echo $am_intitule_lien; ?></a></p><?php } ?>
        </td>
        <td style="vertical-align:top" width="200">
        	<?php if(file_exists($config["fromadmin"].$image_path.$alu_image) && is_file($config["fromadmin"].$image_path.$alu_image) ) { ?><img src="<?php echo $config["serveur"].$config["root"].$image_path.$alu_image; ?>" width="200" alt="Image A la une"><?php } ?>
            <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>img_itw.gif" width="189" alt="Logo interview">
            <h2><?php echo $act1_titre; ?></h2>
            <span class="interview"><?php echo $act1_article; ?></span>
            <?php if($act1_lien<>"" && $act1_intitule_lien<>"") { ?><p class="small"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>fleche_rouge2.gif" alt="Flèche rouge" /><a href="<?php echo $act1_lien; ?>"><?php echo $act1_intitule_lien; ?></a></p><?php } ?>
 		</td>
    </tr>
    <tr>
    	<td colspan="2"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>titre_zoom.gif" width="582" alt="Zoom"></td>
    </tr>
    <tr>
    	<td>
        	<h2><?php echo $z_titre; ?></h2>
            <?php if ($z_chapeau <> "") {echo "<h3>".$z_chapeau."</h3>";} ?>
            <?php echo $z_article; ?>
        </td>
        <td style="vertical-align:top">
        	<?php if(file_exists($config["fromadmin"].$image_path.$am_image) && is_file($config["fromadmin"].$image_path.$am_image) ) { ?><img src="<?php echo $config["serveur"].$config["root"].$image_path.$am_image; ?>" width="200" alt="Image Zoom"><?php } ?>
            <?php if($z_lien<>"" && $z_intitule_lien<>"") { ?><p class="small"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>fleche_rouge2.gif" alt="Flèche rouge" /><a href="<?php echo $z_lien; ?>"><?php echo $z_intitule_lien; ?></a></p><?php } ?>
 		</td>
    </tr>
    <tr>
    	<td colspan="2"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>ligne_verte.gif" width="582" alt="ligne verte"></td>
    </tr>
    <tr>
    	<td colspan="2">
        	<h2>Autres actualités de Finances & Pédagogie</h2>
        	<?php echo $at_article; ?>
        </td>
    </tr>
     <tr>
     	<td colspan="2" class="small"><a href="<?php echo $lienpdffr; ?>"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>btn_pdf.gif" style="border:none; margin-right: 100px" width="241" alt="PDF français" /></a><a href="http://www.finances-pedagogie.fr" ><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>btn_site.gif" style="border:none;" width="256" alt="Site Finances & Pédagogie"></a>
         </td>
     </tr>
     <tr>
    	<td colspan="2">
        	 <span class="small">Tous droits réservés <?php echo $mois." ".$annee; ?> - Finances & Pédagogie - Copyright © <?php echo $annee; ?> Finances & Pédagogie - All rights reserved.</span>
        	<table border="0" width="100%">
            	<tr>
                	<td>
                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>logoFP.jpg" width="66" alt="logo finances et pédagogie" />
                    </td>
                    <td>
                       <span class="mlegale"><?php echo $ml_article; ?> Conception et réalisation (newsletter et application), &copy; <a href="http://www.marquedefabrique.net" target="_blank" style="font-size: 1em;">Marquedefabrique</a> 2012.</span>
                    </td>
                </tr>
         	</table>
        </td>
    </tr>
     <tr>
    	<td colspan="2" bgcolor="#2B2700">
        	
        	<p class="small" align="center"><a href="http://www.finances-pedagogie.fr/lettreinfo/_desinscription.php<?php $content1 = ob_get_clean(); ob_start(); ?>">Se désabonner de la Newsletter / Unsubscribe to Newsletter</a></p>
        </td>
    </tr>
</tbody>
</table>
<table width="640" border="0" cellpadding="8" cellspacing="0">
<tbody>
	<tr>
    	<td align="right" ><a href="<?php echo $lienpdffr?>" >Cliquez ici</a> pour lire la version complète au format PDF</td>
        <td width = "200"><a href="<?php echo $lienpdffr?>"><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>drapo_fr.gif" style="border:none;"width="26" alt="PDF en français"></a></td>
    </tr>
	
</tbody>
</table>
</body>
</html>
<?php require("./libraries/secure/bddisconnect.req.php");
$content2=ob_get_clean();
if(isset($_GET["uniq_id"])) {
	
	echo $content1.$content2;
}
else {
	file_put_contents($config["fromadmin"].$config["li_html"].$uniq_id.".html", $content1.$content2);
	file_put_contents($config["fromadmin"].$config["li_html"].$uniq_id."-part1.html", $content1);
	file_put_contents($config["fromadmin"].$config["li_html"].$uniq_id."-part2.html", $content2);
} ?>
