    <div class="card p-3 mb-4">
	  <?php echo "<h4 class=\"text-center\"><em>".$classtext."</em></h4>";
	  echo "<em class=\"text-center\">Affichage des recommandations seulement</em>";
	  ?>
	</div>
<div class="row">
      <?php 
      
	$query="SELECT ".$mysql_prefix."ebooks.* FROM ".$mysql_prefix."ebooks,".$mysql_prefix."favoris WHERE ".$mysql_prefix."ebooks.id=".$mysql_prefix."favoris.book ORDER BY ".$classsql.";";
	//echo $query;
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