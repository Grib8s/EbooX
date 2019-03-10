    <?php 
	echo "<form class=\"form-inline\" name=\"searchdel\" method=\"POST\" action=\"/\"><input type=\"hidden\" name=\"delsearchbook\" value=\"1\"></form>";
    ?>
    <div class="card p-3 mb-4">
	  <?php echo "<h4 class=\"text-center\"><em>".$classtext."</em></h4>";
	  if ($search!="") {
	  	if ($_SESSION['searchauteurstrict']==1) $addtexts=" l'auteur"; else $addtexts="";
	  	echo "<hr><a class=\"btn btn-sm btn-info\" style=\"cursor:pointer;color:#fff\" onclick=\"window.document.searchdel.submit()\">Supprimer la recherche</a>
	  	<div class=\"text-center\">
	  	Recherche de$addtexts : \"".$search."\"";
	  	
	  	if ($_SESSION['searchtitre']==1) $check1=" checked"; else $check1="";
	  	if ($_SESSION['searchauteur']==1) $check2=" checked"; else $check2="";
	  	//if ($_SESSION['searchauteurstrict']==1) $check5=" checked"; else $check5="";
	  	if ($_SESSION['searchdescr']==1) $check3=" checked"; else $check3="";
	  	if ($_SESSION['searchsujet']==1) $check4=" checked"; else $check4="";
	  	if ($_SESSION['searchauteurstrict']!=1) {
		  	echo "<form name=\"searchspec\" method=\"POST\" action=\"/\">
		  	
		  	<div class=\"d-inline custom-control custom-checkbox ml-2\">
			  <input class=\"custom-control-input\" id=\"TitreCheck\" type=\"checkbox\" name=\"checktitre\" value=\"1\" onchange=\"this.form.submit()\"$check1>
			  <label class=\"custom-control-label\" for=\"TitreCheck\">Titre</label>
			</div>
		  	<div class=\"d-inline custom-control custom-checkbox ml-2\">
			  <input class=\"custom-control-input\" id=\"AuteurCheck\" type=\"checkbox\" name=\"checkauteur\" value=\"1\" onchange=\"this.form.submit()\"$check2>
			  <label class=\"custom-control-label\" for=\"AuteurCheck\">Auteur</label>
			</div>";
			/*<div class=\"form-check form-check-inline\">
			  <input class=\"form-check-input\" type=\"checkbox\" name=\"checkauteurstrict\" value=\"1\" onchange=\"this.form.submit()\"$check5>
			  <label class=\"form-check-label\">strict</label>
			</div>*/
		  	echo"<div class=\"d-inline custom-control custom-checkbox ml-2\">
			  <input class=\"custom-control-input\" id=\"DescrCheck\" type=\"checkbox\" name=\"checkdescr\" value=\"1\" onchange=\"this.form.submit()\"$check3>
			  <label class=\"custom-control-label\" for=\"DescrCheck\">Description</label>
			</div>
		  	<div class=\"d-inline custom-control custom-checkbox ml-2\">
			  <input class=\"custom-control-input\" id=\"SujetCheck\" type=\"checkbox\" name=\"checksujet\" value=\"1\" onchange=\"this.form.submit()\"$check4>
			  <label class=\"custom-control-label\" for=\"SujetCheck\">Mots clefs</label>
			</div>
			<input type=\"hidden\" name=\"searchchange\" value=\"go\">
	
		  	</form>";
	  	}
	  	echo "</div>";
	  }
	  ?>
	</div>
<div class="row">
      <?php 
      
	$query="SELECT * FROM ".$mysql_prefix."ebooks".$request." ORDER BY ".$classsql.";";
	$q = $pdo->prepare($query);
	$q->execute();

	if ($eboox_class!="aleatoire") showpagination($p,$pmax,$page); 
	$i=0;
	while ($book=$q->fetch()) {
		showbook($book['id'],$book['titre'],$book['auteur'],$book['descr'],$book['sujet'],$book['filename'],$book['pathfile'],$user,$page,$pdo,$mysql_prefix); 
		$i++;
	}
      
    if ($eboox_class!="aleatoire") showpagination($p,$pmax,$page);  
      ?>
	
</div>