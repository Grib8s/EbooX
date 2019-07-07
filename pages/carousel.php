<!-- https://www.eleqtriq.com/2010/05/css-3d-matrix-transformations/ -->
<div class="collapse" id="collapseslide">
<div id="carouselExampleIndicators" class="carousel slide d-none d-lg-block" data-ride="carousel" data-interval="7000">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
    </ol>
    <?php
    $i=0;
    ?>
    <div class="carousel-inner" style="height:320px">
        <div class="carousel-item active">
           
            <div class="carousel-caption d-none d-md-block" style="height:320px;padding-top:320px">
                <div class="card mb-4" style="margin-top:50px">
                <h5 style="color:#000"><em>Les recommandations</em></h5>
                </div>
                <div class="row mb-4 stage">
                	<?php
                	$querycar="SELECT COUNT(book) as counter, book as book FROM ".$mysql_prefix."favoris GROUP BY book ORDER BY counter DESC LIMIT 6;";
					  $qcar = $pdo->prepare($querycar);
					  $qcar->execute();
					while ($book=$qcar->fetch()) {
						
								$query2ar="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
								  $q2car = $pdo->prepare($query2ar);
								  $q2car->bindParam('id', $book['book'], PDO::PARAM_INT);
								  $q2car->execute();
								$bookinfo=$q2car->fetch();
						          echo"<form method=\"POST\" name=\"carouselitem$i\" action=\"/\">
						            <input name=\"searchbook\" type=\"hidden\" value=\"".$bookinfo['titre']." ".$bookinfo['auteur']."\">
						            <input name=\"checktitre\" type=\"hidden\" value=\"1\">
						            <input name=\"checkauteur\" type=\"hidden\" value=\"1\">
						            <input name=\"checkdescr\" type=\"hidden\" value=\"0\">
						            <input name=\"checksujet\" type=\"hidden\" value=\"0\">
						            <input name=\"selecttype\" type=\"hidden\" value=\"0\">
						            <input name=\"selectfrom\" type=\"hidden\" value=\"".$bookinfo['user']."\">	
						            <input type=\"hidden\" name=\"searchchange\" value=\"go\">
						            <a style=\"cursor:pointer\" onclick=\"window.document.carouselitem$i.submit();\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>
						          </form>";
						
						
						
						//echo"<a style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#InfoBook$i\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>";
						//$modal[$i]=$book['book'];
						$i++;
					}
                	?>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            
            <div class="carousel-caption d-none d-md-block" style="height:320px;padding-top:320px">
                <div class="card mb-4" style="margin-top:50px">
                <h5 style="color:#000"><em>Les derniers ajouts</em></h5>
                </div>
                <div class="row mb-4 stage">
                	<?php
                	$querycar="SELECT id FROM ".$mysql_prefix."ebooks ORDER BY date DESC, id DESC LIMIT 6;";
					  $qcar = $pdo->prepare($querycar);
					  $qcar->execute();
					while ($book=$qcar->fetch()) {
								$query2car="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
								  $q2car = $pdo->prepare($query2car);
								  $q2car->bindParam('id', $book['id'], PDO::PARAM_INT);
								  $q2car->execute();
								$bookinfo=$q2car->fetch();
						          echo"<form method=\"POST\" name=\"carouselitem$i\" action=\"/\">
						            <input name=\"searchbook\" type=\"hidden\" value=\"".$bookinfo['titre']." ".$bookinfo['auteur']."\">
						            <input name=\"checktitre\" type=\"hidden\" value=\"1\">
						            <input name=\"checkauteur\" type=\"hidden\" value=\"1\">
						            <input name=\"checkdescr\" type=\"hidden\" value=\"0\">
						            <input name=\"checksujet\" type=\"hidden\" value=\"0\">
						            <input name=\"selecttype\" type=\"hidden\" value=\"0\">
						            <input name=\"selectfrom\" type=\"hidden\" value=\"".$bookinfo['user']."\">						            
						            <input type=\"hidden\" name=\"searchchange\" value=\"go\">
						            <a style=\"cursor:pointer\" onclick=\"window.document.carouselitem$i.submit();\"><img src=\"img.php?idimg=".$book['id']."\" class=\"stage rounded\"></a>
						          </form>";
						
						
						
						//echo"<a style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#InfoBook$i\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>";
						//$modal[$i]=$book['book'];
						$i++;
					}
                	?>
                </div>
            </div>
        </div>
        <div class="carousel-item">
           
            <div class="carousel-caption d-none d-md-block" style="height:320px;padding-top:320px">
                <div class="card mb-4" style="margin-top:50px">
                <h5 style="color:#000"><em>Dans les listes de lecture</em></h5>
                </div>
                <div class="row mb-4 stage">
                	<?php
                	$querycar="SELECT DISTINCT book FROM ".$mysql_prefix."listelec WHERE book>0 ORDER BY RAND() LIMIT 6;";
					  $qcar = $pdo->prepare($querycar);
					  $qcar->execute();
					while ($book=$qcar->fetch()) {
						
								$query2ar="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
								  $q2car = $pdo->prepare($query2ar);
								  $q2car->bindParam('id', $book['book'], PDO::PARAM_INT);
								  $q2car->execute();
								$bookinfo=$q2car->fetch();
						          echo"<form method=\"POST\" name=\"carouselitem$i\" action=\"/\">
						            <input name=\"searchbook\" type=\"hidden\" value=\"".$bookinfo['titre']." ".$bookinfo['auteur']."\">
						            <input name=\"checktitre\" type=\"hidden\" value=\"1\">
						            <input name=\"checkauteur\" type=\"hidden\" value=\"1\">
						            <input name=\"checkdescr\" type=\"hidden\" value=\"0\">
						            <input name=\"checksujet\" type=\"hidden\" value=\"0\">
						            <input name=\"selecttype\" type=\"hidden\" value=\"0\">
						            <input name=\"selectfrom\" type=\"hidden\" value=\"".$bookinfo['user']."\">	
						            <input type=\"hidden\" name=\"searchchange\" value=\"go\">
						            <a style=\"cursor:pointer\" onclick=\"window.document.carouselitem$i.submit();\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>
						          </form>";
						
						
						
						//echo"<a style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#InfoBook$i\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>";
						//$modal[$i]=$book['book'];
						$i++;
					}
                	?>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            
            <div class="carousel-caption d-none d-md-block" style="height:320px;padding-top:320px">
                <div class="card mb-4" style="margin-top:50px">
                <h5 style="color:#000"><em>Les tendances</em></h5>
                </div>
                <div class="row mb-4 stage">
                	<?php
                	$querycar="SELECT book FROM ".$mysql_prefix."ebooks_infos ORDER BY dl DESC LIMIT 6;";
					  $qcar = $pdo->prepare($querycar);
					  $qcar->execute();
					while ($book=$qcar->fetch()) {
								$query2car="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
								  $q2car = $pdo->prepare($query2car);
								  $q2car->bindParam('id', $book['book'], PDO::PARAM_INT);
								  $q2car->execute();
								$bookinfo=$q2car->fetch();
						          echo"<form method=\"POST\" name=\"carouselitem$i\" action=\"/\">
						            <input name=\"searchbook\" type=\"hidden\" value=\"".$bookinfo['titre']." ".$bookinfo['auteur']."\">
						            <input name=\"checktitre\" type=\"hidden\" value=\"1\">
						            <input name=\"checkauteur\" type=\"hidden\" value=\"1\">
						            <input name=\"checkdescr\" type=\"hidden\" value=\"0\">
						            <input name=\"checksujet\" type=\"hidden\" value=\"0\">
						            <input name=\"selecttype\" type=\"hidden\" value=\"0\">
						            <input name=\"selectfrom\" type=\"hidden\" value=\"".$bookinfo['user']."\">	
						            <input type=\"hidden\" name=\"searchchange\" value=\"go\">
						            <a style=\"cursor:pointer\" onclick=\"window.document.carouselitem$i.submit();\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>
						          </form>";
						
						
						
						//echo"<a style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#InfoBook$i\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>";
						//$modal[$i]=$book['book'];
						$i++;
					}
                	?>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            
            <div class="carousel-caption d-none d-md-block" style="height:320px;padding-top:320px">
                <div class="card mb-4" style="margin-top:50px">
                <h5 style="color:#000"><em>Livres au hasard</em></h5>
                </div>
                <div class="row mb-4 stage">
                	<?php
                	$querycar="SELECT id FROM ".$mysql_prefix."ebooks ORDER BY RAND() LIMIT 6;";
					  $qcar = $pdo->prepare($querycar);
					  $qcar->execute();
					while ($book=$qcar->fetch()) {
								$query2car="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
								  $q2car = $pdo->prepare($query2car);
								  $q2car->bindParam('id', $book['id'], PDO::PARAM_INT);
								  $q2car->execute();
								$bookinfo=$q2car->fetch();
						          echo"<form method=\"POST\" name=\"carouselitem$i\" action=\"/\">
						            <input name=\"searchbook\" type=\"hidden\" value=\"".$bookinfo['titre']." ".$bookinfo['auteur']."\">
						            <input name=\"checktitre\" type=\"hidden\" value=\"1\">
						            <input name=\"checkauteur\" type=\"hidden\" value=\"1\">
						            <input name=\"checkdescr\" type=\"hidden\" value=\"0\">
						            <input name=\"checksujet\" type=\"hidden\" value=\"0\">						            
						            <input name=\"selecttype\" type=\"hidden\" value=\"0\">
						            <input name=\"selectfrom\" type=\"hidden\" value=\"".$bookinfo['user']."\">	
						            <input type=\"hidden\" name=\"searchchange\" value=\"go\">
						            <a style=\"cursor:pointer\" onclick=\"window.document.carouselitem$i.submit();\"><img src=\"img.php?idimg=".$book['id']."\" class=\"stage rounded\"></a>
						          </form>";
						
						
						
						//echo"<a style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#InfoBook$i\"><img src=\"img.php?idimg=".$book['book']."\" class=\"stage rounded\"></a>";
						//$modal[$i]=$book['book'];
						$i++;
					}
                	?>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
</div>
	<?php
/*	foreach ($modal as $key=>$value) { 
	    $query="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
		  $q = $pdo->prepare($query);
		  $q->bindParam('id', $value, PDO::PARAM_INT);
		  $q->execute();
		$book=$q->fetch();

	//<!-- Modal -->
	echo"<div id=\"InfoBook$key\" class=\"modal fade\" role=\"dialog\">
	  <div class=\"modal-dialog modal-lg\">
	    <div class=\"modal-content\">
	      <div class=\"modal-body\">
			<h5>".$book['titre']."</h5>
			<h6 class=\"text-muted\">".$book['auteur']."</h6>
			<em>".strip_tags($book['descr'])."</em>
	      </div>
	      <div class=\"modal-footer\">
	      	<a href=\"dl.php?idbook=".$book['id']."&clebook=".md5($book['filename'])."\" target=\"dlframe\" class=\"btn btn-info btn-sm\" data-dismiss=\"modal\">Télécharger</a>
	      </div>
	    </div>
	
	  </div>
	</div>";
	 } */?>