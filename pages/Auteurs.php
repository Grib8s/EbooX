<div class="card p-3 mb-4">
<h4 class="text-center"><em>Liste des auteurs</em></h4>
<em class="text-center">Classé par nom d'auteur, dans l'ordre alphabétique.</em>
<hr>
	<div class="col-auto">
      <label class="sr-only" for="inlineFormInputGroup">Recherche</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fas fa-search text-info"></i> </div>
        </div>
        <input class="form-control" id="inputsearch" type="text" placeholder="Rechercher un auteur">
      </div>
    </div>






</div>

<?php

	//$query="SELECT DISTINCT auteur FROM ".$mysql_prefix."auteurvalid ORDER BY auteur;";

	$query="select DISTINCT c.auteur, 
                    (select count(id) from ".$mysql_prefix."ebooks where b.auteur = auteur) as counter 
                    from ".$mysql_prefix."auteurvalid as c, ".$mysql_prefix."ebooks as b 
                    where b.auteur = c.auteur
                    order by c.auteur asc";
	
	
	
	$q = $pdo->prepare($query);
	$q->execute();
	$nba=$q->rowCount();
	$j=0;
	$i=0;
	echo"<div class=\"card\">
	
	<div class=\"row p-2\" id=\"divsearch\"><div class=\"col-lg-4\">";
	while ($auteur=$q->fetch()) {
		/*$query2="SELECT DISTINCT id FROM ".$mysql_prefix."ebooks WHERE auteur=:auteur;";
		$q2 = $pdo->prepare($query2);
		$q2->bindParam('auteur', $auteur['auteur'], PDO::PARAM_STR);
		$q2->execute();
		$nbl=$q2->rowCount();*/
		//affichage de la selection
		echo "<h6 style=\"cursor:pointer\" onclick=\"window.document.gotoauteur".$j.".submit()\">".$auteur['auteur']." (".$auteur['counter'].")</h6>";
				echo "<form name=\"gotoauteur".$j."\" method=\"POST\" action=\"/\">
				<input name=\"searchbook\" type=\"hidden\" value=\"".$auteur['auteur']."\">
				<input name=\"checktitre\" type=\"hidden\" value=\"0\">
				<input name=\"checkdescr\" type=\"hidden\" value=\"0\">
				<input name=\"checksujet\" type=\"hidden\" value=\"0\">
				<input name=\"checkauteur\" type=\"hidden\" value=\"1\">
				<input name=\"checkauteurstrict\" type=\"hidden\" value=\"1\">
				<input type=\"hidden\" name=\"searchchange\" value=\"go\">
				</form>";


		$i++;
		//if ($i>$nba/3) { echo "</div><div class=\"col-lg-4\">";$i=0; }
		echo "</div><div class=\"col-lg-4\">";

		
		$j++;
	} 
	echo"</div></div></div>";
	
?>