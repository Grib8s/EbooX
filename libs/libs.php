<?php
function login($pass,$log,$pdo,$mysql_prefix) {
	$pass=md5($pass);
	$query="SELECT * FROM ".$mysql_prefix."users WHERE nick=:login AND pass=:pass AND valid=1;";
	  $q = $pdo->prepare($query);
	  $q->bindParam('login', $log, PDO::PARAM_STR);
	  $q->bindParam('pass', $pass, PDO::PARAM_STR);
	  $q->execute();
	$user=$q->fetch(); 
	$_SESSION['user']=$user;
}

function showbook($i,$titre,$auteur,$descr,$sujet,$filename,$path,$user=false,$page=false,$pdo=false,$mysql_prefix=false,$image=false) {
						$patterns = array("&", "/", " ",",");
						$string = str_replace($patterns, '-', $sujet);
						$mots = explode('-',$string);
						$pathto = implode('/', array_map('rawurlencode', explode('/', $path)));
						if ($page!="Books"||$page!="Favoris") $patho="../".$pathto;
		        foreach($mots as $mot)
		        {	
		        	if ($mot!="") echo "<form class=\"form-inline\" name=\"search".$mot.$i."\" method=\"POST\" action=\"/\"><input type=\"hidden\" name=\"searchbook\" value=\"".$mot."\"></form>";
		        }
				if($user['type']=="admin") { ?>
			       <form class="form-inline" name="delbook<?php echo $i; ?>" method="POST"><input type="hidden" name="deletebook" value="<?php echo $i; ?>"></form>
			    <?php } 
			    //gestion des favoris
			    if ($page=="Books"||$page=="Favoris") {?>
			       <form class="form-inline" name="addfav<?php echo $i; ?>" method="POST"><input type="hidden" name="addfavbook" value="<?php echo $i; ?>"></form>
			    <?php
			    }
			    if ($page=="Books"||$page=="Favoris") {?>
			       <form class="form-inline" name="dellfav<?php echo $i; ?>" method="POST"><input type="hidden" name="delfavbook" value="<?php echo $i; ?>"></form>
			    <?php
			    }
			    if ($page=="Books"||$page=="Favoris") {?>
			       <form class="form-inline" name="modifapi<?php echo $i; ?>" method="POST" action="?page=ModifBook"><input type="hidden" name="bookid" value="<?php echo $i; ?>"></form>
			    <?php
			    }
			    //vérifier si livre favori
				if ($page=="Books"||$page=="Favoris"||$page=="Outils") {
					$queryfav="SELECT * FROM ".$mysql_prefix."favoris WHERE book=:book;";
					$qfav = $pdo->prepare($queryfav);
					$qfav->bindParam('book', $i, PDO::PARAM_INT);
					$qfav->execute();
					$favorite=$qfav->fetch();
					$isfav=$qfav->rowCount();
					$queryuser="SELECT nick FROM ".$mysql_prefix."users WHERE id=:id;";
					$quser = $pdo->prepare($queryuser);
					$quser->bindParam('id', $favorite['user'], PDO::PARAM_INT);
					$quser->execute();
					$favoriteuser=$quser->fetch();
					if ($favoriteuser['nick']!="") $favtext="- Recommandé par ".$favoriteuser['nick'];
					else $favtext="";
				}
						//echo "<h6 style=\"cursor:pointer\" onclick=\"window.document.gotoauteur".$j.".submit()\">".$auteur['auteur']." ($nbl)</h6>";
				echo "<form name=\"gotoauteur".$i."\" method=\"POST\" action=\"/\">
				<input name=\"searchbook\" type=\"hidden\" value=\"".$auteur."\">
				<input name=\"checktitre\" type=\"hidden\" value=\"0\">
				<input name=\"checkdescr\" type=\"hidden\" value=\"0\">
				<input name=\"checksujet\" type=\"hidden\" value=\"0\">
				<input name=\"checkauteur\" type=\"hidden\" value=\"1\">
				<input name=\"checkauteurstrict\" type=\"hidden\" value=\"1\">
				</form>";
			    
    ?>
        <div class="col-12">
	        <div class="card mb-4">
	        	  
	        	<div class="row">
		        	<div class="col-lg-2 my-auto">
		        		<?php 
		        		if(file_exists('Books/'.$path.'cover.jpg')) $img_bk=('Books/'.$pathto.'cover.jpg');
		        		if($image!="") $img_bk=$image;
		        		if($img_bk=="") $img_bk='images/nocover.jpg';
		        		if ($page=="Books"||$page=="Favoris"||$page=="Outils") {
			        		if ($isfav==0) {
			        		?>
			        		<div class="bg-image">
				        		<img class ="d-none d-lg-block rounded" src="<?php echo $img_bk; ?>" width="100%" alt="Couverture">
								<h3><i class="far fa-star" style="cursor:pointer" onclick="window.document.addfav<?php echo $i; ?>.submit()"></i></h3>
	            			</div>
	            			<?php } else { ?>
	            			<div class="bg-image">
	            				<?php 
	            				if ($favorite['user']==$user['id']) {
	            					$stringdelfav =" onclick=\"window.document.dellfav".$i.".submit()\"";
	            				} else $stringdelfav="";
	            				?>
				        		<img class ="d-none d-lg-block rounded" src="<?php echo $img_bk; ?>" width="100%" alt="Couverture">
								<h3><i class="fa fa-star" style="cursor:pointer;color:orange"<?php echo $stringdelfav; ?>></i></h3>
	            			</div>
	            			<?php }
            			} else {
            				?>
            				<img class ="d-none d-lg-block rounded" src="<?php echo $img_bk; ?>" width="100%" alt="Couverture">
            				<?php
            			}
            			?>
		        	</div>
		        	<div class="col-lg-10 pl-4">
				      <h5><?php echo $titre; ?><small class="text-muted"> <em><?php echo $favtext; ?></em></small></h5>  		
		              <div><h6 class="text-muted" style="cursor:pointer" onclick="window.document.gotoauteur<?php echo $i; ?>.submit()"><?php echo $auteur; ?></h6></div>
		              <div style="height:126px;overflow:auto" class="pr-2"><p class="card-text mb-auto"><em><?php echo strip_tags($descr); ?></em></p></div>
		              <p><small>
		              <?php
		              

		        foreach($mots as $mot)
		        {	
		        	
					if ($mot!="")echo "<span class=\"badge badge-secondary d-inline\" style=\"cursor:pointer\" onclick=\"window.document.search".$mot.$i.".submit()\">".$mot."</span> ";
		        }
		              ?>
		              </small></p>
		              
		              <div class="text-center">
		              <h6>
		              	<?php 
		              	if ($page=="Books"||$page=="Favoris"||$page=="Outils") {
			              	if(file_exists('Books/'.$path.$filename)) { 
			            		if ($user['type']=="admin") echo "<span class=\"badge badge-info\"style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#ModifBook".$i."\"><i class=\"fa fa-edit\"></i> Edition</span> ";
			            		
			             ?>
			                  <a href="<?php echo "Books/".$pathto.$filename; ?>"><span class="badge badge-info"><i class="fa fa-download"></i> Télécharger</span></a> 
				            <?php 
				            } else { 
				            ?>
				              <span class="badge badge-danger">Lien cassé</span>
				            <?php echo $path;
				            }
		              	}
			            ?>
			            
		              </h6>     		
				      </div>
 		
		        	</div>
		        </div>
		        		              
	        </div>
	      </div>
        
    <?php
    if ($user['type']=="admin") {
    	
    	
    						//<!-- Modal -->
							echo"<div id=\"ModifBook".$i."\" class=\"modal fade\" role=\"dialog\">
							  <div class=\"modal-dialog\">
							
							    <div class=\"modal-content\">
							      <div class=\"modal-header\">
							      	<h4 class=\"modal-title\">Modifier le livre</h4>
							        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
							        
							      </div>
							      <div class=\"modal-body\">
							        <form name=\"modifbook".$i."\" method=\"POST\">
							          <div class=\"form-group\">
									    <label for=\"couv\">Nouvelle couverture</label>
									    <input type=\"text\" name=\"ccouv\" class=\"form-control form-control-sm\" id=\"couv\" value=\"\" placeholder=\"url de la nouvelle couverture\">
									  </div>
							          <div class=\"form-group\">
									    <label for=\"titre\">Titre</label>
									    <input type=\"text\" name=\"ctitre\" class=\"form-control form-control-sm\" id=\"titre\" value=\"".$titre."\">
									  </div>
									  <div class=\"form-group\">
									    <label for=\"auteur\">Auteur <small>Prénom Nom</small></label>
									    <input type=\"text\" name=\"cauteur\" class=\"form-control form-control-sm\" id=\"auteur\" value=\"".$auteur."\">
									  </div>
									  <div class=\"form-group\">
									    <label for=\"mclefs\">Mots clefs</label>
									    <input type=\"text\" name=\"csujet\" class=\"form-control form-control-sm\" id=\"mclefs\" value=\"".$sujet."\">
									  </div>
									  <div class=\"form-group\">
									    <label for=\"descr\">Description</label>
									    <textarea class=\"form-control form-control-sm\" name=\"cdescr\" id=\"descr\" rows=\"3\">".strip_tags($descr)."</textarea>
									  </div>
									  <input type=\"hidden\" name=\"opsql\" value=\"modifbook\">
									  <input type=\"hidden\" name=\"bookid\" value=\"".$i."\">
									</form>";
									//if ($user['type']=="admin") echo "<span class=\"badge badge-info\"style=\"cursor:pointer\" onclick=\"window.document.modifapi".$i.".submit()\"><i class=\"fa fa-edit\"></i> Edition avancée</span> ";
							    	
							    echo "<form name=\"recherchebabel".$i."\" action=\"https://www.babelio.com/resrecherche.php\" method=\"post\" target=\"newbabel\"><input type=\"hidden\" name=\"Recherche\" value=\"".skip_accents($titre." ".$auteur)."\"></form>";
								echo "<div class=\"row\"><div class=\"col-6\">";
								echo "<button class=\"btn btn-block btn-sm btn-info\" onclick=\"window.document.recherchebabel".$i.".submit()\"><i class=\"fas fa-link\"></i> Babelio</button>";
								echo "<a href=\"https://booknode.com/search?q=".$titre."\" class=\"btn btn-block btn-sm btn-info\" target=\"newnode\"><i class=\"fas fa-link\"></i> BookNode</a>";
								echo "</div><div class=\"col-6\">";
								echo "<a href=\"https://www.goodreads.com/search?utf8=✓&query=".$titre." ".$auteur."\" class=\"btn btn-block btn-sm btn-info\" target=\"newgr\"><i class=\"fas fa-link\"></i> Goodreads</a>";
								echo "<a href=\"https://www.livraddict.com/search.php?t=".$titre."\" class=\"btn btn-block btn-sm btn-info\" target=\"newla\"><i class=\"fas fa-link\"></i> Livraddict</a>";
							    echo "</div></div>"; 
							     
							      echo"</div>
							      <div class=\"modal-footer\">";
							      if ($user['type']=="admin") echo "<button type=\"button\" class=\"btn btn-danger\"data-dismiss=\"modal\" data-toggle=\"modal\" data-target=\"#SupprBook".$i."\">Supprimer</button>";
							      	echo"<button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\" onclick=\"window.document.modifbook".$i.".submit()\">Enregistrer</button>
							        <button type=\"button\" class=\"btn btn-dark\" data-dismiss=\"modal\">Annuler</button>
							      </div>
							    </div>
							
							  </div>
							</div>";
							//<!-- fin modal -->
							//<!-- Modal -->
							echo"<div id=\"SupprBook".$i."\" class=\"modal fade\" role=\"dialog\">
							  <div class=\"modal-dialog\">
							
							    <div class=\"modal-content\">
							      <div class=\"modal-header\">
							      	<h4 class=\"modal-title\">Supprimer le livre ?</h4>
							        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
							        
							      </div>
							      <div class=\"modal-body\">
							        <p>Etes vous certain de vouloir supprimer <b>$titre</b> de <b>$auteur</b> de la bibliothèque ?</p>
							        <p class=\"text-danger\">Cela est définitif !</p>
							      </div>
							      <div class=\"modal-footer\">";
							      	echo"<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\" onclick=\"window.document.delbook".$i.".submit()\">Supprimer</button>
							        <button type=\"button\" class=\"btn btn-dark\" data-dismiss=\"modal\">Annuler</button>
							      </div>
							    </div>
							
							  </div>
							</div>";
							//<!-- fin modal -->
    	
    }
    
}

