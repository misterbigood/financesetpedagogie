<?php 
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

$z_titre				=	stripslashes($data_unseries["z_titre"]);
$z_article				=	stripslashes($data_unseries["z_article"]);
$am_image				=	stripslashes($data_unseries["am_image"]);
$z_lien					=	stripslashes($data_unseries["z_lien"]);
$z_intitule_lien		=	stripslashes($data_unseries["z_intitule_lien"]);

$no_titre				=	stripslashes($data_unseries["no_titre"]);
$no_chapeau				=	stripslashes($data_unseries["no_chapeau"]);
$no_article				=	stripslashes($data_unseries["no_article"]);

$act1_titre				=	stripslashes($data_unseries["act1_titre"]);
$act1_article				=	stripslashes($data_unseries["act1_article"]);
$act1_lien					=	stripslashes($data_unseries["act1_lien"]);
$act1_intitule_lien		=	stripslashes($data_unseries["act1_intitule_lien"]);
$act1_image		=	stripslashes($data_unseries["act1_image"]);
$act2_titre				=	stripslashes($data_unseries["act2_titre"]);
$act2_article				=	stripslashes($data_unseries["act2_article"]);
$act2_lien					=	stripslashes($data_unseries["act2_lien"]);
$act2_intitule_lien		=	stripslashes($data_unseries["act2_intitule_lien"]);
$act2_image		=	stripslashes($data_unseries["act2_image"]);

