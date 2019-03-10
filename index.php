<!doctype html>
<html lang="fr">
<?php
session_start();
include ("conf/config.inc.php");

//If the HTTPS is not found to be "on"
if((!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")&&$forceSSL=="yes")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}

$page = $_GET['page'];
if (!$_GET['page']) $page="Books";

include ("libs/libs.php");
include ("libs/opsql.php");

?>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Collection privée de livres électroniques">
    <meta name="author" content="Grib8s">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="favicon.ico">

    <title>EbooX - Collection privée de livres électroniques</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- EbookX core CSS -->
    <link href="css/ebooks.css" rel="stylesheet">
    <!-- Fontawesome -->
    <link href="css/FA-all.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">
    <!-- Custom styles for upload -->
    <link rel="stylesheet" href="css/upload.css">
  </head>

  <body style="background-color:#EEEEEE">
	<?php
	if (!$_SESSION['user']) {
		//del_empty_folder ('Books'); 
		include('pages/login.php');
	}
	else 
	{
	?>
    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      	<div class="container">
        <a class="navbar-brand" href="/" title="Accueil"><b>EbooX</b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Classement">
          <i class="fa fa-list-ul"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="?page=<?php echo $page; ?>&class=t"><?php if ($eboox_class=="titre") echo "<i class=\"fas fa-check\"></i> "; ?>Titre</a>
          <a class="dropdown-item" href="?page=<?php echo $page; ?>&class=at"><?php if ($eboox_class=="auteur") echo "<i class=\"fas fa-check\"></i> "; ?>Auteur</a>
          <a class="dropdown-item" href="?page=<?php echo $page; ?>&class=a"><?php if ($eboox_class=="aleatoire") echo "<i class=\"fas fa-check\"></i> "; ?>Aléatoire</a>
          <a class="dropdown-item" href="?page=<?php echo $page; ?>&class=n"><?php if ($eboox_class=="nouveautee") echo "<i class=\"fas fa-check\"></i> "; ?>Nouveautées</a>
        </div>
      </li>
            <li class="nav-item">
              <a class="nav-link" href="/" title="Livres"><i class="fa fa-book"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?page=Favoris" title="Favoris"><i class="fa fa-star"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?page=Auteurs" title="Auteurs"><i class="fas fa-pen-nib"></i></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?page=Profils" title="Utilisateurs"><i class="fa fa-users"></i></a>
            </li>
            <?php if ($user['type']=="admin"||$user['type']=="contributeur") { ?>
            <li class="nav-item">
              <a class="nav-link" href="?page=Upload" title="Uploader des livres"><i class="fa fa-folder-plus"></i></a>
            </li>
            <?php } ?>
            <?php if ($user['type']=="admin") { ?>
            <li class="nav-item">
              <a class="nav-link" href="?page=Outils" title="Gérer le bibliothèque"><i class="fa fa-tools"></i></a>
            </li>
            <?php } ?>
            <li class="nav-item">
              <a class="nav-link" href="?logout=1" title="Se déconnecter"><i class="fa fa-sign-out-alt"></i></a>
            </li>          
            </ul>
          <form class="form-inline mt-2 mt-md-0" method="POST" action="/">
            <input name="searchbook" class="form-control input-sm" type="text" placeholder="Recherche" aria-label="Recherche">
          </form>
        </div>
        </div>
      </nav>
    </header>

    <!-- Begin page content-->
    <main role="main" class="container">
	<?php
	if ($page=="Books") include("pages/Books.php");
	if ($page=="Auteurs") include("pages/Auteurs.php");
	if ($page=="Upload"&&($user['type']=="admin"||$user['type']=="contributeur")) include("pages/Upload.php");
	if ($page=="Profils") include("pages/Profils.php");
	if ($page=="Favoris") include("pages/Favoris.php");
	if ($page=="Outils"&&$user['type']=="admin") include("pages/Outils.php");
	//if ($page=="ModifBook"&&$user['type']=="admin") include("pages/ModifBook.php");
	?>
        
    </main>

    <footer class="footer">
      <div class="container">
      	<?php
      		$querytot="SELECT * FROM ".$mysql_prefix."ebooks;";
	//echo $querym;
	$qtot = $pdo->prepare($querytot);
	$qtot->execute();
    $totalbooks = $qtot->rowCount();
      	?>
        <span class="text-muted"><b>EbooX</b> - <i>Collection privée de livres électroniques. (<?php echo $totalbooks; ?> livres)</i></span>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jQuery.js"></script>
    <!--<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>-->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
	<script src="js/jquery.ui.widget.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
	<script src="js/upload.js"></script>

	<script>
	$(function () {
	    'use strict';
	    $('#fileupload').fileupload({
	        //url: url,
	        dataType: 'json',
	        done: function (e, data) {
	            $.each(data.result.files, function (index, file) {
	            	var cuurl = 'pages/majbiblio.php?user=<?php echo $user['id']; ?>&path=<?php echo rand(); ?>&fileproc=' + encodeURIComponent(file.name) + '';
	                //$('<p/>').text(cuurl).appendTo('#files');
	                $("#addedbooks").load(cuurl);
	            });
	        },
	        progressall: function (e, data) {
	            var progress = parseInt(data.loaded / data.total * 100, 10);
	            $('#progress .progress-bar').css(
	                'width',
	                progress + '%'
	            );
	        },
	        stop: function (e) {
			    $('<p/>').text('Upload terminé, veuillez attendre le défilement des livres...').appendTo('#files');
			}
	    })
	});
	</script>
	<?php } ?>
  </body>
</html>
