<?php
// Vérification de l'accès:
session_start();
isset($_SESSION["CONNEXION_VALIDE"]) or header("location: http://".$_SERVER['HTTP_HOST'].$config["root"]);
include("libraries/send_head.inc.php");
// Connexion base
require("libraries/secure/config.req.php");
require("libraries/secure/bdconnect.req.php");
require("libraries/secure/fonctions.req.php");


// Rechercher le dernier numéro
$request_string = "SELECT numero FROM lettredinformation ORDER BY numero DESC, id_li DESC LIMIT 1";
$request = mysql_query( $request_string );
if($request<>FALSE) {
	$resultat = mysql_fetch_array( $request );
	$numerosuivant = $resultat["numero"]+1;
}


// Traitement du formulaire envoyé ou non
if(filter_input(INPUT_POST, 'form_send') == 1 )
{
	// Formulaire envoyé
	include("lettredinformation-add.inc.php");
	if(isset($bdderror["bdd"]["uniq_id"]) && $actif_li > 0 ) {
		$uniq_id = $bdderror["bdd"]["uniq_id"];
		include("lettredinformation-html-create.php");
	}
	ob_start();
	if($bdderror["bdd"]["etat"] == TRUE) {
		?>
    <p class="titre">Confirmation de l'ajout de la lettre d'information n°<?php echo $numero; ?></p>
    <p>La lettre d'information a été ajoutée.</p>
    <p class="erreur"><?php foreach($varerror as $key => $value) { 
                                if($value["etat"] === FALSE) { echo $value["texte"]."<br />"; } 
                                
                            } ?></p>
    <p class="erreur"><?php foreach($alu_imgerror as $key => $value) if($value["etat"] == FALSE) echo $value["texte"]."<br />"; ?></p>
    <p class="erreur"><?php foreach($z_imgerror as $key => $value) if($value["etat"] == FALSE) echo $value["texte"]."<br />"; ?></p>
    <?php
	}
	else
	{
		?>
    <p class="titre">Echec de l'ajout de la lettre d'information n°<?php echo $numero; ?></p>
    <p>La lettre d'information n'a pas  été ajoutée. L'opération a échoué.</p>
    <p class="erreur"><?php echo $bdderror["bdd"]["texte"]; ?></p>
    <p><?php echo $config["contact-administrateur"]; ?></p>
    <?php
	}
}
else
{
	// Formulaire non envoyé
	ob_start();
	?>
    <p class="titre">Ajout d'une nouvelle lettre d'information</p>
    
	<form name="formulaire" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" class="formulaire" id="formulaire">
    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
    <div id="accordion">
    <h2><a href="#">En-tête de la lettre d'information</a></h2>
    <div>
       <label for="numero">Numéro de la lettre d'information</label>
       <select name="numero">
       	<option value="<?php echo $numerosuivant; ?>"><?php echo $numerosuivant; ?></option>
        <option value="<?php echo $numerosuivant+1; ?>"><?php echo $numerosuivant+1; ?></option>
        <option value="<?php echo $numerosuivant+2; ?>"><?php echo $numerosuivant+2; ?></option>
         <option value="<?php echo $numerosuivant+3; ?>"><?php echo $numerosuivant+3; ?></option>
       </select>
       <input type="hidden" name="li_titre" id="li_titre" value="La lettre d'information de Finances & Pédagogie"/>
       <label for="mois">Mois de la lettre d'information</label>
       <select name="mois">
            <option>Janvier</option>
            <option>Février</option>
            <option>Mars</option>
            <option>Avril</option>
            <option>Mai</option>
            <option>Juin</option>
            <option>Juillet</option>
            <option>Août</option>
            <option>Septembre</option>
            <option>Octobre</option>
            <option>Novembre</option>
            <option>Décembre</option>
        </select>
        <label for="annee">Année de la lettre d'information</label>
        <select name="annee">
        <?php $year = date("Y"); ?>
		   	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <option value="<?php echo $year+1; ?>"><?php echo $year+1; ?></option>
            <option value="<?php echo $year+2; ?>"><?php echo $year+2; ?></option>
        </select>
        <label for="lienfrancais">Adresse http du PDF en français</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/xxxxxxxxxxx.pdf)</span>
        <input type="text" name="lienfrancais">
        <label for="li_chapeau">Edito</label>
        <textarea name="li_chapeau" id="li_chapeau" class="chapeau"></textarea>
    </div>
    <h3><a href="#">Rubrique: A la une</a></h3>
    <div>
        <label for="alu_titre">Titre</label>
        <input type="text" name="alu_titre">
        <label for="alu_chapeau">Chap&ocirc;</label>
        <textarea name="alu_chapeau" id="alu_chapeau" class="chapeau"></textarea>
        <label for="alu_article">Contenu de l'article</label>
        <textarea name="alu_article" id="alu_article"></textarea>
        <label for="alu_image">Image associée</label>
        <span>(L'image ne doit pas dépasser une taille de 1Mo; les extensions autorisées sont: gif, png, jpg ou jpeg)</span>
        <input type="file" name="alu_image">
        <label for="alu_lien">Lien http associé</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/)</span>
        <input type="text" name="alu_lien">
         <label for="alu_intitule_lien">Descriptif du lien</label>
        <input type="text" name="alu_intitule_lien">
    </div>
    <h3><a href="#">Rubrique: Zoom</a></h3>
    <div>
        <label for="z_titre">Titre</label>
        <input type="text" name="z_titre">
       <label for="z_article">Contenu de l'article</label>
         <textarea name="z_article" id="z_article"></textarea>
        <label for="am_image">Image associée</label>
        <span>(L'image ne doit pas dépasser une taille de 1Mo; les extensions autorisées sont: gif, png, jpg ou jpeg)</span>
        <input type="file" name="am_image">
         <label for="z_lien">Lien http associé</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/)</span>
        <input type="text" name="z_lien">
        <label for="z_intitule_lien">Descriptif du lien</label>
        <input type="text" name="z_intitule_lien">
     </div>
    <h3><a href="#">Rubrique: Arrêt métier</a></h3>
    <div>
        <label for="am_titre">Titre</label>
        <input type="text" name="am_titre">
        <label for="am_chapeau">Chap&ocirc;</label>
        <textarea name="am_chapeau" id="am_chapeau" class="chapeau"></textarea>
        <label for="am_article">Contenu de l'article</label>
        <textarea name="am_article" id="am_article"></textarea>
         <label for="am_lien">Lien http associé</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/)</span>
        <input type="text" name="am_lien">
         <label for="am_intitule_lien">Descriptif du lien</label>
        <input type="text" name="am_intitule_lien">
     </div>
    <h3><a href="#">Rubrique: Nos partenaires</a></h3>
    <div>
        <label for="no_titre">Titre</label>
        <input type="text" name="no_titre">
        <label for="no_chapeau">Chap&ocirc;</label>
        <textarea name="no_chapeau" id="no_chapeau" class="chapeau"></textarea>
        <label for="no_article">Contenu de l'article</label>
        <textarea name="no_article" id="no_article"></textarea>
    </div>
    <h3><a href="#">Rubrique: Actualités</a></h3>
    <div>
        <label for="act1_titre">Actualit&eacute; 1 - Titre</label>
        <input type="text" name="act1_titre">
        <label for="act1_article">Actualit&eacute; 1 - Contenu de l'article</label>
        <textarea name="act1_article" id="act1_article"></textarea>
        <label for="act1_lien">Actualit&eacute; 1 - Lien http associé</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/)</span>
        <input type="text" name="act1_lien">
        <label for="act1_intitule_lien">Actualit&eacute; 1 - Descriptif du lien</label>
        <input type="text" name="act1_intitule_lien">
        <label for="act2_titre">Actualit&eacute; 2 - Titre</label>
        <input type="text" name="act2_titre">
        <label for="act2_article">Actualit&eacute; 2 - Contenu de l'article</label>
        <textarea name="act2_article" id="act2_article"></textarea>
        <label for="act2_lien">Actualit&eacute; 2 - Lien http associé</label>
        <span>(Adresse complète sous la forme http://www.finances-pedagogie.fr/lettredinformation/)</span>
        <input type="text" name="act2_lien">
        <label for="act2_intitule_lien">Actualit&eacute; 2 - Descriptif du lien</label>
        <input type="text" name="act2_intitule_lien">
     </div>
    <h3><a href="#">Rubrique: Autres titres</a></h3>
    <div>
        <label for="at_article">Liste des autres titres</label>
         <textarea name="at_article"></textarea>
     </div>
     <h3><a href="#">Rubrique: Mentions légales</a></h3>
    <div>
        <label for="ml_article">Liste des autres titres</label>
         <textarea name="ml_article"></textarea>
     </div>
    <h3><a href="#">Paramètres d'envoi</a></h3>
    <div>
          <label for="actif">Activation de la lettre d'information</label>
          <span>(Attention, si vous cochez cette case, la lettre d'information sera envoyée à partir de la date spécifiée; si vous ne spécifiez aucune date, les envois démarreront immédiatemment)</span>
	   	  <input type="checkbox" name="actif_li" id="actif_li" value="1" />
          <label for="dateactivationenvoi">Date d'activation de l'envoi</label>
          <span>(Vous pouvez spécifier une date sans valider la lettre d'information; dans ce cas, tant que la lettre d'information ne sera pas validée via la case à cocher ci-dessus, aucun envoi ne sera effectué)</span>
           <span>(Format de la date d'envoi: JJ/MM/AAAA)</span>
	      <input type="text" name="dateactivationenvoi" id="dateactivationenvoi" value="" />
        </div>
        <input type="hidden" name="form_send" value="1" />
       </div> 
        <input type="submit" name="ajouter" id="ajouter" value="Ajouter" />
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
<title><?php echo $config["html-title"]?>Edition d'une lettre d'information</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.8.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="libraries/jquery.min.js"></script>
<script type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="libraries/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript">
$(function() {
		$( "#accordion" ).accordion({
			autoHeight: false
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
		elements: "li_chapeau, alu_chapeau, alu_article, am_chapeau, am_article, z_article, no_article, at_article, ml_article",
		theme : "advanced",
		width: "500",
		height: "180",
		theme_advanced_buttons1: "bold, italic, underline",
		theme_advanced_buttons2 :"",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center"
	});
</script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "exact",
		elements: "act1_article",
		theme : "advanced",
		width: "500",
		height: "180",
		theme_advanced_styles: "Question=question",
		theme_advanced_buttons1: "bold, italic, underline, |, styleselect",
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
        <li><a href="lettredinformation-visu.php" title="Visualiser la lettre d'information"><img src="medias/icones/news-visu.png" height="64" alt="Visualiser la lettre d'information" /></a></li>
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
