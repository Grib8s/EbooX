<div class="card p-3 mb-4"><h3>Liste des auteurs</h3>
<p>Classé par nom d'auteur, dans l'ordre alphabétique.</p>
</div>

<?php

	$query="SELECT DISTINCT auteur FROM ".$mysql_prefix."auteurvalid ORDER BY auteur;";
	$q = $pdo->prepare($query);
	$q->execute();
	$nba=$q->rowCount();
	$j=0;
	$i=0;
	echo"<div class=\"card\"><div class=\"row p-2\"><div class=\"col-lg-4\">";
	while ($auteur=$q->fetch()) {
		$query2="SELECT DISTINCT id FROM ".$mysql_prefix."ebooks WHERE auteur=:auteur;";
		$q2 = $pdo->prepare($query2);
		$q2->bindParam('auteur', $auteur['auteur'], PDO::PARAM_STR);
		$q2->execute();
		$nbl=$q2->rowCount();
		//affichage de la selection
		echo "<h6 style=\"cursor:pointer\" onclick=\"window.document.gotoauteur".$j.".submit()\">".$auteur['auteur']." ($nbl)</h6>";
				echo "<form name=\"gotoauteur".$j."\" method=\"POST\" action=\"/\">
				<input name=\"searchbook\" type=\"hidden\" value=\"".$auteur['auteur']."\">
				<input name=\"checktitre\" type=\"hidden\" value=\"0\">
				<input name=\"checkdescr\" type=\"hidden\" value=\"0\">
				<input name=\"checksujet\" type=\"hidden\" value=\"0\">
				<input name=\"checkauteur\" type=\"hidden\" value=\"1\">
				<input name=\"checkauteurstrict\" type=\"hidden\" value=\"1\">
				</form>";


		$i++;
		if ($i>$nba/3) { echo "</div><div class=\"col-lg-4\">";$i=0; }
		

		
		$j++;
	} 
	echo"</div></div></div>";
	
?>