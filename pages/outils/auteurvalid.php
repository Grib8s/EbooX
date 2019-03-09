<?php

	$query="SELECT DISTINCT auteur FROM ".$mysql_prefix."auteurvalid ORDER BY auteur;";
	$q = $pdo->prepare($query);
	$q->execute();
	$j=0;
	echo"<div class=\"card\"><div class=\"row p-2\"><div class=\"col-12\">";
	while ($auteur=$q->fetch()) {
		
		//affichage de la selection
		echo "<h6>".$auteur['auteur']." 
			<span class=\"badge badge-info\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#ModifAuteur".$j."\"><i class=\"fa fa-edit\"></i> Modifier</span>
		</h6>";
		
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
	
?>