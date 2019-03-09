<?php if (isset($_POST['bookid'])) { ?>
<div class="card p-3 mb-4"><h3>Modification avancée</h3>
<p>Work in progress !!!</p>
</div>
<div class="row">
<?php
// livre à modifier
$booktomodif=$_POST['bookid'];
$query="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
$q = $pdo->prepare($query);
$q->bindParam('id', $booktomodif, PDO::PARAM_INT);
$q->execute();
$book=$q->fetch();

showbook($book['id'],$book['titre'],$book['auteur'],$book['descr'],$book['sujet'],$book['filename'],$book['pathfile'],$user,$page,$pdo,$mysql_prefix);



							        echo"</div><div class=\"card p-3 mb-4\"><form name=\"modifbook".$i."\" method=\"POST\">
							          <div class=\"form-group\">
									    <label for=\"titre\">Titre</label>
									    <input type=\"text\" name=\"ctitre\" class=\"form-control\" id=\"titre\" value=\"".$book['titre']."\">
									  </div>
									  <div class=\"form-group\">
									    <label for=\"auteur\">Auteur <small>Prénom Nom</small></label>
									    <input type=\"text\" name=\"cauteur\" class=\"form-control\" id=\"auteur\" value=\"".$book['auteur']."\">
									  </div>
									  <div class=\"form-group\">
									    <label for=\"mclefs\">Mots clefs</label>
									    <input type=\"text\" name=\"csujet\" class=\"form-control\" id=\"mclefs\" value=\"".$book['sujet']."\">
									  </div>
									  <div class=\"form-group\">
									    <label for=\"descr\">Description</label>
									    <textarea class=\"form-control\" name=\"cdescr\" id=\"descr\" rows=\"3\">".$book['descr']."</textarea>
									  </div>
									  <input type=\"hidden\" name=\"opsql\" value=\"modifbook\">
									  <input type=\"hidden\" name=\"bookid\" value=\"".$book['id']."\">
									</form></div>";








// recherche des infos via google book
include('libs/BookSearcher.class.php');

$googleBook = new BookSearcher();

// Exemple de recherche par mot clés //
$livres = $googleBook->getBooksByKeyword($book['titre']." ".$book['auteur']);

echo "<div class=\"card p-3 mb-4\"><h3>Résultat de recherche via l'API Google</h3></div><div class=\"row\">";
$i=0;
	showbook($i,$livres[$i]['titre'],$livres[$i]['auteur'],$livres[$i]['description'],$livres[$i]['sujet'],'','',$user,$page,$pdo,$mysql_prefix,$livres[$i]['image']);
echo "</div><div class=\"card p-3 mb-4\"><h3>Rechercher ailleurs sur le net</h3></div>";
echo "<form name=\"recherchebabel\" action=\"https://www.babelio.com/resrecherche.php\" method=\"post\" target=\"newbabel\"><input type=\"hidden\" name=\"Recherche\" value=\"".$book['titre']." ".$book['auteur']."\"></form>";
echo "<button class=\"btn btn-block btn-info\" onclick=\"window.document.recherchebabel.submit()\">Recherche sur Babelio</button>";
echo "<a href=\"https://booknode.com/search?q=".$book['titre']."\" class=\"btn btn-block btn-info\" target=\"newnode\">Recherche sur BookNode</a>";
echo "<a href=\"https://www.goodreads.com/search?utf8=✓&query=".$book['titre']." ".$book['auteur']."\" class=\"btn btn-block btn-info\" target=\"newgr\">Recherche sur Goodreads</a>";
echo "<a href=\"https://www.livraddict.com/search.php?t=".$book['titre']."\" class=\"btn btn-block btn-info\" target=\"newla\">Recherche sur Livraddict</a>";
https://www.livraddict.com/search.php?t=
/* Exemple de recherche par ISBN //
echo '<h1>Example de recherche par ISBN</h1>';
$livre = $googleBook->getBookByISBN('2844272592');

echo 'Livre (2844272592)<br />';
echo '<pre>';
	print_r($livre);
echo '</pre><br />';*/

?>

<?php } ?>