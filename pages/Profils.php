<div class="card p-3 mb-4">
<h4 class="text-center"><em>Utilisateurs</em></h4>
<em class="text-center">Les avatars sont liés à votre adresse email grâce au service <a href="https://fr.gravatar.com/" target="new">Gravatar</a>.<hr>
<h6>Dans cette boite à lire virtuelle entre amis et famille, vous pouvez :</h6>
</em>
<em>
<ul>
<li>emprunter des livres</li>
<li>en recommander en cliquant sur l'étoile</li>
<li>en ajouter / supprimer à votre liste de lecture en cliquant sur le ruban</li>
<li>si vous demandez à être contributeur, en ajouter (le demander si ce n'est pas le cas et que vous avez quelque chose à partager)</li>
<li>si vous êtes admin... tout faire :)</li>
</ul>
</em>
<?php if ($user['type']=="admin") { ?>
	<hr><button type="button" class="mb-4 btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#Modaladduser<?php echo $iduser; ?>"><i class="fas fa-user-plus"></i> Ajouter un utilisateur</button>
	<?php
}
?>
</div>

<?php


	$query="SELECT * FROM ".$mysql_prefix."users ORDER BY type;";
	$q = $pdo->prepare($query);
	$q->execute();
	$i=0;
	echo"<div class=\"row\">";
	while ($users=$q->fetch()) {
		if ($users['valid']==0&&$user['type']=="admin") {
			echo"<div class=\"col-6 mb-4\">";
			showuser($users['nick'],$users['email'],$users['type'],$users['id'],$users['valid'],$pdo,$mysql_prefix,$user);
			echo"</div>";
			$i++;
		}
		if ($users['valid']==1) {
			//echo"<div class=\"card mb-4 lg-6\">";
			echo"<div class=\"col-6 mb-4\"><div class=\"card\">";
			showuser($users['nick'],$users['email'],$users['type'],$users['id'],$users['valid'],$pdo,$mysql_prefix,$user);
			echo"</div></div>";
			$i++;
			//echo"</div>";
			//if ($i%2 != 1) echo"";
		}
		
		
	}
	echo"</div>";
?>


<?php if ($user['type']=="admin") { ?>
	<!-- Modal -->
	<div id="Modaladduser<?php echo $iduser; ?>" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	      	<h4 class="modal-title">Ajouter</h4>
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	      <div class="modal-body">
	        <p>Ajouter un utilisateur <?php echo $nick; ?>.</p>
	        <form name="adduser" method="POST">
	        <div class="form-group">
			  <label for="usr">Login</label>
			  <input name="cnick" type="text" class="form-control">
			</div>
			<div class="form-group">
			  <label for="pwd">Mot de passe</label>
			  <input name="cpass" type="password" class="form-control">
			</div>
			<div class="form-group">
			  <label for="usr">Email</label>
			  <input name="cemail" type="text" class="form-control">
			</div>
			<div class="form-group">
			  <label for="sel1">Type</label>
			  <select class="form-control" name="ctype">
			    <option>lecteur</option>
			    <option>contributeur</option>
			    <option>admin</option>
			  </select>
			</div>
			<input type="hidden" name="opsql" value="adduser">
	    	</form>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.document.adduser.submit()">Ajouter</button>
	        <button type="button" class="btn btn-dark" data-dismiss="modal">Annuler</button>
	      </div>
	    </div>
	
	  </div>
	</div>
<?php
}
?>