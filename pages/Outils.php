<div class="card p-3 mb-4"><h3>Entretien</h3>
<p>Voici une liste d'outils pour entretenir la bibliothèque.</p>
<ul>
	<li>Livres</li>
	<ul>
		<li><a href="?page=Outils&outil=doublons">Vérifier les doublons</a></li>
		<li><a href="?page=Outils&outil=noauteur">Livres sans auteur</a></li>
		<li><a href="?page=Outils&outil=nodescr">Livres sans descriptions</a></li>
		<li><a href="?page=Outils&outil=nosujet">Livres sans mots clef</a></li>
	</ul>
	<li>Auteurs</li>
	<ul>
		<li><a href="?page=Outils&outil=auteurs">Vérifier les auteurs</a></li>
		<li><a href="?page=Outils&outil=auteurvalid">Auteurs validés</a></li>
	</ul>
</ul>
<?php showdiskspace(); ?>

</div>

<?php

// espace disque






$outil=$_GET['outil'];



if ($outil=="doublons") include("pages/outils/livresdoublons.php");

if ($outil=="auteurs") include("pages/outils/auteurverif.php");

if ($outil=="auteurvalid") include("pages/outils/auteurvalid.php");

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

	$string1="Stephens,King";
	$string2= "King Stephen";
	
	echo 'Compare result: ' . compareStrings($string1, $string2) . '%';
	//60%
	
	
	
}
?>