function showuser($nick,$mail,$type,$iduser,$activ,$pdo,$mysql_prefix,$user) {
    $query="SELECT COUNT(*) AS tot FROM ".$mysql_prefix."ebooks WHERE user=:id;";
	  $q = $pdo->prepare($query);
	  $q->bindParam('id', $iduser, PDO::PARAM_INT);
	  $q->execute();
	$nbb=$q->fetch(); 
	
	$query2="SELECT COUNT(*) AS tot FROM ".$mysql_prefix."favoris WHERE user=:id;";
	  $q2 = $pdo->prepare($query2);
	  $q2->bindParam('id', $iduser, PDO::PARAM_INT);
	  $q2->execute();
	$nbf=$q2->fetch(); 
    
    
    ?>
        
	        <div class="card mb-4">
	        	  
	        	<div class="row">
		        	<div class="col-lg-2 my-auto"><img class ="d-none d-lg-block rounded" src="https://www.gravatar.com/avatar/<?php echo md5($mail); ?>?s=200&d=<?php echo urlencode('http://'.$_SERVER['SERVER_NAME'].'/images/avatar.png'); ?>" width="100%" alt="Profil"></div>
		        	<div class="col-lg-10 pl-4">
				      <h5><?php echo $nick; ?></h5>  		
		              <div class="text-muted"><?php echo $mail; ?></div>
		              <div class="text-muted"><?php echo $type; ?></div>
					  <div class="text-muted">Nombre de livres : <?php echo $nbb['tot']; ?> / Favoris : <?php echo $nbf['tot']; ?></div>
					  <?php if ($user['type']=="admin"||$iduser==$user['id']) { ?>
					  <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#Modalmodif<?php echo $iduser; ?>">Modifier</button>
					  <?php } 
					  if ($user['type']=="admin") { ?>
		              <?php if ($activ==1) { ?>
		              <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#Modaldelete<?php echo $iduser; ?>">Supprimer</button>
		              <?php } ?>
		              <?php if ($activ==0) { ?>
		              <button type="button" class="btn btn-sm btn-dark" data-toggle="modal" data-target="#Modalreactiv<?php echo $iduser; ?>">Réactiver</button>
		              <?php } ?>

							<!-- Modal -->
							<div id="Modaldelete<?php echo $iduser; ?>" class="modal fade" role="dialog">
							  <div class="modal-dialog">
							
							    <!-- Modal content-->
							    <div class="modal-content">
							      <div class="modal-header">
							      	<h4 class="modal-title">Supprimer</h4>
							        <button type="button" class="close" data-dismiss="modal">&times;</button>
							        
							      </div>
							      <div class="modal-body">
							        <p>Voulez vous supprimer l'utilisateur <?php echo $nick; ?> ?</p>
							        <form name="deleteuser<?php echo $iduser; ?>" method="POST">
							        	<input type="hidden" name="opsql" value="deleteuser">
							        	<input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
							        </form>
							      </div>
							      <div class="modal-footer">
							      	<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="window.document.deleteuser<?php echo $iduser; ?>.submit()">Supprimer</button>
							        <button type="button" class="btn btn-dark" data-dismiss="modal">Annuler</button>
							      </div>
							    </div>
							
							  </div>
							</div>
							<!-- fin modal -->
							<!-- Modal -->
							<div id="Modalreactiv<?php echo $iduser; ?>" class="modal fade" role="dialog">
							  <div class="modal-dialog">
							
							    <!-- Modal content-->
							    <div class="modal-content">
							      <div class="modal-header">
							      	<h4 class="modal-title">Réactiver</h4>
							        <button type="button" class="close" data-dismiss="modal">&times;</button>
							        
							      </div>
							      <div class="modal-body">
							        <p>Voulez vous réactiver l'utilisateur <?php echo $nick; ?> ?</p>
							        <form name="reactivuser<?php echo $iduser; ?>" method="POST">
							        	<input type="hidden" name="opsql" value="reactivuser">
							        	<input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
							        </form>
							      </div>
							      <div class="modal-footer">
							      	<button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.document.reactivuser<?php echo $iduser; ?>.submit()">Réactiver</button>
							        <button type="button" class="btn btn-dark" data-dismiss="modal">Annuler</button>
							      </div>
							    </div>
							
							  </div>
							</div>
							<!-- fin modal -->
						<?php } 
						if ($user['type']=="admin"||$iduser==$user['id']) { ?>
							<!-- Modal -->
							<div id="Modalmodif<?php echo $iduser; ?>" class="modal fade" role="dialog">
							  <div class="modal-dialog">
							
							    <!-- Modal content-->
							    <div class="modal-content">
							      <div class="modal-header">
							      	<h4 class="modal-title">Modifier</h4>
							        <button type="button" class="close" data-dismiss="modal">&times;</button>
							        
							      </div>
							      <div class="modal-body">
							        <p>Modifier les informations pour <?php echo $nick; ?>.</p>
							            <form name="modifuser<?php echo $iduser; ?>" method="POST">
								        <div class="form-group">
										  <label for="usr">Login</label>
										  <input name="cnick" type="text" class="form-control" value="<?php echo $nick; ?>">
										</div>
										<div class="form-group">
										  <label for="pwd">Mot de passe</label>
										  <input name="cpass" type="password" class="form-control">
										</div>
										<div class="form-group">
										  <label for="usr">Email</label>
										  <input name="cemail" type="text" class="form-control" value="<?php echo $mail; ?>">
										</div>
										<?php if ($user['type']=="admin") { ?>
										<div class="form-group">
										  <label for="sel1">Type</label>
										  <select class="form-control" name="ctype">
										    <option<?php if ($type=="lecteur") echo " selected"; ?>>lecteur</option>
										    <option<?php if ($type=="contributeur") echo " selected"; ?>>contributeur</option>
										    <option<?php if ($type=="admin") echo " selected"; ?>>admin</option>
										  </select>
										</div>
										<?php }
										else echo "<input type=\"hidden\" name=\"ctype\" value=\"".$type."\">"; ?>
										<input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
										<input type="hidden" name="opsql" value="modifuser">
								    	</form>
							      </div>
							      <div class="modal-footer">
							      	<button type="button" class="btn btn-info" data-dismiss="modal" onclick="window.document.modifuser<?php echo $iduser; ?>.submit()">Modifier</button>
							        <button type="button" class="btn btn-dark" data-dismiss="modal">Annuler</button>
							      </div>
							    </div>
							
							  </div>
							</div>
							<!-- fin modals -->
							<?php } ?>
							
				      </div>
 		
		        	</div>
		        </div>
		        		              

    <?php
}

