<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");

//Récupération de l'ID
if (isset($_GET["uniq_id"])) {
    $uniq_id = $_GET["uniq_id"];
}
if (isset($_POST["uniq_id"])) {
    $uniq_id = $_POST["uniq_id"];
}

// Chercher l'enregistrement correspondant à l'ID
// Récupération des données pour ce uniq_id
$tableau_classe = liste_li("","uniq_id='".$uniq_id."'","","1");


// Traitement du formulaire envoyé ou non
if( isset($_POST["form_send"]) && $_POST["form_send"] == 1 )
{
	// Formulaire envoyé
	include("lettredinformation-edit.inc.php");
	if ($actif_li > 0) {
        include("lettredinformation-html-create.php");
    }
    ob_start();
	if($bdderror["bdd"]["etat"] == TRUE) {
		?>
    <p class="titre">Confirmation de la modification de la lettre d'information n°<?php echo $numero; ?></p>
    <p>La lettre d'information a été modifiée.<br />Vous pouvez <a href="lettredinformation.php" title="Retour à la liste des lettres d'information">retourner à la liste des lettres d'information</a>.</p>
    <p class="erreur"><?php                        foreach ($varerror as $key => $value) {
                            if ($value["etat"] == FALSE) {
                        echo $value["texte"] . "<br />";
                    }
                }
                ?></p>
    <p class="erreur"><?php                foreach ($alu_imgerror as $key => $value) {
                    if ($value["etat"] == FALSE) {
                        echo $value["texte"] . "<br />";
                    }
                }
                ?></p>
    <p class="erreur"><?php                foreach ($am_imgerror as $key => $value) {
                    if ($value["etat"] == FALSE) {
                        echo $value["texte"] . "<br />";
                    }
                }
                ?></p>
    <?php
	}
	else
	{
		?>
    <p class="titre">Echec de la modification de la lettre d'information n°<?php echo $numero; ?></p>
    <p>La lettre d'information n'a pas été modifiée.</p>
    <p class="erreur"><?php echo $bdderror["bdd"]["texte"]; ?></p>
    <p><?php echo $config["contact-administrateur"]; ?></p>
    <?php
	}
}
else
{
	ob_start();
	$data_unseries = unserialize($tableau_classe[0]["dataseries"]);
	// Formulaire non envoyé
	?>
    <p class="titre">Modification de la lettre d'information n°<?php echo $tableau_classe[0]["numero"]; ?></p>
	<form name="formulaire" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" class="formulaire">
    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
    <input type="hidden" name="uniq_id" value="<?php echo $uniq_id; ?>" />
    <div id="accordion">
    <h2><a href="#">En-tête de la lettre d'information</a></h2>
    <div>
        <label for="numero">Numéro de la lettre d'information</label>
       <select name="numero">
       	<?php if($tableau_classe[0]["numero"]-2 > 0) { ?><option value="<?php echo $tableau_classe[0]["numero"]-2; ?>"><?php echo $tableau_classe[0]["numero"]-2; ?></option><?php } ?>
        <?php if($tableau_classe[0]["numero"]-1 > 0) { ?><option value="<?php echo $tableau_classe[0]["numero"]-1; ?>"><?php echo $tableau_classe[0]["numero"]-1; ?></option><?php } ?>
        <option value="<?php echo $tableau_classe[0]["numero"]; ?>" selected="selected"><?php echo $tableau_classe[0]["numero"]; ?></option>
        <option value="<?php echo $tableau_classe[0]["numero"]+1; ?>"><?php echo $tableau_classe[0]["numero"]+1; ?></option>
        <option value="<?php echo $tableau_classe[0]["numero"]+2; ?>"><?php echo $tableau_classe[0]["numero"]+2; ?></option>
        <option value="<?php echo $tableau_classe[0]["numero"]+3; ?>"><?php echo $tableau_classe[0]["numero"]+3; ?></option>
       </select>
        
        <input type="hidden" name="li_titre" id="li_titre" value="La lettre d'information de Finances & Pédagogie" />
       <label for="mois">Mois de la lettre d'information</label>
       <select name="mois">
           <option value="Janvier" <?php                   if ($data_unseries["mois"] == "Janvier") {
                   echo "selected='selected'";
               }
               ?>>Janvier</option>
           <option value="Février" <?php               if ($data_unseries["mois"] == "Février") {
                   echo "selected='selected'";
               }
               ?>>Février</option>
           <option value="Mars" <?php               if ($data_unseries["mois"] == "Mars") {
                   echo "selected='selected'";
               }
               ?>>Mars</option>
           <option value="Avril" <?php               if ($data_unseries["mois"] == "Avril") {
                   echo "selected='selected'";
               }
               ?>>Avril</option>
           <option value="Mai" <?php               if ($data_unseries["mois"] == "Mai") {
                   echo "selected='selected'";
               }
               ?>>Mai</option>
           <option value="Juin" <?php               if ($data_unseries["mois"] == "Juin") {
                   echo "selected='selected'";
               }
               ?>>Juin</option>
           <option value="Juillet" <?php               if ($data_unseries["mois"] == "Juillet") {
                   echo "selected='selected'";
               }
               ?>>Juillet</option>
           <option value="Août" <?php               if ($data_unseries["mois"] == "Août") {
                   echo "selected='selected'";
               }
               ?>>Août</option>
           <option value="Septembre" <?php               if ($data_unseries["mois"] == "Septembre") {
                   echo "selected='selected'";
               }
               ?>>Septembre</option>
           <option value="Octobre" <?php               if ($data_unseries["mois"] == "Octobre") {
                   echo "selected='selected'";
               }
               ?>>Octobre</option>
           <option value="Novembre" <?php               if ($data_unseries["mois"] == "Novembre") {
                   echo "selected='selected'";
               }
               ?>>Novembre</option>
           <option value="Décembre" <?php               if ($data_unseries["mois"] == "Décembre") {
                   echo "selected='selected'";
               }
               ?>>Décembre</option>
        </select>
        <label for="annee">Année de la lettre d'information</label>
        <select name="annee">
		   	<option value="<?php echo $data_unseries["annee"]-1; ?>"><?php echo $data_unseries["annee"]-1; ?></option>
            <option value="<?php echo $data_unseries["annee"]; ?>" selected="selected"><?php echo $data_unseries["annee"]; ?></option>
            <option value="<?php echo $data_unseries["annee"]+1; ?>"><?php echo $data_unseries["annee"]+1; ?></option>
        </select>
        <label for="lienfrancais">Adresse http du PDF en français</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/xxxxxxxxxxx.pdf)</span>
        <input type="text" name="lienfrancais" value="<?php echo $tableau_classe[0]["lienpdffr"]; ?>" />
       
        <label for="li_chapeau">Edito</label>
        <textarea name="li_chapeau" id="li_chapeau" class="chapeau"><?php echo stripslashes($data_unseries["li_chapeau"]); ?></textarea>
    </div>
    <h2><a href="#">Rubrique: A la une</a></h2>
    <div>
        <label for="alu_titre">Titre</label>
        <input type="text" name="alu_titre" value="<?php echo stripslashes($data_unseries["alu_titre"]); ?>" />
        <label for="alu_chapeau">Chap&ocirc;</label>
        <textarea name="alu_chapeau" id="alu_chapeau" class="chapeau"><?php echo stripslashes($data_unseries["alu_chapeau"]); ?></textarea>
        <label for="alu_article">Contenu de l'article</label>
        <textarea name="alu_article" id="alu_article"><?php echo stripslashes($data_unseries["alu_article"]); ?></textarea>
        <?php if($data_unseries["alu_image"]<>"") { ?>
        	<label for="alu_actuelle">Image actuelle: </label><img src="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["alu_image"]); ?>" height="80" name="alu_actuelle" />
            <input type="hidden" name="alu_image_old" value="<?php echo stripslashes($data_unseries["alu_image"]); ?>" />
            <label for="actif">Supprimer l'image actuelle</label>
            <input type="checkbox" name="alu_suppr" id="alu_suppr" value="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["alu_image"]); ?>" />
        <?php } ?>
        <label for="alu_image">Associer une nouvelle image</label>
        <span>(L'image ne doit pas dépasser une taille de 1Mo; les extensions autorisées sont: gif, png, jpg ou jpeg)</span>
        <input type="file" name="alu_image" />
        <label for="alu_lien">Lien http associé</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/)</span>
        <input type="text" name="alu_lien" value="<?php echo stripslashes($data_unseries["alu_lien"]); ?>" />
         <label for="alu_intitule_lien">Légende</label>
        <input type="text" name="alu_intitule_lien" value="<?php echo stripslashes($data_unseries["alu_intitule_lien"]); ?>" />
    </div>
    <h2><a href="#">Rubrique: Zoom</a></h2>
    <div>
        <label for="z_titre">Titre</label>
        <input type="text" name="z_titre" value="<?php echo stripslashes($data_unseries["z_titre"]); ?>" />
       <label for="z_article">Contenu de l'article</label>
         <textarea name="z_article" id="z_article"><?php echo stripslashes($data_unseries["z_article"]); ?></textarea>
    </div>
    <h2><a href="#">Rubrique: Arrêt métier</a></h2>
    <div>
        <label for="am_titre">Titre</label>
        <input type="text" name="am_titre" value="<?php echo stripslashes($data_unseries["am_titre"]); ?>" />
        <label for="am_chapeau">Chap&ocirc;</label>
        <textarea name="am_chapeau" id="am_chapeau" class="chapeau"><?php echo stripslashes($data_unseries["am_chapeau"]); ?></textarea>
        <label for="am_article">Contenu de l'article</label>
        <textarea name="am_article" id="am_article"><?php echo stripslashes($data_unseries["am_article"]); ?></textarea>
        <?php if($data_unseries["am_image"]<>"") {?>
        	<label for="am_actuelle">Image actuelle:</label> <img src="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["am_image"]); ?>" height="80" name="am_actuelle" />
			<input type="hidden" name="am_image_old" value="<?php echo stripslashes($data_unseries["am_image"]); ?>" />
            <label for="actif">Supprimer l'image actuelle</label>
            <input type="checkbox" name="am_suppr" id="z_suppr" value="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["am_image"]); ?>" />
		<?php } ?>
        <label for="am_image">Associer une nouvelle image</label>
        <span>(L'image ne doit pas dépasser une taille de 1Mo; les extensions autorisées sont: gif, png, jpg ou jpeg)</span>
        <input type="file" name="am_image" />
         <label for="am_lien">Lien http associé</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/)</span>
        <input type="text" name="am_lien" value="<?php echo stripslashes($data_unseries["am_lien"]); ?>" />
         <label for="am_intitule_lien">Descriptif du lien</label>
        <input type="text" name="am_intitule_lien" value="<?php echo stripslashes($data_unseries["am_intitule_lien"]); ?>" />
    </div>
    <h2><a href="#">Rubrique: Notre partenaire</a></h2>
    <div>
        <label for="no_titre">Titre</label>
        <input type="text" name="no_titre" value="<?php echo stripslashes($data_unseries["no_titre"]); ?>" />
       <label for="no_article">Contenu de l'article</label>
         <textarea name="no_article" id="no_article"><?php echo stripslashes($data_unseries["no_article"]); ?></textarea>
    </div>
    <h2><a href="#">Rubrique: Actualités</a></h2>
    <div>
        <label for="act1_titre">Actualit&eacute; 1 - Titre</label>
        <input type="text" name="act1_titre" value="<?php echo stripslashes($data_unseries["act1_titre"]); ?>" />
        <label for="act1_article">Actualit&eacute; 1 - Contenu de l'article</label>
        <textarea name="act1_article" id="act1_article"><?php echo stripslashes($data_unseries["act1_article"]); ?></textarea>
        <?php if($data_unseries["act1_image"]<>"") {?>
        	<label for="act1_actuelle">Image actuelle:</label> <img src="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["act1_image"]); ?>" height="80" name="act1_actuelle" />
			<input type="hidden" name="act1_image_old" value="<?php echo stripslashes($data_unseries["act1_image"]); ?>" />
            <label for="actif">Supprimer l'image actuelle</label>
            <input type="checkbox" name="act1_suppr" id="act1_suppr" value="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["act1_image"]); ?>" />
		<?php } ?>
        <label for="act1_image">Associer une nouvelle image</label>
        <span>(L'image ne doit pas dépasser une taille de 1Mo; les extensions autorisées sont: gif, png, jpg ou jpeg)</span>
        <input type="file" name="act1_image" />
        <br>
        <label for="act2_titre">Actualit&eacute; 2 - Titre</label>
        <input type="text" name="act2_titre" value="<?php echo stripslashes($data_unseries["act2_titre"]); ?>" />
        <label for="act2_article">Actualit&eacute; 2 - Contenu de l'article</label>
        <textarea name="act2_article" id="act2_article"><?php echo stripslashes($data_unseries["act2_article"]); ?></textarea>
        <?php if($data_unseries["act2_image"]<>"") {?>
        	<label for="act2_actuelle">Image actuelle:</label> <img src="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["act2_image"]); ?>" height="80" name="act2_actuelle" />
			<input type="hidden" name="act2_image_old" value="<?php echo stripslashes($data_unseries["act2_image"]); ?>" />
            <label for="actif">Supprimer l'image actuelle</label>
            <input type="checkbox" name="act2_suppr" id="act2_suppr" value="<?php echo "../".stripslashes($data_unseries["image_path"]. $data_unseries["act2_image"]); ?>" />
		<?php } ?>
        <label for="act2_image">Associer une nouvelle image</label>
        <span>(L'image ne doit pas dépasser une taille de 1Mo; les extensions autorisées sont: gif, png, jpg ou jpeg)</span>
        <input type="file" name="act2_image" />
    </div>
    <h2><a href="#">Rubrique: Autres titres</a></h2>
    <div class="input_fields_wrap">
     <button class="add_field_button">Ajouter un titre</button>
     <?php $at_titres = unserialize($data_unseries['at_titres']);
     foreach($at_titres as $at_titre) {
         $at_titres_counter++;
     ?>
     <div><input type="text" name="mytext[]" value="<?php echo $at_titre; ?>"><a href="#" class="remove_field">Supprimer</a></div>
     <?php } ?>
    </div>
    <h3><a href="#">Paramètres d'envoi</a></h2>
    <div>
          <label for="actif">Activation de la lettre d'information</label>
          <span>(Attention, si vous cochez cette case, la lettre d'information sera envoyée à partir de la date spécifiée; si vous ne spécifiez aucune date, les envois démarreront immédiatemment)</span>
          <input type="checkbox" name="actif_li" id="actif_li" value="<?php           if ($tableau_classe[0]["actif_li"] <> -2) {
                  echo "1";
              }
              ?>" <?php          if ($tableau_classe[0]["actif_li"] > 0) {
              echo "checked='checked'";
          }
          ?> />
          <input type="hidden" name="actif_li_old" value="<?php echo $tableau_classe[0]["actif_li"]; ?>" />
          <label for="dateactivationenvoi">Date d'activation de l'envoi</label>
          <span>(Vous pouvez spécifier une date sans valider la lettre d'information; dans ce cas, tant que la lettre d'information ne sera pas validée via la case à cocher ci-dessus, aucun envoi ne sera effectué)</span>
           <span>(Format de la date d'envoi: JJ/MM/AAAA)</span>
	      <input type="text" name="dateactivationenvoi" id="dateactivationenvoi" value="<?php echo datetime_to_fr($tableau_classe[0]["dateactivationenvoi"]); ?>" />
        </div>
        <input type="hidden" name="form_send" value="1" />
        </div>
        <input type="submit" name="ajouter" id="ajouter" value="Appliquer les modifications" />
        <input type="reset" name="annuler" id="annuler" value="Annuler les modifications" />
	</form>
    <?php
}
$content = ob_get_clean();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title><?php echo $config["html-title"]?>Edition d'un abonné</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.8.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="libraries/jquery.min.js"></script>
<script type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="libraries/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
     var max_fields      = <?php echo 9-$at_titres_counter;?>; //maximum input boxes allowed
     var wrapper         = $(".input_fields_wrap"); //Fields wrapper
     var add_button      = $(".add_field_button"); //Add button ID
     
     var x = 1; //initlal text box count
     $(add_button).click(function(e){ //on add input button click
         e.preventDefault();
         if(x < max_fields){ //max input box allowed
             x++; //text box increment
             $(wrapper).append('<div><input type="text" name="mytext[]"/><a href="#" class="remove_field">Supprimer</a></div>'); //add input box
         }
     });
     
     $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
         e.preventDefault(); $(this).parent('div').remove(); x--;
     })
});
$(function() {
		$( "#accordion" ).accordion({
			autoHeight: false,
			collapsible: true,
			active: false
		});
	});