$at_titres				=	unserialize(stripslashes($data_unseries["at_titres"]));
$ml_article				=	stripslashes($data_unseries["ml_article"]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Finances et pédagogie / Newsletter </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta content="width=device-width">
            <meta http-equiv="Pragma" content="no-cache" />
            <meta name="Robots" content="noindex, nofollow" />
            <meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
            <meta name="Language" content="fr" />
            <title>Finances et pédagogie - Lettre d'information n°<?php echo $numero; ?></title>
                <style type="text/css">
                    /* Fonts and Content */
                    body, td { font-family: Arial, Helvetica, Geneva, sans-serif; font-size:11px; }
                    body { background-color: #fff;  
                           margin: 0; padding: 0;
                           -webkit-text-size-adjust:none; 
                           -ms-text-size-adjust:none; 
                           color: #000; }
                    a{
                        text-decoration: none;
                    }
                    a:hover,a:visited{
                        color:inherit;
                    }
                    h1{
                        font-size: 24px;
                        font-weight: lighter;
                        text-transform:uppercase;
                        color:#8e2b70; /*violet*/
                    }
                    h2{ padding-top:12px; /* ne fonctionnera pas sous Outlook 2007+ */color:#636466;/*gris foncé*/ font-size:15px; }
                    h3{text-transform: uppercase;}
                    p{
                        line-height: 15px;
                        color:#636466;/*gris foncé*/
                    }
                    .chapo{
                        font-weight: bold;
                        font-size: 14px;
                    }
                    .extrait{
                        font-size:12px;
                        font-weight: bold;
                        color:#8e2b70; /*violet*/


                    }
                    strong{
                        color: #000000;
                    }
                    .small{
                        font-size: 10px;
                    }

                    /*rouge:#E41A18*/

                </style>

                </head>
                <body style="margin:0px; padding:0px; -webkit-text-size-adjust:none;">

                    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" >
                        
                            <tr>
                                <td align="center" bgcolor="#ffffff">
                                    <table  cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                                        <tr><!-- visualisation dans navigateur web -->
                                                <td align="center" class="w640"  width="640" height="20"> 
                                                    <span style="color:#000000; font-size:10px;">Si vous ne visualisez pas correctement la lettre d’information, <a href="<?php echo $config["serveur"].$config["root"].$config["li_html"].$uniq_id.".html"; ?>" target="_blank">cliquez ici</a> pour l’ouvrir dans votre navigateur web.</span></td>

                                            </tr>
                                            <tr><!-- en tête -->
                                                <td width="660" bgcolor="#FFFFFF">
                                                    <table  class="w660"  width="660"  cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                                                           <td width="15" height="127" rowspan="4"></td>
                                                            <td rowspan="4">
                                                                <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>bandeau.jpg" width="566" height="127" alt=""></td>
                                                            <td width="64" height="18" bgcolor="#E41A18" style="color:#fff; text-align: right;"><?php echo strtoupper($mois); ?>&nbsp;&nbsp;
                                                            </td>
                                                            <td width="15" height="127" rowspan="4">
                                                            </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="64" height="12" bgcolor="#E41A18" style="color:#fff; text-align: right;"><?php echo $annee; ?>&nbsp;&nbsp;
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="64" height="14" bgcolor="#E41A18" style="color:#fff; text-align: right;">N°<?php echo $numero; ?>&nbsp;&nbsp;
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>bandeau-triangle.jpg" width="64" height="83" alt=""></td>
                                                            </tr>

                                                       </table>
                                                </td>
                                            </tr><!-- fin en tête -->

                                            <!-- espacement -->
                                            <tr>
                                                <td width="660" height="12"style="border-top:dotted 2px #919294;"></td>
                                            </tr>


                                            <tr> <!-- Edito -->
                                                <td width="660" bgcolor="#fff" >
                                                    <table  class="w660"  width="660" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFF">
                                                            <tr>
                                                                <td width="40" height="166">
                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>spacer.gif" width="40" height="166" alt=""></td>
                                                                <td>
                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>photo-edito.jpg" width="80" height="166" alt=""></td>
                                                                <td width="25" height="166">
                                                                </td>
                                                                <td width="475" height="166">
                                                                    <?php echo $li_chapeau; ?>
                                                                    </td>
                                                                <td width="40" height="166">
                                                                </td>
                                                            </tr>
                                                   
                                                    </table>
                                                </td>
                                            </tr><!-- Fin Edito -->

                                            <!-- espacement -->
                                            <tr>
                                                <td width="660" height="12"style="border-top:dotted 2px #919294;"></td>
                                            </tr>


                                            <tr><!-- A la une -->
                                                <td width="660">
                                                    <table  class="w660"  width="660" cellpadding="0" cellspacing="0" border="0" bgcolor="#ececec">
                                                            <tr>

                                                                <td width="15" rowspan="9">
                                                                </td>
                                                                <td width="25" rowspan="9" bgcolor="#ECECEC">
                                                                </td>
                                                                <td width="590" height="21" colspan="8" bgcolor="#ECECEC">
                                                                </td>
                                                                <td width="15" rowspan="9" bgcolor="#ECECEC">
                                                                </td>
                                                                <td width="15" rowspan="9"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="590" height="20" colspan="8">
                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>alaune.png" width="590" height="20" alt=""></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan=8" height="8"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="298" rowspan="3" style="vertical-align:top">
                                                                    <h1><?php echo $alu_titre; ?></h1>
                                                                    <div class="chapo">
                                                                        <?php echo $alu_chapeau; ?></div>
                                                                </td>
                                                                <td width="10" rowspan="3"></td>
                                                                <td width="282" colspan="6">
                                                                    <?php if(file_exists($config["fromadmin"].$image_path.$alu_image) && is_file($config["fromadmin"].$image_path.$alu_image) ) { ?><img src="<?php echo $config["serveur"].$config["root"].$image_path.$alu_image; ?>" width="282" alt="Image A la une"><?php } ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="282" height="4" colspan="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="10" height="40" style="border-left:solid 2px #E41A18;">
                                                                </td>
                                                                <td width="272" colspan="5" class="extrait" style="vertical-align:top">
                                                                    <?php if ($alu_lien<>"") {?>
                                                                    <a href="<?php echo $alu_lien; ?>"><?php echo $alu_intitule_lien; ?></a>
                                                                    <?php }
                                                                    else { echo $alu_intitule_lien; } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="406" colspan="4" rowspan="3" style="vertical-align:top">
                                                                    <?php echo $alu_article; ?>
                                                                    </td>
                                                                <td width="9" rowspan="3">
                                                                </td>
                                                                <td width="175" height="25" colspan="3" >
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="175" height="40" colspan="3" bgcolor="#ffffff">
                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>alaune-zoom.png" width="175" height="40" alt=""></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="10" bgcolor="#ffffff">
                                                                </td>
                                                                <td width="155" bgcolor="#ffffff">
                                                                    <h3><?php echo $z_titre; ?></h3>
                                                                    <?php echo $z_article; ?>
                                                                            </td>
                                                                            <td width="10" bgcolor="#ffffff">
                                                                            </td>
                                                                            </tr>
                                                            <tr><td colspan="12" height="16" bgcolor="#ECECEC"></td></tr>

                                                                           
                                                                            </table>
                                                                            </td>
                                                                            </tr><!-- Fin A la une -->


                                                                            <!-- espacement -->
                                                                            <tr>
                                                                                <td width="660" height="19"></td>
                                                                            </tr>



                                                                            <tr> <!-- Arret métier -->
                                                                                <td width="660"  bgcolor="#FFFFFF">
                                                                                    <table  class="w630"  width="660" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                                                                                            <tr>
                                                                                                <td width="40" rowspan="9"></td>
                                                                                                <td width="590" height="20" colspan="5"></td>
                                                                                                <td width="30" rowspan="9"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="590" height="13" colspan="5">
                                                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>arret-metier.png" width="590" height="20" alt=""></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="405" rowspan="6" style="vertical-align:top">
                                                                                                    <h1><?php echo $am_titre; ?></h1>
                                                                                                    <div class="chapo"><?php echo $am_chapeau; ?></div>
                                                                                                    <?php echo $am_article; ?>
                                                                                                    </td>
                                                                                                <td width="10" >
                                                                                                </td>
                                                                                                <td width="175"  colspan="3">
                                                                                                    <?php if(file_exists($config["fromadmin"].$image_path.$am_image) && is_file($config["fromadmin"].$image_path.$am_image) ) { ?><img src="<?php echo $config["serveur"].$config["root"].$image_path.$am_image; ?>" width="175" alt="Arrêt métier"><?php } ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="10" rowspan="5">
                                                                                                </td>
                                                                                                <td width="175" height="20" colspan="3"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="10" style="border-left:solid 2px #E41A18;">
                                                                                                </td>
                                                                                                <td width="155" class="extrait">
                                                                                                <?php if ($am_lien<>"") {?>
                                                                                                <a href="<?php echo $am_lien; ?>"><?php echo $am_intitule_lien; ?></a>
                                                                                                <?php }
                                                                                                else { echo $am_intitule_lien; } ?>
                                                                                                </td>
                                                                                                <td width="10" >
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="175" height="10" colspan="3">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="175" height="40" colspan="3" bgcolor="#f4f4f4">
                                                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>arret-metier-notre-partenaire.png" width="175" height="40" alt=""></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="10" bgcolor="#f4f4f4"></td>
                                                                                                <td width="155" bgcolor="#f4f4f4" class="small" valign="top">
                                                                                                    <h3><?php echo $no_titre; ?></h3>
                                                                                                    <?php echo $no_article; ?>
                                                                                                </td>
                                                                                                <td width="10" bgcolor="#f4f4f4">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="590" height="7" colspan="5">
                                                                                                </td>
                                                                                            </tr>

                                                                                 
                                                                                    </table>
                                                                                </td>
                                                                            </tr> <!-- Fin Arret métier -->

                                                                            <!-- espacement -->
                                                                            <tr>
                                                                                <td width="660" height="20"></td>
                                                                            </tr>


                                                                            <tr><!--Actualités  -->
                                                                                <td width="660" bgcolor="#ebd9e5">
                                                                                    <table  class="w630"  width="660" cellpadding="0" cellspacing="0" border="0" >
                                                                                            <tr>
                                                                                                <td width="15" height="298" rowspan="13"></td>
                                                                                                <td width="25" height="298" rowspan="13"></td>
                                                                                                <td width="590" height="15" colspan="8"></td>
                                                                                                <td width="15" height="298" rowspan="13"></td>
                                                                                                <td width="15" height="298" rowspan="13"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td colspan="8">
                                                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>actualites.png" width="590" height="20" alt=""></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="590" height="20" colspan="8"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="185" height="225" style="vertical-align:top">
                                                                                                    <h2><?php echo $act1_titre; ?></h2>
                                                                                                    <?php echo $act1_article; ?>
                                                                                                    <?php if(file_exists($config["fromadmin"].$image_path.$act1_image) && is_file($config["fromadmin"].$image_path.$act1_image) ) { ?><img src="<?php echo $config["serveur"].$config["root"].$image_path.$act1_image; ?>" width="185" alt="Actualité 1"><?php } ?>
                                                                                                    
                                                                                                </td>
                                                                                                <td width="10" height="225"></td>
                                                                                                <td width="195" height="225" style="vertical-align:top">
                                                                                                    <h2><?php echo $act2_titre; ?></h2>
                                                                                                    <?php echo $act2_article; ?>
                                                                                                    <?php if(file_exists($config["fromadmin"].$image_path.$act2_image) && is_file($config["fromadmin"].$image_path.$act2_image) ) { ?><img src="<?php echo $config["serveur"].$config["root"].$image_path.$act2_image; ?>" width="185" alt="Actualité 2"><?php } ?>
                                                                                                </td>
                                                                                                <td width="10" height="225"></td>
                                                                                                <td width="10" height="225" bgcolor="#ffffff"></td>
                                                                                                <td width="165" bgcolor="#ffffff" style="vertical-align:top;">
                                                                                                    <img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>actualites-autres.png" width="165" height="45" alt="">
                                                                                                        <table>
                                                                                                            <?php foreach($at_titres as $at_titre) {?>
                                                                                                            <tr><td style="border-left:solid 2px #E41A18;" width="5"></td><td><?php echo $at_titre;?></td></tr>
                                                                                                            <tr><td height="5"></td><td></td></tr>
                                                                                                            <?php } ?>
                                                                                                        </table></td>
                                                                                                <td width="15" height="225" bgcolor="#ffffff"></td>
                                                                                            </tr>
                                                                                                                                                                                    <tr>
                                                                                                <td height="18" colspan="8">
                                                                                                </td>
                                                                                            </tr>
                                                                                       
                                                                                    </table>
                                                                                </td>
                                                                            </tr><!--Fin Actualités  -->
                                                                          


                                                                            <tr>  <!-- pied de page -->
                                                                                <td width="660" height="298" bgcolor="#FFFFFF">
                                                                                    <table  class="w660"  width="660" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                                                                                            <tr>
                                                                                                <td width="15" height="234" rowspan="7"></td>
                                                                                                <td width="630" height="25"></td>
                                                                                                <td width="15" height="234" rowspan="7"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="630" height="15" style="text-align: center; color:#636466;">
                                                                                                    <h3>Pour aller plus loin : <a href="http://www.finances-pedagogie.fr">www.finances-pedagogie.fr</a></h3>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="630" height="23"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <a href="<?php echo $lienpdffr?>" ><img src="<?php echo $config["serveur"].$config["root"].$config["li_images"]; ?>footer.jpg" width="630" height="117" alt=""></a></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="630" height="20">
                                                                                                    </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="630" height="15" style="text-align: center;"><a href="http://www.finances-pedagogie.fr/lettreinfo/_desinscription.php<?php $content1 = ob_get_clean(); ob_start(); ?>" style="color:#000;">Se désabonner de la Newsletter / Unsubscribe to Newsletter</a></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td width="630" height="19"></td>
                                                                                            </tr>
                                                                                        
                                                                                    </table>
                                                                                </td>
                                                                            </tr><!-- fin pied de page -->



                                                                            
                                                                            </table><!-- main content -->
                                                                            </td><!-- align center -->
                                                                            </tr>

                                                                            
                                                                            </table>  <!-- width 100% -->



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
