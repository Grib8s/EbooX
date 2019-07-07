<?php
$query="SELECT *, COUNT(titre) FROM ".$mysql_prefix."ebooks GROUP by titre HAVING COUNT(titre) >1;";
//$query="SELECT * FROM ".$mysql_prefix."ebooks;";
	$q = $pdo->prepare($query);
	$q->execute();
	//if ($eboox_class!="aleatoire") showpagination($p,$pmax); similar text
	// Modifier la bdd pour inclure le nom du libre dans doubloncheck
	echo"<div class=\"alert alert-dark\" role=\"alert\">Recherche des livres en doublons</div>";
	echo"<div class=\"row\">";
	$i=0;
	while ($booksearch=$q->fetch()) {
		// recherche si besoin de suppression d'un doublon enregistré suite à l'ajout d'un nouveau livre
		$querycheck="SELECT ".$mysql_prefix."doubloncheck.id FROM ".$mysql_prefix."ebooks,".$mysql_prefix."doubloncheck 
		WHERE ".$mysql_prefix."ebooks.titre=:titre 
		AND ".$mysql_prefix."doubloncheck.titre=:titre
		AND ".$mysql_prefix."ebooks.date>".$mysql_prefix."doubloncheck.date;";
		$qcheck = $pdo->prepare($querycheck);
	    $qcheck->bindParam('titre', $booksearch['titre'], PDO::PARAM_STR);
		$qcheck->execute();
		if ($qcheck->rowCount()>0) {
			//suppression
			$query = "DELETE FROM ".$mysql_prefix."doubloncheck WHERE titre=:titre;";
		    $q = $pdo->prepare($query);
		    $q->bindParam('titre', $booksearch['titre'], PDO::PARAM_INT);
		    $q->execute();
		} 
		// recherches des doublons enregistrés
		$querycheck="SELECT ".$mysql_prefix."doubloncheck.id FROM ".$mysql_prefix."ebooks,".$mysql_prefix."doubloncheck 
		WHERE ".$mysql_prefix."ebooks.titre=:titre 
		AND ".$mysql_prefix."doubloncheck.titre=:titre;";
		$qcheck = $pdo->prepare($querycheck);
	    $qcheck->bindParam('titre', $booksearch['titre'], PDO::PARAM_STR);
		$qcheck->execute();
		if ($qcheck->rowCount()==0) {
			// affichage des doublons
			$query2="SELECT ".$mysql_prefix."ebooks.* FROM ".$mysql_prefix."ebooks WHERE ".$mysql_prefix."ebooks.titre=:titre OR pathfile=:pathfile;";
			$q2 = $pdo->prepare($query2);
		    $q2->bindParam('titre', $booksearch['titre'], PDO::PARAM_STR);
		    $q2->bindParam('pathfile', $booksearch['pathfile'], PDO::PARAM_STR);
			$q2->execute();
			if ($q2->rowCount()>0) {
				echo "<form class=\"form-inline\" name=\"validdoublon".$i."\" method=\"POST\"><input type=\"hidden\" name=\"doublbook\" value=\"".$booksearch['titre']."\"></form>";
				echo"<div class=\"col-12\"><div class=\"card mb-4 pl-4 pr-4 pb-2\"><h4 class=\"text-center\">Livres avec le titre : \"".$booksearch['titre']."\"</h4>
				<button class=\"btn btn-info\" onclick=\"window.document.validdoublon".$i.".submit()\">Ces livres ne sont pas des doublons</button></div></div>";
			}
			while ($book=$q2->fetch()) {
				showbook($book['id'],$book['titre'],$book['auteur'],$book['descr'],$book['sujet'],$book['filename'],$book['pathfile'],$user,$page,$pdo,$mysql_prefix); 
			}
			echo"<hr style=\"width: 100%; color: black; height: 1px; background-color:grey;\" />";
			$i++;
		}
	}
	if ($i==0) echo"<div class=\"col-12\"><div class=\"card mb-4 pl-4 pr-4 pb-2\"><h4 class=\"text-center\">Aucun livre en doublon à vérifier</h4></div></div>";
	echo"</div>";
?>