</script>
<script>
	$(function() {
		$( "#dateactivationenvoi" ).datepicker();
	});
	</script>

<!-- TinyMCE -->
<script type="text/javascript" src="libraries/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "exact",
		elements: "li_chapeau, alu_chapeau, am_chapeau, z_article, no_article, at_article, ml_article",
		theme : "advanced",
		width: "500",
		height: "180",
		theme_advanced_buttons1: "bold, italic, underline, |, bullist",
		theme_advanced_buttons2 :"",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center"
	});
</script>
<script type="text/javascript">
    

	tinyMCE.init({
		mode : "exact",
		elements: "act1_article, act2_article, alu_article, am_article",
		theme : "advanced",
		width: "500",
		height: "180",
		theme_advanced_blockformats: "p,h1,h2,h3",
		theme_advanced_buttons1: "bold, italic, underline, |, formatselect",
		theme_advanced_buttons2 :"",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center"
	});
</script>
<!-- /TinyMCE -->
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
    <?php echo $content; ?>
    </div>
<div id="menu">
	<ul>
        <li><a href="lettredinformation.php" title="Retour à la liste des lettres d'information"><img src="medias/icones/retour.png" height="64" alt="Retour à la liste des lettres d'information" /></a></li>
        <li><a href="lettredinformation-html-create.php?uniq_id=<?php echo $uniq_id; ?>" target="_blank" title="Afficher la lettre d'information"><img src="medias/icones/news-visu.png" height="64" alt="Visualiser la lettre d'information" /></a></li>
        <!-- <li><a href="lettredinformation-print.php" title="Imprimer la lettre d'information"><img src="medias/icones/news-print.png" height="64" alt="Imprimer la lettre d'information" /></a></li> -->
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