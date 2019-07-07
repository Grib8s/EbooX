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
	if ($q->rowCount()==0) return false;
}

function showbook($i,$titre,$auteur,$descr,$sujet,$filename,$path,$user=false,$page=false,$pdo=false,$mysql_prefix=false,$image=false) {
						$patterns = array("&", "/", " ",",");
						$string = str_replace($patterns, '-', $sujet);
						$mots = explode('-',$string);
						//$pathto = implode('/', array_map('rawurlencode', explode('/', $path)));
						//if ($page!="Books"||$page!="Favoris") $patho="../".$pathto;
		        foreach($mots as $mot)
		        {	
		        	if ($mot!="") echo "<form class=\"form-inline\" name=\"search".$mot.$i."\" method=\"POST\" action=\"/\"><input type=\"hidden\" name=\"searchbook\" value=\"".$mot."\">
		        	<input type=\"hidden\" name=\"checktitre\" value=\"0\"><input type=\"hidden\" name=\"checkauteur\" value=\"0\"><input type=\"hidden\" name=\"checkdescr\" value=\"0\"><input type=\"hidden\" name=\"checkauteur\" value=\"0\">
		        	<input type=\"hidden\" name=\"checksujet\" value=\"1\"><input type=\"hidden\" name=\"searchchange\" value=\"go\"></form>";
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
			    //gestion des listes de lecture
			    if ($page=="Books"||$page=="Favoris") {?>
			       <form class="form-inline" name="addll<?php echo $i; ?>" method="POST"><input type="hidden" name="addllbook" value="<?php echo $i; ?>"></form>
			    <?php
			    }
			    if ($page=="Books"||$page=="Favoris") {?>
			       <form class="form-inline" name="dellll<?php echo $i; ?>" method="POST"><input type="hidden" name="delllbook" value="<?php echo $i; ?>"></form>
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
					$isfav=$qfav->rowCount();
					
					$queryll="SELECT * FROM ".$mysql_prefix."listelec WHERE book=:book AND user=:user;";
					$qll = $pdo->prepare($queryll);
					$qll->bindParam('book', $i, PDO::PARAM_INT);
					$qll->bindParam('user', $user['id'], PDO::PARAM_INT);
					$qll->execute();
					if ($qll->rowCount()>0) {
						$styleicon="fas fa-bookmark";
						$stringshelve=" onclick=\"window.document.dellll".$i.".submit()\"";
						$color="color:red;";
					} else {
						$styleicon="fas fa-bookmark";
						$stringshelve=" onclick=\"window.document.addll".$i.".submit()\"";
						$color="";
					}
					
					
					$jnb=0;
					$isuserfav="no";
					while ($favorite=$qfav->fetch()) {
						$queryuser="SELECT id,nick FROM ".$mysql_prefix."users WHERE id=:id;";
						$quser = $pdo->prepare($queryuser);
						$quser->bindParam('id', $favorite['user'], PDO::PARAM_INT);
						$quser->execute();
						$favoriteuser=$quser->fetch();
						if ($favoriteuser['nick']!="") {
							if ($jnb==0) $favtext="Recommandé par ";
							if ($jnb>0) $favtext.=", ";
							$favtext.=$favoriteuser['nick'];
							if ($user['id']==$favorite['user']) $isuserfav=1;
							$jnb++;
						}
					}
					
				}
						//echo "<h6 style=\"cursor:pointer\" onclick=\"window.document.gotoauteur".$j.".submit()\">".$auteur['auteur']." ($nbl)</h6>";
				echo "<form name=\"gotoauteur".$i."\" method=\"POST\" action=\"/\">
				<input name=\"searchbook\" type=\"hidden\" value=\"".$auteur."\">
				<input name=\"checktitre\" type=\"hidden\" value=\"0\">
				<input name=\"checkdescr\" type=\"hidden\" value=\"0\">
				<input name=\"checksujet\" type=\"hidden\" value=\"0\">
				<input name=\"checkauteur\" type=\"hidden\" value=\"1\">
				<input name=\"checkauteurstrict\" type=\"hidden\" value=\"1\">
				<input type=\"hidden\" name=\"searchchange\" value=\"go\">
				</form>";
			    
    ?>
        <div class="col-12">
	        <div class="card mb-4">
	        	  
	        	<div class="row">
		        	<div class="col-lg-2 my-auto">
		        		<?php 
		        		if(file_exists('Books/'.$path.'cover.jpg')) $img_bk="img.php?idimg=".$i;
		        		else $img_bk='img.php?idimg=0';
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
	            				if ($isuserfav==1) $stringdelfav =" onclick=\"window.document.dellfav".$i.".submit()\"";
	            				else $stringdelfav=" onclick=\"window.document.addfav".$i.".submit()\"";
	            				?>
				        		<img class ="d-none d-lg-block rounded" src="<?php echo $img_bk; ?>" width="100%" alt="Couverture">
								<h3><i class="fa fa-star" style="cursor:pointer;color:orange"<?php echo $stringdelfav; ?>><span class="badge-notify" style="font-size:16px;color:black"><?php echo $isfav; ?></span></i></h3>
	            			
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
					<h4 class="shelve"><i class="<?php echo $styleicon; ?>" style="cursor:pointer;<?php echo $color; ?>"<?php echo $stringshelve; ?>></i></h4>
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
			                  <?php echo"<a href=\"dl.php?idbook=$i&clebook=".md5($filename)."\" target=\"dlframe\"><span class=\"badge badge-info\"><i class=\"fa fa-download\"></i> Télécharger</span></a>"; ?>
			                  <?php //echo "<a href=\"Books/".$pathto.$filename."\"><span class=\"badge badge-info\"><i class=\"fa fa-download\"></i> Télécharger</span></a>"; ?>
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

function showuser($nick,$mail,$type,$iduser,$activ,$pdo,$mysql_prefix,$user,$style=false) {
    $query="SELECT COUNT(*) AS tot FROM ".$mysql_prefix."ebooks WHERE user=:id;";
	  $q = $pdo->prepare($query);
	  $q->bindParam('id', $iduser, PDO::PARAM_INT);
	  $q->execute();
	$nbb=$q->fetch(); 
	$page=$_GET['page'];
	$query2="SELECT COUNT(*) AS tot FROM ".$mysql_prefix."favoris WHERE user=:id;";
	  $q2 = $pdo->prepare($query2);
	  $q2->bindParam('id', $iduser, PDO::PARAM_INT);
	  $q2->execute();
	$nbf=$q2->fetch(); 
	$query3="SELECT COUNT(*) AS tot FROM ".$mysql_prefix."listelec WHERE user=:id;";
	  $q3 = $pdo->prepare($query3);
	  $q3->bindParam('id', $iduser, PDO::PARAM_INT);
	  $q3->execute();
	$nbl=$q3->fetch(); 
    
    ?>
        
	        
	        	 
	        	
	        	<div class="row">
		        	<?php
		        	if ($style!="menu") {
		        	?>
		        	<div class="col-lg-4 my-auto">
		        	<?php
		        	}
		        	else echo"<div class=\"col-12 mx-1 my-auto\">";
		        	?>
		        		<img class ="d-none d-lg-block rounded" src="https://www.gravatar.com/avatar/<?php echo md5($mail); ?>?s=200&d=<?php echo urlencode('http://'.$_SERVER['SERVER_NAME'].'/images/'.$type.'.jpg'); ?>" width="100%" height:"100%" style="min-width:150px;min-height:150px" alt="Profil">
						<div style="position:absolute;top:0;right:20px;"><span class="badge badge-info"><?php echo $type; ?></span></div>
		        	</div>
					<?php

		        	if ($style!="menu") {
		        	?>
		        	<div class="col-lg-8 pl-4">
		        	<?php
		        	} else echo"<div class=\"col-12 mx-1\">";
		        	?>
				      <h5><?php echo $nick; ?></h5>  		
		              <div class="text-muted"><?php //echo $mail; ?></div>
		              
					  <div class="row pl-4">
					  	
					  	<div class="text-muted d-inline mx-1"><small><i class="fa fa-book"></i> <?php echo $nbb['tot']; ?></small></div>
					  	<div class="text-muted d-inline mx-1"><small><i class="fa fa-star" style="color:orange"></i> <?php echo $nbf['tot']; ?></small></div>
					  	<div class="text-muted d-inline mx-1"><small><i class="fas fa-bookmark" style="color:red"></i> <?php echo $nbl['tot']; ?></small></div>	
					  	
					  </div>
					  <?php 
					  
					  
					  if ($user['type']=="admin"&&$style!="menu") { ?>
						<div class="dropdown dropup" style="position:absolute;bottom:5px;right:25px;">
						  <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <i class="fas fa-cog"></i>
						  </button>
						  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#ModalmodifU<?php echo $iduser; ?>">Modifier</a>
						    <?php 
						    
						    if ($activ==1&&$user['type']=="admin") { ?>
						    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#Modaldelete<?php echo $iduser; ?>">Supprimer</a>
						    <?php } ?>
		            		<?php if ($activ==0&&$user['type']=="admin"&&$style!="menu") { ?>
						    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#Modalreactiv<?php echo $iduser; ?>">Réactiver</a>
						    <?php } ?>
						  </div>
						</div>
					  <?php
					  }
					  

					  if ($user['type']=="admin"&&$style!="menu") { ?>


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
						if (($user['type']=="admin"&&$iduser!=$user['id'])) { 
						
							modifusertab($type,$iduser,$mail,$nick);
						}
							?>
							
				      </div>

				</div>

		        	
		        
		        		              

    <?php
}
function modifusertab($type,$iduser,$mail,$nick) {
	?>
							<!-- Modal -->
							<div id="ModalmodifU<?php echo $iduser; ?>" class="modal fade" role="dialog" tabindex="-1">
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
							<?php
}
function showpagination ($p,$pmax,$page) {
	?>
		<div class="mx-auto">
			<nav aria-label="..." class="centered">
			  <ul class="pagination">
			    <li class="page-item <?php if ($p==1) echo "disabled";?>">
			      <a class="changepage page-link" href="#" onclick="changepage(1)"><i class="fas fa-angle-double-left"></i></a>
			    </li>
			    <li class="page-item <?php if ($p==1) echo "disabled";?>">
			      <a class="changepage page-link" href="#" onclick='changepage(<?php echo $p-1;?>)'><i class="fas fa-angle-left"></i></a>
			    </li>
			    <?php
			    $plinktop=$p+3;
			    $plinkmin=$p-3;
			    for ($i=$plinkmin;$i<=$plinktop;$i++) {
			    	if ($i<=$pmax&&$i>=1) {
				    	if ($i==$p) $active=" active"; else $active="";
				    	echo "<li class=\"page-item$active\"><a class=\"changepage page-link\" href=\"#\" onclick='changepage($i)'>$i</a></li>";
			    	}
			    }
			    
			    ?>
			    <li class="page-item <?php if ($p>=$pmax) echo "disabled";?>">
			      <a class="changepage page-link" href="#" onclick='changepage(<?php echo $p+1;?>)'><i class="fas fa-angle-right"></i></a>
			    </li>
			    <li class="page-item <?php if ($p>=$pmax) echo "disabled";?>">
			      <a class="changepage page-link" href="#" onclick='changepage(<?php echo $pmax;?>)'><i class="fas fa-angle-double-right"></i></a>
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

function showdiskspace($pdo,$mysql_prefix,$sizemax) {
	$path = "/";
		$query="select sum(size) as total from ".$mysql_prefix."ebooks_infos;";
		$q = $pdo->prepare($query);
		$q->execute();
		$size=$q->fetch(); 
		//echo Space_size($size['total']);
	
	if ($sizemax==0) $totalspace=disk_total_space($path); else $totalspace=$sizemax*1024*1024*1024;
	$usedspace=$size['total'];
	$freespace=$totalspace-$usedspace;
	$percentusedspace=sprintf('%1.2f' ,100*$usedspace/$totalspace);
	echo "<div class=\"progress\">
	  <div class=\"progress-bar bg-info\" role=\"progressbar\" style=\"width: ".$percentusedspace."%\" aria-valuenow=\"".$percentusedspace."\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>
	</div>";
	echo "<p class=\"text-muted\"><i>".Space_size($usedspace)." utilisés, ".Space_size($freespace)." libres sur ".Space_size($totalspace)." au total (".$percentusedspace."%)</i></p>";
}
function Majspace($pdo,$mysql_prefix,$id=false){
	// trouver les fichiers n'ayant pas d'entrée select * from t2 where not exists (select null from t1 where t1.id = t2.id);
	if ($id==false) {
		//$query="SELECT * FROM ".$mysql_prefix."ebooks WHERE id NOT IN (SELECT book FROM ".$mysql_prefix."ebooks_infos);";
		$query="select id,pathfile,filename from ".$mysql_prefix."ebooks where not exists (select * from ".$mysql_prefix."ebooks_infos where ".$mysql_prefix."ebooks_infos.book = ".$mysql_prefix."ebooks.id);";
		$q = $pdo->prepare($query);
		$q->execute();
		while ($book=$q->fetch()) {
			$space=0;
			$spacejpg=0;
			$spacefile=0;
			if (file_exists("Books/".$book['pathfile'].'cover.jpg')) $spacejpg=filesize("Books/".$book['pathfile'].'cover.jpg'); else $spacejpg=0;
			if (file_exists("Books/".$book['pathfile'].$book['filename'])) $spacefile=filesize("Books/".$book['pathfile'].$book['filename']); else $spacefile=0;
			$space=$spacejpg+$spacefile;
			$query2 = "INSERT INTO ".$mysql_prefix."ebooks_infos (book, size) 
		    VALUES (:book, :size);";
		    $q2 = $pdo->prepare($query2);
		    $q2->bindParam('book', $book['id'], PDO::PARAM_INT);
		    $q2->bindParam('size', $space, PDO::PARAM_INT);
		    $q2->execute();
		}
		// effacer les livres supprimés
		$query="select id from ".$mysql_prefix."ebooks_infos where not exists (select * from ".$mysql_prefix."ebooks where ".$mysql_prefix."ebooks_infos.book = ".$mysql_prefix."ebooks.id);";
		$q = $pdo->prepare($query);
		$q->execute();
		while ($book=$q->fetch()) {
			$query2 = "DELETE FROM ".$mysql_prefix."ebooks_infos WHERE id=:id;";
		    $q2 = $pdo->prepare($query2);
		    $q2->bindParam('id', $book['id'], PDO::PARAM_INT);
		    $q2->execute();
		}
		//afficher l'espace total;
		/*$query="select sum(size) as total from ".$mysql_prefix."ebooks_infos;";
		$q = $pdo->prepare($query);
		$q->execute();
		$size=$q->fetch(); 
		echo Space_size($size['total']);*/
	}	
	// remplacer seulement un fichier
	if ($id!=false&&$dl==false) {
		$query="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
		$q = $pdo->prepare($query);
		$q->bindParam('id', $id, PDO::PARAM_INT);
		$q->execute();
		$book=$q->fetch();
		$space=0;
		$spacejpg=0;
		$spacefile=0;
		if (file_exists("Books/".$book['pathfile'].'cover.jpg')) $spacejpg=filesize("Books/".$book['pathfile'].'cover.jpg'); else $spacejpg=0;
		if (file_exists("Books/".$book['pathfile'].$book['filename'])) $spacefile=filesize("Books/".$book['pathfile'].$book['filename']); else $spacefile=0;
		$space=$spacejpg+$spacefile;
		$query = "UPDATE ".$mysql_prefix."ebooks_infos SET size=:size WHERE id=:id;";
		$q = $pdo->prepare($query);
		$q->bindParam('size', $space, PDO::PARAM_INT);
		$q->bindParam('id', $id, PDO::PARAM_INT);
		$q->execute();
	}
	
	

}


?>