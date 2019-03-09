<?php

	$query="SELECT DISTINCT auteur FROM ".$mysql_prefix."ebooks ORDER BY auteur;";
	$q = $pdo->prepare($query);
	$q->execute();
	$i=0;
	while ($auteur=$q->fetch()) {
		$auteurs[$i]=$auteur['auteur'];
		$i++;
	}
	try {
		//$query2="SELECT DISTINCT auteur FROM ".$mysql_prefix."ebooks WHERE auteur NOT IN (SELECT auteur FROM ".$mysql_prefix."auteurvalid) ORDER BY auteur;";
		$query2="SELECT DISTINCT auteur FROM ".$mysql_prefix."ebooks WHERE NOT EXISTS (SELECT ".$mysql_prefix."auteurvalid.auteur FROM ".$mysql_prefix."auteurvalid WHERE ".$mysql_prefix."auteurvalid.auteur=".$mysql_prefix."ebooks.auteur) ORDER BY auteur LIMIT ".$limit.";";
		$q2 = $pdo->prepare($query2);
		$q2->execute();
	}
	catch (Exception $pdo)
	{
        die('Erreur : ' . $pdo->getMessage());
	}
	
	if ($q2->rowCount()>0) {
	$nba=$q->rowCount();
	echo"<div class=\"alert alert-dark\" role=\"alert\">Vérifier les auteurs (max $limit sur $nba) - Format \"Prénom Nom\"</div>";
	echo "<div class=\"container\"><div class=\"row\"><div class=\"card p-3 col-12\">";
	$j=0;
	while ($auteur=$q2->fetch()) {
		unset($result);
		$i=0;
		foreach ($auteurs as $value) {
	        if (compareStrings($auteur['auteur'], $value) > 75 && $auteur['auteur']!=$value) {
	            $result[$i] = $value;
	            $i++;
	        }
		}
		if ($i==0) {
			$counta="";
			$valider="onclick=\"window.document.validauteursolo".$j.".submit()\"";
			echo"<form class=\"form-inline\" name=\"validauteursolo".$j."\" method=\"POST\"><input type=\"hidden\" name=\"addauteurvalid\" value=\"".$auteur['auteur']."\"></form>";
		}
		else {
			$valider="data-toggle=\"modal\" data-target=\"#ValidMultiAuteur".$j."\"";
			$counta=" (".count($result).") ";
		}
		
		//affichage de la selection
		echo "<h6>".$auteur['auteur']."$counta 
		<span class=\"badge badge-info\" style=\"cursor:pointer\" $valider><i class=\"fa fa-check\"></i> Valider</span>
		<span class=\"badge badge-info\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#ModifAuteur".$j."\"><i class=\"fa fa-edit\"></i> Modifier</span>
		</h6>";
		
		// modal d'ajout en multi
		echo"<div id=\"ValidMultiAuteur".$j."\" class=\"modal fade\" role=\"dialog\">
		  <div class=\"modal-dialog\">
		
		    <div class=\"modal-content\">
		      <div class=\"modal-header\">
		      	<h4 class=\"modal-title\">Valider l'auteur ?</h4>
		        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
		        
		      </div>
		      <div class=\"modal-body\">
		      <h5>".$auteur['auteur']."</h5>
		        <p>Selectionnez les auteurs similaires à modifier :</p>";
		        // form de multi validation
		        		if (count($result)>0) {
							echo "<form name=\"validmultiauteur".$j."\" method=\"POST\">
							<input type=\"hidden\" name=\"auteurref\" value=\"".$auteur['auteur']."\">";
							$k=0;
							foreach ($result as $value) {
								echo "<input type=\"checkbox\" value=\"".$value."\" name=\"auteurtomodif".$k."\" id=\"auteurtomodif".$k."\">
								  <label class=\"form-check-label\" for=\"auteurtomodif".$k."\">
								    ".$value."
								  </label><br>";
								  $k++;
							}
							echo"<input type=\"hidden\" name=\"nbchoix\" value=\"".$k."\"><input type=\"hidden\" name=\"opsql\" value=\"validmultiauteur\"></form>";
						} 
		      echo"</div>
		      <div class=\"modal-footer\">";
		      	echo"<button type=\"button\" class=\"btn btn-success\" data-dismiss=\"modal\" onclick=\"window.document.validmultiauteur".$j.".submit()\">Valider</button>
		        <button type=\"button\" class=\"btn btn-dark\" data-dismiss=\"modal\">Annuler</button>
		      </div>
		    </div>
		
		  </div>
		</div>";	
		// modal de modification
		echo"<div id=\"ModifAuteur".$j."\" class=\"modal fade\" role=\"dialog\">
		  <div class=\"modal-dialog\">
		
		    <div class=\"modal-content\">
		      <div class=\"modal-header\">
		      	<h4 class=\"modal-title\">Modifier l'auteur ?</h4>
		        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
		        
		      </div>
		      <div class=\"modal-body\">
		      <h5>".$auteur['auteur']."</h5>";
		      // form de modification
				echo "<form name=\"modifauteur".$j."\" method=\"POST\">
				<input name=\"prevauteurname\" type=\"hidden\" value=\"".$auteur['auteur']."\">
				<input name=\"modifauteurname\" type=\"text\" class=\"form-control\" value=\"".$auteur['auteur']."\">";
						if (count($result)>0) {
							echo "<p>Modifier aussi les auteurs suivants ?</p>";
							$k=0;
							foreach ($result as $value) {
								echo "<input type=\"checkbox\" value=\"".$value."\" name=\"auteurtomodif".$k."\" id=\"auteurtomodif".$k."\">
								  <label class=\"form-check-label\" for=\"auteurtomodif".$k."\">
								    ".$value."
								  </label><br>";
								  $k++;
							}
							echo"<input type=\"hidden\" name=\"nbchoix\" value=\"".$k."\">";
						}
				echo"</form>";
		      echo"</div>
		      <div class=\"modal-footer\">";
		      	echo"<button type=\"button\" class=\"btn btn-success\" data-dismiss=\"modal\" onclick=\"window.document.modifauteur".$j.".submit()\">Valider</button>
		        <button type=\"button\" class=\"btn btn-dark\" data-dismiss=\"modal\">Annuler</button>
		      </div>
		    </div>
		
		  </div>
		</div>";	
		
		
		
		
		

		$j++;
	}
	echo"</div></div></div>";
	} else {
		echo"<div class=\"alert alert-dark\" role=\"alert\">Aucun auteur non vérifié \o/</div>";
	}
	
?>