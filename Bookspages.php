<?

// CONNEXION ET SECURISATION
session_start();
$user=$_SESSION['user'];
include ("conf/config.inc.php");
include ("libs/libs.php");
//include ("libs/opsql.php");
try {
    $pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_dbase.'', $mysql_user, $mysql_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

if ($user['type']!="") {

$page=$_GET['page'];
// PAGE EN COURS ---------------------------------------------------------------------
if (isset($_GET['p'])&&$_GET['p']>=1) $_SESSION['p']=$_GET['p'];
$p=$_SESSION['p'];
if ($_POST['searchchange']=="go") $p=1;
$_SESSION['p']=$p;
// CLASSEMENT DE LIVRES ---------------------------------------------------------------
if ($_POST['selectclass']=="a") $_SESSION['class']="aleatoire";
if ($_POST['selectclass']=="t") $_SESSION['class']="titre";
if ($_POST['selectclass']=="n") $_SESSION['class']="nouveautee";
if ($_POST['selectclass']=="at") $_SESSION['class']="auteur";
if ($_POST['selectnbpp']>0) $_SESSION['searchlimit']=$_POST['selectnbpp'];

if ($_SESSION['searchlimit']>0) $limitsearch=$_SESSION['searchlimit']; else $limitsearch=$limit;
$_SESSION['searchlimit']=$limitsearch;

if (!$_SESSION['class']) $_SESSION['class']="titre";
$eboox_class=$_SESSION['class'];
$_SESSION['class']=$eboox_class;

$start=0;
//if ($_GET['p']) $p=$_GET['p']; else $p=1;
$start=($p*$limitsearch)-$limitsearch;

    if ($eboox_class=="titre") {
    	$classtext="Classement par titre";
    	$classsql="titre LIMIT $start,$limitsearch";
    }
    if ($eboox_class=="nouveautee") {
    	$classtext="Classement par nouveautées";
    	$classsql="date DESC, id DESC LIMIT $start,$limitsearch";
    }
    if ($eboox_class=="aleatoire") {
    	$classtext="Livres aléatoires, seulement $limitsearch livres affichés";
    	$classsql="RAND() LIMIT $limitsearch";
    }
    if ($eboox_class=="auteur") {
    	$classtext="Classement par auteur";
    	$classsql="auteur LIMIT $start,$limitsearch";
    }


  // RECHERCHE DE LIVRES ------------------------------------------------------------------
if ($user['type']!="") {
	// effacer la recherche
	if ($_POST['delsearchbook']=="1") {
		unset($_SESSION['searchbook']);
		unset($_SESSION['searchtitre']);
		unset($_SESSION['searchauteur']);
		unset($_SESSION['searchauteurstrict']);
		unset($_SESSION['searchdescr']);
		unset($_SESSION['searchsujet']);

		
	}
	

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
    	
    	
    	
    	
	if(($_POST['searchbook']!=""||$_POST['searchbook']!=" "||$_SESSION['searchbook']!="")&&$user['type']!="") {
		if(isset($_POST['searchbook'])) {
			$searchbook=$_POST['searchbook'];
		}
		else $searchbook=$_SESSION['searchbook'];
			$_SESSION['searchbook']=$searchbook;



		
		
		$search=strip_tags($searchbook);
		
		if (($checktitre==1||$checkauteur==1||$checkdescr==1||$checksujet==1)&&$search!="") {
			//$request = ' WHERE (';
			$request = ' AND ( CONCAT(';
			$mots = explode(' ',$search);
			
			if ($selectype>0) {
				$reqbdd=array("ebooks", "favoris", "listelec");
				$reqdatab=$mysql_prefix.$reqbdd[$selecttype].".";
			}
			else $reqdatab="";
			
			if ($checkauteurstrict==1) $request .= $reqdatab.'auteur) = "'.$search.'")';
			else {
			
				$i=0;
				foreach($mots as $mot) {
					if ($request!=" AND ( CONCAT(") $request.=' AND CONCAT(';
					if ($checktitre==1) {
						if ($request!=" WHERE ( CONCAT("&&$i>0) $request.=', " ",';
						$request.=$reqdatab.'titre';
						$i++;
					}
					if ($checkauteur==1) {
						if ($request!=" WHERE ( CONCAT("&&$i>0) $request.=', " ",';
						$request.=$reqdatab.'auteur';
						$i++;
					}
					if ($checkdescr==1) {
						if ($request!=" WHERE ( CONCAT("&&$i>0) $request.=', " ",';
						$request.=$reqdatab.'descr';
						$i++;
					}
					if ($checksujet==1) {
						if ($request!=" WHERE ( CONCAT("&&$i>0) $request.=', " ",';
						$request.=$reqdatab.'sujet';
						$i++;
					}
					$request.=') LIKE "%'.$mot.'%"';
					$i=0;
				}
				$request .= ')';
			}
			
		}
		

		if($selectfrom>0) {
			$reqbdd=array("ebooks", "favoris", "listelec");
			$request .= " AND ".$mysql_prefix.$reqbdd[$selecttype].".user=".$selectfrom;
		} 



	} else {
		$request="";
		$search="";
	}
}

	// trouve le nombre de pages à afficher
	$reqbdd=array("ebooks", "favoris", "listelec");
	if ($selecttype>0) $querym="SELECT DISTINCT ".$mysql_prefix."ebooks.* FROM ".$mysql_prefix."ebooks,".$mysql_prefix.$reqbdd[$selecttype]." WHERE ".$mysql_prefix."ebooks.id=".$mysql_prefix.$reqbdd[$selecttype].".book".$request.";";
	if ($selecttype==0) $querym="SELECT * FROM ".$mysql_prefix."ebooks WHERE 1=1".$request.";";
	//echo $querym;
	$qm = $pdo->prepare($querym);
	$qm->execute();
    $pmax = ceil ($qm->rowCount()/$limitsearch);
    
    // affiche les livres
    $reqbdd=array("ebooks", "favoris", "listelec");
	if ($selecttype>0) $query="SELECT DISTINCT ".$mysql_prefix."ebooks.* FROM ".$mysql_prefix."ebooks,".$mysql_prefix.$reqbdd[$selecttype]." WHERE ".$mysql_prefix."ebooks.id=".$mysql_prefix.$reqbdd[$selecttype].".book".$request." ORDER BY ".$classsql.";";
    if ($selecttype==0) $query="SELECT * FROM ".$mysql_prefix."ebooks WHERE 1=1".$request." ORDER BY ".$classsql.";"; 
    
	//$query="SELECT * FROM ".$mysql_prefix."ebooks".$request." ORDER BY ".$classsql.";";
	//echo $query; // pour s'aider dans les requettes :)
	$q = $pdo->prepare($query);
	$q->execute();
	if ($q->rowcount()>0) {
 ?>
 
	
<div class="row">
      <?php 

		if ($eboox_class!="aleatoire") showpagination($p,$pmax,$page); 
		$i=0;
		while ($book=$q->fetch()) {
			showbook($book['id'],$book['titre'],$book['auteur'],$book['descr'],$book['sujet'],$book['filename'],$book['pathfile'],$user,$page,$pdo,$mysql_prefix); 
			$i++;
		}
	      
	    if ($eboox_class!="aleatoire") showpagination($p,$pmax,$page);  


	
echo "</div>";
	}
	else {
		echo "<div class=\"alert alert-dark col-12\" role=\"alert\">Aucun livre ne correspond à votre recherche..</div>"; 
		//echo$query;
	}
	
}
?>



