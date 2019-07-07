<div class="card p-3 mb-4">
<h4 class="text-center"><em>Entretien</em></h4>
<em class="text-center">Voici une liste d'outils pour entretenir la bibliothèque.</em>
<hr>
<div class="row">
	<div class="dropdown col-4">
	  <button class="btn btn-info btn-block dropdown-toggle" type="button" id="dropdownLivres" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Livres
	  </button>
	  <div class="dropdown-menu" aria-labelledby="dropdownLivres">
	    <li><a class="dropdown-item" href="?page=Outils&outil=doublons">Vérifier les doublons</a></li>
		<li><a class="dropdown-item" href="?page=Outils&outil=noauteur">Livres sans auteur</a></li>
		<li><a class="dropdown-item" href="?page=Outils&outil=nodescr">Livres sans descriptions</a></li>
		<li><a class="dropdown-item" href="?page=Outils&outil=nosujet">Livres sans mots clef</a></li>
	  </div>
	</div>
	<div class="dropdown col-4">
	  <button class="btn btn-info btn-block dropdown-toggle" type="button" id="dropdownAuteurs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Auteurs
	  </button>
	  <div class="dropdown-menu" aria-labelledby="dropdownAuteurs">
		<li><a class="dropdown-item" href="?page=Outils&outil=auteurs">Vérifier les auteurs</a></li>
		<li><a class="dropdown-item" href="?page=Outils&outil=auteurvalid">Auteurs validés</a></li>
	  </div>
	</div>
	<div class="dropdown col-4">
	  <button class="btn btn-info btn-block dropdown-toggle" type="button" id="dropdownSite" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Site
	  </button>
	  <div class="dropdown-menu" aria-labelledby="dropdownSite">
	  	<li><a class="dropdown-item" href="?page=Outils&outil=phpinfo">phpinfo</a></li>
		<li><a class="dropdown-item" href="?page=Outils&outil=tests">tests</a></li>
	  </div>
	</div>
</div>
<hr>
<?php
// Majspace($pdo,$mysql_prefix);showdiskspace($pdo,$mysql_prefix,$sizemax); 
?>

</div>

<?php

// espace disque






$outil=$_GET['outil'];



if ($outil=="doublons") include("pages/outils/livresdoublons.php");

if ($outil=="auteurs") include("pages/outils/auteurverif.php");

if ($outil=="auteurvalid") include("pages/outils/auteurvalid.php");

if ($outil=="phpinfo") {
	ob_start();
	phpinfo();
	$phpinfo = ob_get_clean();
	
	# Body-Content rausholen
	$phpinfo = preg_replace('#^.*<body>(.*)</body>.*$#s', '$1', $phpinfo);
	# XHTML-Fehler korrigieren
	$phpinfo = str_replace('module_Zend Optimizer', 'module_Zend_Optimizer', $phpinfo);
	# <font> durch <span> ersetzen
	$phpinfo = str_replace('<font', '<span', $phpinfo);
	$phpinfo = str_replace('</font>', '</span>', $phpinfo);
	#Table
	$phpinfo = str_replace( 'border="0" cellpadding="3"', 'class="table table-bordered table-striped" style="table-layout: fixed;word-wrap: break-word;"', $phpinfo );
	$phpinfo = str_replace('<tr class="h"><th>', '<thead><tr><th>', $phpinfo);
	$phpinfo = str_replace('</th></tr>', '</th></tr></thead><tbody>', $phpinfo);
	$phpinfo = str_replace('</table>', '</tbody></table>', $phpinfo);
	# Schlüsselwörter grün oder rot einfärben
	$phpinfo = preg_replace('#>(on|enabled|active)#i', '><span class="text-success">$1</span>', $phpinfo);
	$phpinfo = preg_replace('#>(off|disabled)#i', '><span class="text-error">$1</span>', $phpinfo);
	
	echo '<div class="card p-3 mb-4" id="phpinfo">';
	echo $phpinfo;
	echo '</div>';
}

if ($outil=="nodescr") {
	$query="SELECT * FROM ".$mysql_prefix."ebooks WHERE descr='' ORDER BY RAND() LIMIT ".$limit.";";
	$q = $pdo->prepare($query);
	$q->execute();
	echo"<div class=\"alert alert-dark\" role=\"alert\">Recherche des livres dans description (max $limit)</div>";
	echo"<div class=\"row\">";
	while ($book=$q->fetch()) {
		showbook($book['id'],$book['titre'],$book['auteur'],$book['descr'],$book['sujet'],$book['filename'],$book['pathfile'],$user,$page,$pdo,$mysql_prefix); 
	}
	echo"</div>";
}



if ($outil=="nosujet") {
	$query="SELECT * FROM ".$mysql_prefix."ebooks WHERE sujet='' ORDER BY RAND() LIMIT ".$limit.";";
	$q = $pdo->prepare($query);
	$q->execute();
	echo"<div class=\"alert alert-dark\" role=\"alert\">Recherche des livres sans mots clefs (max $limit)</div>";
	echo"<div class=\"row\">";
	while ($book=$q->fetch()) {
		showbook($book['id'],$book['titre'],$book['auteur'],$book['descr'],$book['sujet'],$book['filename'],$book['pathfile'],$user,$page,$pdo,$mysql_prefix); 
	}
	echo"</div>";
}



if ($outil=="noauteur") {
	$query="SELECT * FROM ".$mysql_prefix."ebooks WHERE auteur='' ORDER BY RAND() LIMIT ".$limit.";";
	$q = $pdo->prepare($query);
	$q->execute();
	echo"<div class=\"alert alert-dark\" role=\"alert\">Recherche des livres sans auteur (max $limit)</div>";
	echo"<div class=\"row\">";
	while ($book=$q->fetch()) {
		showbook($book['id'],$book['titre'],$book['auteur'],$book['descr'],$book['sujet'],$book['filename'],$book['pathfile'],$user,$page,$pdo,$mysql_prefix); 
	}
	echo"</div>";
}


if ($outil=="tests") {
	$query="SELECT auteur FROM ".$mysql_prefix."ebooks GROUP BY auteur;";
	$q = $pdo->prepare($query);
	$q->execute();
	while ($book=$q->fetch()) {
				  $auteur = $book['auteur'];
				  
					list($last,$first) = explode( ",", $auteur );
					if ($first!="") {
						$first = ltrim($first, " ");
						$last = ltrim($last, " ");
						$first = rtrim($first, " ");
						$last = rtrim($last, " ");
						$auteur2=$first . ' ' . $last;
						
						$query2 = "UPDATE ".$mysql_prefix."ebooks SET auteur=:auteur2 WHERE auteur=:auteur;";
						$q2 = $pdo->prepare($query2);
						$q2->bindParam('auteur', $auteur, PDO::PARAM_STR);
						$q2->bindParam('auteur2', $auteur2, PDO::PARAM_STR);
						$q2->execute();	
						echo $auteur."->".$auteur2."<br>";
					}
	}				

	
	
}
?>
