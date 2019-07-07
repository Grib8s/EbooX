    <?php 
    
    
    
    
include ("pages/carousel.php"); 



  // RECHERCHE DE LIVRES ------------------------------------------------------------------
if ($user['type']!="") {

	if ($_POST['searchchange']=="go") {
		$p=1;
		$_SESSION['p']=$p;
	} else $p=$_SESSION['p'];
	
	if ($p<=0) $p=1;
	$_SESSION['p']=$p;
	
    	// créé la requette de recherche
    if ($_POST['searchchange']=="go") {	
    		if ($_POST['checktitre']<2) $_SESSION['searchtitre']=$_POST['checktitre']; else $_SESSION['searchtitre']=1;
			if ($_POST['checkauteur']<2) $_SESSION['searchauteur']=$_POST['checkauteur']; else $_SESSION['searchauteur']=1;
			if ($_POST['checkauteurstrict']<2) $_SESSION['searchauteurstrict']=$_POST['checkauteurstrict']; else $_SESSION['searchauteurstrict']=0;
			if ($_POST['checkdescr']<2) $_SESSION['searchdescr']=$_POST['checkdescr']; else $_SESSION['searchdescr']=1;
			if ($_POST['checksujet']<2) $_SESSION['searchsujet']=$_POST['checksujet']; else $_SESSION['searchsujet']=1;
			if (isset($_POST['selecttype'])) $_SESSION['searchtype']=$_POST['selecttype']; else $_SESSION['searchtype']=0;
			if (isset($_POST['selectfrom'])) $_SESSION['searchfrom']=$_POST['selectfrom']; else $_SESSION['searchfrom']=0;
    }

			$checktitre=$_SESSION['searchtitre'];
			$checkauteur=$_SESSION['searchauteur'];
			$checkauteurstrict=$_SESSION['searchauteurstrict'];
			$checkdescr=$_SESSION['searchdescr'];
			$checksujet=$_SESSION['searchsujet'];
			$selecttype=$_SESSION['searchtype'];
			$selectfrom=$_SESSION['searchfrom'];
			
			$_SESSION['searchtitre']=$checktitre;
			$_SESSION['searchauteur']=$checkauteur;
			$_SESSION['searchauteurstrict']=$checkauteurstrict;
			$_SESSION['searchdescr']=$checkdescr;
			$_SESSION['searchsujet']=$checksujet;
			$_SESSION['searchtype']=$selecttype;
			$_SESSION['searchfrom']=$selectfrom;
    	
    	
    	
    	
	
		if(isset($_POST['searchbook'])) {
			$searchbook=$_POST['searchbook'];
		}
		else $searchbook=$_SESSION['searchbook'];
			$_SESSION['searchbook']=$searchbook;


}




	
    ?>
    <div class="card p-3 mb-4">
    	
    	<h1 class="slideflag" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<em>En ce moment</em>"><i class="fas fa-bookmark" id="toggleslide" style="cursor:pointer;color:#17A2B8;" data-toggle="collapse" href="#collapseslide" role="button" aria-expanded="false" aria-controls="collapseExample"></i></h1>
    	
	  <?php 
	  
	  
	  
	  echo "<h4 class=\"text-center\"><em>Livres</em>
	  <i class=\"fas fa-search btn btn-info\" id=\"togglesearch\" style=\"cursor:pointer;\" data-toggle=\"collapse\" href=\"#formsearchdiv\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample\"></i>
	  </h4><hr><form name=\"searchspec\" class=\"col-md-10 col-lg-12 mx-auto\" id=\"formulairerech\"><em>";
	  

	  
	  
	  	if ($_SESSION['searchauteurstrict']==1) $addtexts=" l'auteur"; else $addtexts="";
	  	/*echo "<div class=\"col-auto row d-flex justify-content-center\">
	  	<a class=\"btn btn-sm btn-info col-auto\" style=\"cursor:pointer;color:#fff\" onclick=\"window.document.searchdel.submit()\">Supprimer la recherche</a>
	  	</div>";
	  	*/

		  	
		    	
		  	
	echo"<div class=\"form-group row\">
	  	<label for=\"selectselection\" class=\"col-md-3 col-lg-2 col-form-label\">Selection </label>
	  <div class=\"col-md-9 col-lg-4\">	
	  <select class=\"form-control form-control-sm\" id=\"selectselection\" name=\"selecttype\">";
	  $selecttext=array("<i class=\"fa fa-book\"></i>Tous les livres", "Recommandations", "Liste de lecture");
		foreach ($selecttext as $key => $value) {
				if ($key==$_SESSION['searchtype']) $selected=" selected"; else $selected="";
				echo "<option value=\"$key\"$selected>".$value."</option>";
			}
	  echo "</select></div>";
	  
	echo"
		<label for=\"selectpersonne\" class=\"col-md-3 col-lg-2 col-form-label\">Personne </label>
		<div class=\"col-md-9 col-lg-4\">
	  <select class=\"form-control form-control-sm\" id=\"selectpersonne\" name=\"selectfrom\">
	  <option value=\"0\">Tout le monde</option>";
	$query="SELECT DISTINCT ".$mysql_prefix."users.* FROM ".$mysql_prefix."users,".$mysql_prefix."favoris,".$mysql_prefix."listelec,".$mysql_prefix."ebooks WHERE 
	".$mysql_prefix."favoris.user=".$mysql_prefix."users.id OR 
	".$mysql_prefix."listelec.user=".$mysql_prefix."users.id OR 
	".$mysql_prefix."ebooks.user=".$mysql_prefix."users.id ORDER BY ".$mysql_prefix."users.nick;";

	$q = $pdo->prepare($query);
	$q->execute();
	while ($usersel=$q->fetch())  {

		if ($usersel['id']==$_SESSION['searchfrom']) $selected=" selected"; else $selected="";
		echo "<option value=\"".$usersel['id']."\"$selected>".$usersel['nick']."</option>";
		
	}
	  echo "</select></div>";

	  echo"
	  <label for=\"selectclassm\" class=\"col-md-3 col-lg-2 col-form-label\">Classement </label>
	  <div class=\"col-md-9 col-lg-4\">
	  <select class=\"form-control form-control-sm\" id=\"selectclassm\" name=\"selectclass\">";
	  if ($_SESSION['class']=="titre") $selectt=" selected"; else $selectt="";
	  if ($_SESSION['class']=="auteur") $selectat=" selected"; else $selectat="";
	  if ($_SESSION['class']=="aleatoire") $selecta=" selected"; else $selecta="";
	  if ($_SESSION['class']=="nouveautee") $selectn=" selected"; else $selectn="";
	  echo"<option value=\"t\"$selectt>Titre</option>
	  <option value=\"at\"$selectat>Auteur</option>
	  <option value=\"a\"$selecta>Aléatoire</option>
	  <option value=\"n\"$selectn>Nouveautées</option>";
	  echo"</select></div>";
	  
	  echo"
	  <label for=\"selectnbpp\" class=\"col-md-3 col-lg-2 col-form-label\">Nombre par page </label>
	  <div class=\"col-md-9 col-lg-4\">
	  <select class=\"form-control form-control-sm\" id=\"selectnbpp\" name=\"selectnbpp\">";
	  for ($i=1;$i<=10;$i++) {
	  	$n=$i*10;
	  	if ($_SESSION['searchlimit']==$n) $select=" selected"; else $select="";
	  	echo"<option $select>$n</option>";
	  }
	  echo"</select></div></div>";
	  
	  if ($_SESSION['searchbook']!="") $showclass=" show"; else $showclass="";
	  
	  
	  
	  echo"<div class=\"collapse$showclass\" id=\"formsearchdiv\"><div class=\"form-group row\">
		<label for=\"textsearch\" class=\"col-md-3 col-lg-2 col-form-label\">Rechercher $addtexts </label>
		<div class=\"col-md-9 col-lg-10\">
			<input name=\"searchbook\" id=\"textsearch\" class=\"form-control\" type=\"text\" placeholder=\"Recherche\" value=\"".$_SESSION['searchbook']."\">
		</div>
		</div>";
	  	
	  	
	  	
	  	//echo "<h5>Rechercher $addtexts : \"".$search."\"</h5>";
	  
	  
	  	//echo"<input name=\"searchbook\" class=\"form-control input-lg\" type=\"text\" placeholder=\"Recherche\" value=\"$search\">";
	  
	  
	  	if ($_SESSION['searchtitre']==1) $check1=" checked"; else $check1="";
	  	if ($_SESSION['searchauteur']==1) $check2=" checked"; else $check2="";
	  	if ($_SESSION['searchdescr']==1) $check3=" checked"; else $check3="";
	  	if ($_SESSION['searchsujet']==1) $check4=" checked"; else $check4="";
	  	
		  	echo "<div class=\"form-group row\">
		  	<label for=\"checkchamps\" class=\"col-md-3 col-lg-2 col-form-label\">Champs </label>
		  	<div class=\"col-md-9 col-lg-10\">
			  	<div class=\"d-inline custom-control custom-checkbox ml-2\">
				  <input class=\"custom-control-input\" id=\"TitreCheck\" type=\"checkbox\" name=\"checktitre\" value=\"1\"$check1>
				  <label class=\"custom-control-label\" for=\"TitreCheck\">Titre</label>
				</div>
			  	<div class=\"d-inline custom-control custom-checkbox ml-2\">
				  <input class=\"custom-control-input\" id=\"AuteurCheck\" type=\"checkbox\" name=\"checkauteur\" value=\"1\"$check2>
				  <label class=\"custom-control-label\" for=\"AuteurCheck\">Auteur</label>
				</div>
				<div class=\"d-inline custom-control custom-checkbox ml-2\">
				  <input class=\"custom-control-input\" id=\"DescrCheck\" type=\"checkbox\" name=\"checkdescr\" value=\"1\"$check3>
				  <label class=\"custom-control-label\" for=\"DescrCheck\">Description</label>
				</div>
			  	<div class=\"d-inline custom-control custom-checkbox ml-2\">
				  <input class=\"custom-control-input\" id=\"SujetCheck\" type=\"checkbox\" name=\"checksujet\" value=\"1\"$check4>
				  <label class=\"custom-control-label\" for=\"SujetCheck\">Mots clefs</label>
				</div>
			</div>
		  	</div></em>";
	  
echo"<div class=\"form-group row\">";
 echo"<input type=\"hidden\" name=\"searchchange\" value=\"go\">";
 echo"</form>";
 

echo "<div class=\"col-lg-12\">
<form name=\"searchdel\" id=\"formulairedel\" method=\"POST\">
<input type=\"hidden\" name=\"delsearchbook\" value=\"1\"><input type=\"submit\" class=\"btn btn-block btn-dark\" value=\"Supprimer\">
</form></div>
</div></div>";
  	
echo "</div>";
	  	
	  
	  ?>
	</div>
	

	
<div id="Renderbooks" style="height:100%;width:100%;"></div>