<?php if ($user['type']=="admin"||$user['type']=="contributeur") { ?>

<div class="card p-3 mb-4">
<h4 class="text-center"><em>Ajoutez vos livres</em></h4>
	<em class="text-center">Seul le format <b>epub</b> est accepté dans cette bibliothèque.</em><hr>
	<p>Selon le nombre de livres et la taille cela peut prendre beaucoup de temps, merci de <b>ne pas quitter cette page tant que la mise à jour de la bibliothèque n'est pas terminée, le défilement des livres s'arrêtera à ce moment</b>. Dans le cas contraire vos livres ne seront pas ajoutés.</p>
	<p>Afin de palier à déventuels soucis, merci de passer par <a href="https://calibre-ebook.com/" target="new">Calibre</a> et d'<b>exporter uniquement les fichiers epub propres et sans DRM </b>!</p>
	<p>Le site vérifiera si le livre existe déjà et récupèrera automatiquement les infos si possible, sinon il ne sera pas uploadé.</p>
	<p>Enfin, merci de réserver cet espace aux romans ;)</p>
	<?php Majspace($pdo,$mysql_prefix);showdiskspace($pdo,$mysql_prefix,$sizemax); ?>
	<span class="btn btn-lg btn-success fileinput-button">
        <i class="fa fa-book-medical"></i>
        <span>Ajoutez vos epubs</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" data-url="upload/" multiple>
    </span>
    <br>
    <br>
    <!-- The global progress bar -->
    <div id="progress" class="progress">
        <div class="progress-bar"></div>
    </div>
    <!-- The container for the uploaded files -->
    <div id="files" class="files"></div>
</div>
	<div id="addedbooks"></div>
	
<?php } ?>