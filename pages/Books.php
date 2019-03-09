    <?php 
	echo "<form class=\"form-inline\" name=\"searchdel\" method=\"POST\" action=\"/\"><input type=\"hidden\" name=\"delsearchbook\" value=\"1\"></form>";
    ?>
    <div class="alert alert-dark" role="alert">
	  <?php echo $classtext;
	  if ($search!="") {
	  	if ($_SESSION['searchauteurstrict']==1) $addtexts=" l'auteur"; else $addtexts="";
	  	echo "<br><span class=\"badge badge-dark\" style=\"cursor:pointer\" onclick=\"window.document.searchdel.submit()\">Supprimer la recherche</span><br> Recherche de$addtexts : \"".$search."\"";
	  	
	  	if ($_SESSION['searchtitre']==1) $check1=" checked"; else $check1="";
	  	if ($_SESSION['searchauteur']==1) $check2=" checked"; else $check2="";
	  	//if ($_SESSION['searchauteurstrict']==1) $check5=" checked"; else $check5="";
	  	if ($_SESSION['searchdescr']==1) $check3=" checked"; else $check3="";
	  	if ($_SESSION['searchsujet']==1) $check4=" checked"; else $check4="";
	  	if ($_SESSION['searchauteurstrict']!=1) {
		  	echo "<br><b>Rechercher par : </b><br><form name=\"searchspec\" method=\"POST\" action=\"/\">
		  	
		  	<div class=\"form-check form-check-inline\">
			  <input class=\"form-check-input\" type=\"checkbox\" name=\"checktitre\" value=\"1\" onchange=\"this.form.submit()\"$check1>
			  <label class=\"form-check-label\">Titre</label>
			</div>
		  	<div class=\"form-check form-check-inline\">
			  <input class=\"form-check-input\" type=\"checkbox\" name=\"checkauteur\" value=\"1\" onchange=\"this.form.submit()\"$check2>
			  <label class=\"form-check-label\">Auteur</label>
			</div>";
			/*<div class=\"form-check form-check-inline\">
			  <input class=\"form-check-input\" type=\"checkbox\" name=\"checkauteurstrict\" value=\"1\" onchange=\"this.form.submit()\"$check5>
			  <label class=\"form-check-label\">strict</label>
			</div>*/
		  	echo"<div class=\"form-check form-check-inline\">
			  <input class=\"form-check-input\" type=\"checkbox\" name=\"checkdescr\" value=\"1\" onchange=\"this.form.submit()\"$check3>
			  <label class=\"form-check-label\">Description</label>
			</div>
		  	<div class=\"form-check form-check-inline\">
			  <input class=\"form-check-input\" type=\"checkbox\" name=\"checksujet\" value=\"1\" onchange=\"this.form.submit()\"$check4>
			  <label class=\"form-check-label\">Mots clefs</label>
			</div>
			<input type=\"hidden\" name=\"searchchange\" value=\"go\">
	
		  	</form>";
	  	}
	  	//echo $request;
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