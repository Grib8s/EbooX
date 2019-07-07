<!doctype html>
<html lang="fr" style="overflow-y: scroll;">
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

  <body>
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
      <!--<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-list-ul" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Classement</em>"></i><em class="d-md-none"> Classement</em>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="?page=< ?php echo $page; ?>&class=t">< ?php if ($eboox_class=="titre") echo "<i class=\"fas fa-check\"></i> "; ?><em>Titre</em></a>
          <a class="dropdown-item" href="?page=< ?php echo $page; ?>&class=at">< ?php if ($eboox_class=="auteur") echo "<i class=\"fas fa-check\"></i> "; ?><em>Auteur</em></a>
          <a class="dropdown-item" href="?page=< ?php echo $page; ?>&class=a">< ?php if ($eboox_class=="aleatoire") echo "<i class=\"fas fa-check\"></i> "; ?><em>Aléatoire</em></a>
          <a class="dropdown-item" href="?page=< ?php echo $page; ?>&class=n">< ?php if ($eboox_class=="nouveautee") echo "<i class=\"fas fa-check\"></i> "; ?><em>Nouveautées</em></a>
        </div>
      </li>-->
            <li class="nav-item">
              <a class="nav-link<?php if ($page=="Books") echo " active";?>" href="/"><i class="fa fa-book" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Livres</em>"></i><em class="d-md-none"> Livres</em></a>
            </li>
            <!--<li class="nav-item">
              <a class="nav-link" href="?page=Favoris"><i class="fa fa-star" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Recommandations</em>"></i><em class="d-md-none"> Recommandations</em></a>
            </li>-->
            <li class="nav-item">
              <a class="nav-link<?php if ($page=="Auteurs") echo " active";?>" href="?page=Auteurs"><i class="fas fa-pen-nib" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Auteurs</em>"></i><em class="d-md-none"> Auteurs</em></a>
            </li>
            
            <!--<li class="nav-item">
            <a class="nav-link" id="toggleslide" data-toggle="collapse" href="#collapseslide" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="far fa-images" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Slide</em>"></i><em class="d-md-none"> Slide</em></a>
            </li>-->
            
            
            <li class="nav-item">
              <a class="nav-link<?php if ($page=="Profils") echo " active";?>" href="?page=Profils"><i class="fa fa-users" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Utilisateurs</em>"></i><em class="d-md-none"> Utilisateurs</em></a>
            </li>
            <?php if ($user['type']=="admin"||$user['type']=="contributeur") { ?>
            <li class="nav-item">
              <a class="nav-link<?php if ($page=="Upload") echo " active";?>" href="?page=Upload"><i class="fa fa-book-medical" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Ajout de livres</em>"></i><em class="d-md-none"> Ajout de livres</em></a>
            </li>
            <?php } ?>
            <?php if ($user['type']=="admin") { ?>
            <li class="nav-item">
              <a class="nav-link<?php if ($page=="Outils") echo " active";?>" href="?page=Outils"><i class="fa fa-tools" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Entretien</em>"></i><em class="d-md-none"> Entretien</em></a>
            </li>
            <?php } ?>
         
            </ul>
          <!--<form class="form-inline mt-2 mt-md-0" method="POST" action="/">
            <input name="searchbook" class="form-control input-sm" type="text" placeholder="Recherche" aria-label="Recherche" value="< ?php //echo $search; ?>">
          </form>-->
          
          <ul class="navbar-nav ml-auto">
          <?php 
          $image ="https://www.gravatar.com/avatar/".md5($user['email'])."?s=200&d=".urlencode("https://".$_SERVER['SERVER_NAME']."/images/".$user['type'].".jpg")."";
        	echo "<li class=\"profilclass dropdown mt-2 mt-md-0 nav-item\">
				            <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdownMenuLink\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
				            <em><b>".$user['nick']."</b></em>
				            </a>
				        
                            <div class=\"dropdown-menu dropdown-menu-right w-100\" aria-labelledby=\"navbarDropdownMenuLink\">";
	        					showuser($user['nick'],$user['email'],$user['type'],$user['id'],$user['valid'],$pdo,$mysql_prefix,$user,"menu");
	        					
	        					echo"<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#ModalmodifU".$user['id']."\"><i class=\"fa fa-edit\"></i><em> Modifier</em></a>";
					          echo"<a class=\"dropdown-item\"  href=\"?logout=1\"><i class=\"fa fa-sign-out-alt\"></i><em> Déconnexion</em></a>
					        </div>
				        
				        </li>";
          ?>
        <!--	<li class="nav-item">
              <a class="nav-link" href="?logout=1"><i class="fa fa-sign-out-alt" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>Déconnexion</em>"></i><em class="d-md-none"> Déconnexion</em></a>

            </li> -->
          </ul>
        </div>
        </div>
      </nav>
    </header>
    <? modifusertab($user['type'],$user['id'],$user['email'],$user['nick']); ?>
    <!-- Begin page content-->
    
    <main role="main" class="container containerpage">
	<?php
	//include('pages/carousel.php');
	if ($page=="Books") include("pages/Books.php");
	if ($page=="Books2") include("pages/Books2.php");
	if ($page=="Auteurs") include("pages/Auteurs.php");
	if ($page=="Upload"&&($user['type']=="admin"||$user['type']=="contributeur")) include("pages/Upload.php");
	if ($page=="Profils") include("pages/Profils.php");
	if ($page=="Favoris") include("pages/Favoris.php");
	if ($page=="Outils"&&$user['type']=="admin") include("pages/Outils.php");
	//if ($page=="ModifBook"&&$user['type']=="admin") include("pages/ModifBook.php");
	include ("pages/chat.php");
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
        <span class="text-muted"><b>EbooX</b> - <i>Collection privée de livres électroniques. (<?php echo $totalbooks; ?> livres)</i></span><iframe id="dlframe" name="dlframe"></iframe>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jQuery.js"></script>
    <!--<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>-->
    <script src="js/popper.js"></script>
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
	        sequentialUploads:true,
	        done: function (e, data) {
	            $.each(data.result.files, function (index, file) {
	            	var cuurl = 'majbiblio.php?user=<?php echo $user['id']; ?>&path=<?php echo rand(); ?>&fileproc=' + encodeURIComponent(file.name) + '';
	                //$('<p/>').text(cuurl).appendTo('#files');
	                		//await sleep(500);
							$('#addedbooks').prepend($('<div></div>').load(cuurl));
	                
	                //$("#addedbooks").load(cuurl);
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
			    $('<p/>').text('Upload terminé !').appendTo('#files');
			}
	    })
	});
	</script>
	<script>
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
	</script>