function showpagination ($p,$pmax,$page) {
	?>
		<div class="mx-auto">
			<nav aria-label="..." class="centered">
			  <ul class="pagination">
			    <li class="page-item <?php if ($p==1) echo "disabled";?>">
			      <a class="page-link" href="?page=<?php echo $page; ?>&p=1"><i class="fas fa-angle-double-left"></i></a>
			    </li>
			    <li class="page-item <?php if ($p==1) echo "disabled";?>">
			      <a class="page-link" href="?page=<?php echo $page; ?>&p=<?php echo $p-1;?>"><i class="fas fa-angle-left"></i></a>
			    </li>
			    <?php
			    $plinktop=$p+3;
			    $plinkmin=$p-3;
			    for ($i=$plinkmin;$i<=$plinktop;$i++) {
			    	if ($i<=$pmax&&$i>=1) {
				    	if ($i==$p) $active=" active"; else $active="";
				    	echo "<li class=\"page-item$active\"><a class=\"page-link\" href=\"?page=$page&p=$i\">$i</a></li>";
			    	}
			    }
			    
			    ?>
			    <li class="page-item <?php if ($p>=$pmax) echo "disabled";?>">
			      <a class="page-link" href="?page=<?php echo $page; ?>&p=<?php echo $p+1;?>"><i class="fas fa-angle-right"></i></a>
			    </li>
			    <li class="page-item <?php if ($p>=$pmax) echo "disabled";?>">
			      <a class="page-link" href="?page=<?php echo $page; ?>&p=<?php echo $pmax;?>"><i class="fas fa-angle-double-right"></i></a>
			    </li>
			  </ul>
			</nav>
		</div>
<?php } 

