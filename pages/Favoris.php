    <div class="card p-3 mb-4 col-12">
	  <?php echo "<h4 class=\"text-center\"><em>".$classtext."</em></h4><hr>";
	  echo "<form method=\"POST\"><div class=\"col-auto row d-flex justify-content-center\">
	  <div class=\"col-lg-auto col-md-auto col-xs-12\">
	  <select class=\"form-control form-control-sm\" name=\"typerfilter\" onchange=\"this.form.submit();\">";
	  $selecttext=array("Recommandations", "Tous les livres", "Liste de lecture");
		foreach ($selecttext as $key => $value){
			if ($key==$typerfilter) $selected=" selected"; else $selected="";
			echo "<option value=\"$key\"$selected>".$value."</option>";
}
	  echo "</select>
	  </div><em> de </em>
	  <div class=\"col-lg-auto col-md-auto col-xs-12\">
	  <select class=\"form-control form-control-sm\" name=\"userfilter\" onchange=\"this.form.submit();\">
	  <option value=\"0\">Tout le monde</option>";
	$query="SELECT DISTINCT ".$mysql_prefix."users.* FROM ".$mysql_prefix."users,".$mysql_prefix."favoris,".$mysql_prefix."listelec,".$mysql_prefix."ebooks WHERE 
	".$mysql_prefix."favoris.user=".$mysql_prefix."users.id OR 
	".$mysql_prefix."listelec.user=".$mysql_prefix."users.id OR 
	".$mysql_prefix."ebooks.user=".$mysql_prefix."users.id ORDER BY ".$mysql_prefix."users.nick;";
	/*$query="SELECT * FROM ".$mysql_prefix."users INNER JOIN ".$mysql_prefix."favoris ON ".$mysql_prefix."favoris.user = ".$mysql_prefix."users.id
	INNER JOIN ".$mysql_prefix."ebooks ON ".$mysql_prefix."ebooks.user = ".$mysql_prefix."users.id
	INNER JOIN ".$mysql_prefix."listelec ON ".$mysql_prefix."listelec.user = ".$mysql_prefix."users.id;";*/
			


	
	$q = $pdo->prepare($query);
	$q->execute();
	while ($usersel=$q->fetch())  {

		if ($usersel['id']==$userfilter) $selected=" selected"; else $selected="";
		echo "<option value=\"".$usersel['id']."\"$selected>".$usersel['nick']."</option>";
		
	}
	  echo "</select><input type=\"hidden\" name=\"userfilterchange\" value=\"go\">
	  </div>
	  </div>
	  </form>";
	 // echo $query;
if ($typerfilter==1) $query="SELECT * FROM ".$mysql_prefix."ebooks WHERE 1=1".$request." ORDER BY ".$classsql.";";

if ($typerfilter==2) $query="SELECT DISTINCT ".$mysql_prefix."ebooks.* FROM ".$mysql_prefix."ebooks,".$mysql_prefix."listelec WHERE ".$mysql_prefix."ebooks.id=".$mysql_prefix."listelec.book".$request." ORDER BY ".$classsql.";";

if (!$typerfilter||$typerfilter==0) $query="SELECT DISTINCT ".$mysql_prefix."ebooks.* FROM ".$mysql_prefix."ebooks,".$mysql_prefix."favoris WHERE ".$mysql_prefix."ebooks.id=".$mysql_prefix."favoris.book".$request." ORDER BY ".$classsql.";";

	  
	  ?>
	</div>
<div class="row">
      <?php 
   	//echo $query;
   	echo $typefilter;
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