<script>
$(document).ready(function(){
  $("#inputsearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#divsearch h6").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<script>
$('#toggleslide').click(function(e){
    	
    	var slideOpen = localStorage['slideopen'];
    	
    	if (slideOpen==='yes') localStorage['slideopen']='no';
    	if (slideOpen==='no') localStorage['slideopen']='yes';
});
	(function($) {
    $(document).ready(function() {
    	
    	var slideOpen = localStorage['slideopen'];
    	if (!slideOpen) {
	        // open popup
	        localStorage['slideopen'] = "no";
    	}
		var slideOpen = localStorage['slideopen'];
    	
        var $slidebox = $('#collapseslide');
            if (slideOpen==='yes') $slidebox.collapse('show');
        
    });
})(jQuery);
</script>
<!-- chat -->
<script>
	(function($) {
    $(document).ready(function() {
    	
    	var chatOpen = localStorage['chatopen'];
    	if (!chatOpen) {
	        // open popup
	        localStorage['chatopen'] = "yes";
    	}
		var chatOpen = localStorage['chatopen'];
    	
        var $chatbox = $('.chatbox'),
            $chatboxTitle = $('.chatbox__title'),
            $chatboxTitleClose = $('.chatbox__title__close'),
            $chatboxCredentials = $('.chatbox__credentials');
            if (chatOpen==='yes') $chatbox.removeClass('chatbox--tray');
        $chatboxTitle.on('click', function() {
            $chatbox.toggleClass('chatbox--tray');
            if (chatOpen==='yes') localStorage['chatopen']='no';
            if (chatOpen==='no') localStorage['chatopen']='yes';
            sessionStorage
        });
        $chatboxTitleClose.on('click', function(e) {
            e.stopPropagation();
            $chatbox.addClass('chatbox--closed');
        });
        $chatbox.on('transitionend', function() {
            if ($chatbox.hasClass('chatbox--closed')) $chatbox.remove();
        });
        /*$chatboxCredentials.on('submit', function(e) {
            e.preventDefault();
            $chatbox.removeClass('chatbox--empty');
        });*/
    });
})(jQuery);
</script>
<!-- chat https://openclassrooms.com/fr/courses/1567926-un-site-web-dynamique-avec-jquery/1569840-tp-le-tchat-en-ajax-->
<script>
$('#submitchat').click(function(e){
    e.preventDefault(); // on empêche le bouton d'envoyer le formulaire

    // on sécurise les données
    var message = $('#messagechat').val();

    if(message != ""){ // on vérifie que les variables ne sont pas vides
        $.ajax({
            url : "pages/chat/send.php", // on donne l'URL du fichier de traitement
            type : "POST", // la requête est de type POST
            data : "message=" + message // et on envoie nos données
        });
		$('#messagechat').val('');
       //$('#messages').prepend("<p>" + message + "</p>"); // on ajoute le message dans la zone prévue

    }
});
</script>

<script>
	$(document).ready(function () {
    var interval = 1000;   //number of mili seconds between each call
    
    var refresh = function() {
    	var premierID = $('#messages div:first').attr('id'); // on récupère l'id le plus récent
        $.ajax({
            url: "pages/chat/messages.php?id=" + premierID,
            cache: false,
            success: function(html) {
                $('#messages').prepend(html);
                setTimeout(function() {
                	refresh();
                }, interval);
            }
        });
    };
    refresh();
});
</script>
<script>
$("#formulairerech").submit(function(event) {
	if (document.getElementById("textsearch").value.length > 3 && (document.getElementById("TitreCheck").checked == true||document.getElementById("AuteurCheck").checked == true||document.getElementById("DescrCheck").checked == true||document.getElementById("SujetCheck").checked == true))
    {
  /* stop form from submitting normally */
  event.preventDefault();
	$('#Renderbooks').hide();
  /* get some values from elements on the page: */
  var $form = $( this );
  //var url = $form.attr( "action" );
  var url = 'Bookspages.php?page=Books&p=1';
  //before send
  //$("body").addClass("loading");
	//$('#Renderbooks').html('<div class="row col-12 text-center"><img src=images/ajax-loading.gif /></div>');
  /* Send the data using post */
  var posting = $.post(url , $( "#formulairerech" ).serialize() );

  /* Alerts the results */
  posting.done(function( data ) {
     //use data
     //$("body").removeClass("loading");
	
	    $('#Renderbooks').load('Bookspages.php?page=Books&p=1', function(){
	        $('#Renderbooks').slideDown('slow');
	    });

  });
    }
});
$("#formulairerech").keyup(function(event) {

  /* stop form from submitting normally */
  event.preventDefault();
	if (document.getElementById("textsearch").value.length > 3 && (document.getElementById("TitreCheck").checked == true||document.getElementById("AuteurCheck").checked == true||document.getElementById("DescrCheck").checked == true||document.getElementById("SujetCheck").checked == true))
    {
		  $('#Renderbooks').hide();
		  /* get some values from elements on the page: */
		  var $form = $( this );
		  //var url = $form.attr( "action" );
		  var url = 'Bookspages.php?page=Books&p=1';
		  //before send
		  //$("body").addClass("loading");
		  
			//$('#Renderbooks').html('<div class="col-12 text-center"><img src=images/ajax-loading.gif /></div>');
		  /* Send the data using post */
		  var posting = $.post(url , $( "#formulairerech" ).serialize() );
		
		  /* Alerts the results */
		  posting.done(function( data ) {
		     //use data
		     //$("body").removeClass("loading");
			
	    $('#Renderbooks').load('Bookspages.php?page=Books&p=1', function(){
	        $('#Renderbooks').slideDown('slow');
	    });
			
		
		  });
    }
});
$("#formulairerech").change(function(event) {

  /* stop form from submitting normally */
  event.preventDefault();

		$('#Renderbooks').hide();
		  /* get some values from elements on the page: */
		  var $form = $( this );
		  //var url = $form.attr( "action" );
		  var url = 'Bookspages.php?page=Books&p=1';
		  //before send
		  //$("body").addClass("loading");
		  
			//$('#Renderbooks').html('<div class="col-12 text-center"><img src=images/ajax-loading.gif /></div>');
		  /* Send the data using post */
			var posting = $.post(url , $( "#formulairerech" ).serialize() );
		
		  /* Alerts the results */
	posting.done(function( data ) {
		     //use data
		     //$("body").removeClass("loading");
			
	    $('#Renderbooks').load('Bookspages.php?page=Books&p=1', function(){
	        $('#Renderbooks').slideDown('slow');
	    });
			
		
	});

});
$("#formulairedel").submit(function(event) {

  /* stop form from submitting normally */
  event.preventDefault();
	$('#Renderbooks').hide();
  /* get some values from elements on the page: */
  var $form = $( this );
  //var url = $form.attr( "action" );
  var url = 'Bookspages.php?page=Books&p=1';
  //before send
  //$("body").addClass("loading");
	//$('#Renderbooks').html('<div class="col-12 text-center"><img src=images/ajax-loading.gif /></div>');
  /* Send the data using post */
  var posting = $.post(url , $( "#formulairedel" ).serialize() );
  $('#textsearch').val('');
  $('#TitreCheck').prop( "checked", false );
  $('#AuteurCheck').prop( "checked", false );
  $('#DescrCheck').prop( "checked", false );
  $('#SujetCheck').prop( "checked", false );
  $('#formsearchdiv').collapse('hide');
    
  /* Alerts the results */
  posting.done(function( data ) {
     //use data
     //$("body").removeClass("loading");
	    $('#Renderbooks').load('Bookspages.php?page=Books&p=1', function(){
	        $('#Renderbooks').slideDown('slow');
	    });
	

  });
});


function changepage(pageid) {

  /* stop form from submitting normally */
  event.preventDefault();
	$('#Renderbooks').hide();
  //var url = $form.attr( "action" );
  var cuurl = 'Bookspages.php?page=Books&p=' + pageid;
  //before send
  //$('#Renderbooks').html('<div class="col-12 text-center"><img src=images/ajax-loading.gif /></div>');

	    $('#Renderbooks').load(cuurl, function(){
	        $('#Renderbooks').slideDown('slow');
	    });
	
}


$(document).ready(function () {
	//$('#Renderbooks').html('<div class="col-12 text-center"><img src=images/ajax-loading.gif /></div>');
	$('#Renderbooks').hide();
	    $('#Renderbooks').load('Bookspages.php?page=Books', function(){
	        $('#Renderbooks').slideDown('slow');
	    });
	
});
 $('.dropdown-menu li').click(function (event) {

         event.stopPropagation();
  
    });
</script>

	<?php } ?>
	
  </body>
</html>