function del_empty_folder($path) {
  $empty=true;
  foreach (glob($path.DIRECTORY_SEPARATOR."*") as $file)
  {
     if (is_dir($file))
     {
        if (!del_empty_folder($file)) $empty=false;
     }
     else
     {
        $empty=false;
     }
  }
  if ($empty) rmdir($path);
  return $empty;
}
function clear_dir($dir, $delete = false) {
    $dossier = $dir;
    $dir = opendir($dossier); 
    while($file = readdir($dir)) { 
        if(!in_array($file, array(".", ".."))){
            if(is_dir("$dossier/$file")) {
                clear_dir("$dossier/$file", true);
            } else {
                unlink("$dossier/$file");
            }
             
             
        }
    } 
    closedir($dir);
     
    if($delete == true) {
        rmdir("$dossier/$file");
    }
}
function compareStrings($s1, $s2) {
    //one is empty, so no result
    if (strlen($s1)==0 || strlen($s2)==0) {
        return 0;
    }
	similar_text($s1, $s2, $percent);
    //replace none alphanumeric charactors
    //i left - in case its used to combine words
    $s1clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s1);
    $s2clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s2);

    //remove double spaces
    while (strpos($s1clean, "  ")!==false) {
        $s1clean = str_replace("  ", " ", $s1clean);
    }
    while (strpos($s2clean, "  ")!==false) {
        $s2clean = str_replace("  ", " ", $s2clean);
    }

    //create arrays
    $ar1 = explode(" ",$s1clean);
    $ar2 = explode(" ",$s2clean);
    $l1 = count($ar1);
    $l2 = count($ar2);

    //flip the arrays if needed so ar1 is always largest.
    if ($l2>$l1) {
        $t = $ar2;
        $ar2 = $ar1;
        $ar1 = $t;
    }

    //flip array 2, to make the words the keys
    $ar2 = array_flip($ar2);


    $maxwords = max($l1, $l2);
    $matches = 0;

    //find matching words
    foreach($ar1 as $word) {
        if (array_key_exists($word, $ar2))
            $matches++;
    }
	if ($percent>(($matches / $maxwords) * 100)) $retour=$percent; else $retour=($matches / $maxwords) * 100;
    return $retour;    
}


