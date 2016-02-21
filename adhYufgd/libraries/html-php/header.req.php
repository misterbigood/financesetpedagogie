<?php
// Tests de sécurité sur la page
	define("CONNEXION_VALIDE",1);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Robots" content="noindex, nofollow" />
<meta name="Copyright" content="MDFConseil, Marquedefabrique 2016" />
<meta name="Language" content="fr" />
<title>Lettre information - Administration - Tableau de bord</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/visualize.css" rel="stylesheet" type="text/css" />
<link href="css/table.css" rel="stylesheet" type="text/css" />
<script  type="text/javascript" src="libraries/jquery.min.js"></script>
<script  type="text/javascript" src="libraries/jquery-ui-1.8.16.custom.min.js"></script>
<script  type="text/javascript" src="libraries/visualize.jQuery.js"></script>
<script type="text/javascript" src="libraries/jquery.dataTables.min.js"></script>
<script type="text/javascript">

 $(document).ready(function(){
	 	$( "#organisable" ).sortable();
		$( "#organisable" ).disableSelection();
		
		$("table.stats").visualize({ type: 'line', lineWeight: 2, appendTitle: false, height: 300 });
 });
</script>
</head>