function skip_accents( $str, $charset='utf-8' ) {
 
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );
    
    return $str;
}


function modifcouverture ($idbook,$image,$pdo,$mysql_prefix) {
	// trouver le chemin de l'image finale
	$query="SELECT pathfile FROM ".$mysql_prefix."ebooks WHERE id=:id;";
	$q = $pdo->prepare($query);
	$q->bindParam('id', $idbook, PDO::PARAM_INT);
	$q->execute();
	$book=$q->fetch();
	
	$outputImage="Books/".$book['pathfile']."cover.jpg";
	//convertir l'image en jpg
    $exploded = explode('.',$image);
    $ext = $exploded[count($exploded) - 1]; 

    if (preg_match('/jpg|jpeg/i',$ext))
        $imageTmp=imagecreatefromjpeg($image);
    else if (preg_match('/png/i',$ext))
        $imageTmp=imagecreatefrompng($image);
    else if (preg_match('/gif/i',$ext))
        $imageTmp=imagecreatefromgif($image);
    else
        return 0;
        
    // redimensionner l'image
    $pattern = imagecreatetruecolor(250, 400);
    $dimensions = getimagesize($image);
	imagecopyresampled($pattern, $imageTmp, 0, 0, 0, 0, 250, 400, $dimensions[0], $dimensions[1]);
	imagedestroy($imageTmp);
	// enregistrer l'image
	imagejpeg($pattern, $outputImage, 100);
}
function Space_size($Bytes)
{
  $Type=array("", "K", "M", "G", "T", "P", "E", "Z", "Y");
  $Index=0;
  while($Bytes>=1024)
  {
    $Bytes/=1024;
    $Index++;
  }
  return("".sprintf('%1.2f' ,$Bytes)." ".$Type[$Index]."bits");
}

function showdiskspace() {
	$path = "/";
	$freespace=disk_free_space($path);
	$totalspace=disk_total_space($path);
	$usedspace=$totalspace-$freespace;
	$percentusedspace=sprintf('%1.2f' ,100*$usedspace/$totalspace);
	echo "<div class=\"progress\">
	  <div class=\"progress-bar bg-info\" role=\"progressbar\" style=\"width: ".$percentusedspace."%\" aria-valuenow=\"".$percentusedspace."\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>
	</div>";
	echo "<p class=\"text-muted\"><i>".Space_size($usedspace)." utilisés, ".Space_size($freespace)." libres sur ".Space_size($totalspace)." au total (".$percentusedspace."%)</i></p>";
}
?>