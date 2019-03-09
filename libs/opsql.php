<?php
// connexion à la bdd
try {
    $pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_dbase.'', $mysql_user, $mysql_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
// UTILISATEUR --------------------------------------------------------------------
//login
if ($_POST['pass']&&$_POST['login']) login($_POST['pass'],$_POST['login'],$pdo,$mysql_prefix);
//logout
if ($_GET['logout']==1) {
	session_destroy();
	header("Refresh:0, url=/");
}
// renouvellement de la session
if ($_SESSION['user']) {
	$user=$_SESSION['user'];
	$query="SELECT * FROM ".$mysql_prefix."users WHERE id=:id;";
	  $q = $pdo->prepare($query);
	  $q->bindParam('id', $user['id'], PDO::PARAM_INT);
	  $q->execute();
	$user=$q->fetch(); 
	$_SESSION['user']=$user;
	// nettoyage du fichier d'uploads perso
	if (file_exists('upload/files/'.$user['id'])) del_empty_folder ('upload/files/'.$user['id']); 
}
if ($user['type']=="admin") { 
	//ajout utilisateur
	if ($_POST['cnick']!=""&&$_POST['cemail']!=""&&$_POST['cpass']!=""&&$_POST['opsql']=="adduser") {
		$pass=md5($_POST['cpass']);
		
		$query = "SELECT id FROM ".$mysql_prefix."users WHERE nick = :login;";
		$q = $pdo->prepare($query);
		$q->bindParam('login', $_POST['cnick'], PDO::PARAM_STR);
		$q->execute();			
		if ($q->rowCount()>0) {
			$message_error="stop";
		}
		if ($message_error!="stop") {
			$query = "INSERT INTO ".$mysql_prefix."users (nick, pass, email, type, valid) 
		    VALUES (:nick, :pass, :email, :type, 1);";
		    $q = $pdo->prepare($query);
		    $q->bindParam('nick', $_POST['cnick'], PDO::PARAM_STR);
		    $q->bindParam('pass', $pass, PDO::PARAM_STR);
		    $q->bindParam('email', $_POST['cemail'], PDO::PARAM_STR);
		    $q->bindParam('type', $_POST['ctype'], PDO::PARAM_STR);
		    $q->execute();
		}
	}
}
// modification utilisateur
if (($user['type']=="admin"||$_POST['iduser']==$user['id'])&&$_POST['cnick']!=""&&$_POST['cemail']!=""&&$_POST['opsql']=="modifuser") {
	if ($_POST['cpass']!="") {
		$pass=md5($_POST['cpass']);
		$sqlpass="pass=:pass,";
	} else $sqlpass="";
	if ($user['type']=="admin") $sqltype="type=:type,"; else $sqltype="";
	
		$query = "SELECT * FROM ".$mysql_prefix."users WHERE nick = :login AND id!=:id;";
		$q = $pdo->prepare($query);
		$q->bindParam('login', $_POST['cnick'], PDO::PARAM_STR);
		$q->bindParam('id', $_POST['iduser'], PDO::PARAM_INT);
		$q->execute();			
		if ($q->rowCount()>0) {
			$message_error="stop";
		}
	if ($message_error!="stop") {
		$query = "UPDATE ".$mysql_prefix."users SET 
		email=:email,
		".$sqlpass."
		".$sqltype."
		nick=:nick 
		WHERE id=:id;";
		$q = $pdo->prepare($query);
		$q->bindParam('nick', $_POST['cnick'], PDO::PARAM_STR);
		$q->bindParam('id', $_POST['iduser'], PDO::PARAM_INT);
		$q->bindParam('email', $_POST['cemail'], PDO::PARAM_STR);
		if ($sqltype!="") $q->bindParam('type', $_POST['ctype'], PDO::PARAM_STR);
		if ($sqlpass!="") $q->bindParam('pass', $pass, PDO::PARAM_STR);
		$q->execute();
	}
}
//désactivation utilisateur
if ($user['type']=="admin"&&$_POST['iduser']!=""&&$_POST['opsql']=="deleteuser") {
	$query = "UPDATE ".$mysql_prefix."users SET valid=0 WHERE id=:id;";
	$q = $pdo->prepare($query);
	$q->bindParam('id', $_POST['iduser'], PDO::PARAM_INT);
	$q->execute();	
}

//réactivation utilisateur
if ($user['type']=="admin"&&$_POST['iduser']!=""&&$_POST['opsql']=="reactivuser") {
	$query = "UPDATE ".$mysql_prefix."users SET valid=1 WHERE id=:id;";
	$q = $pdo->prepare($query);
	$q->bindParam('id', $_POST['iduser'], PDO::PARAM_INT);
	$q->execute();	
}
// CLASSEMENT DE LIVRES ---------------------------------------------------------------
if ($_GET['class']=="a") $_SESSION['class']="aleatoire";
if ($_GET['class']=="t") $_SESSION['class']="titre";
if ($_GET['class']=="n") $_SESSION['class']="nouveautee";
if ($_GET['class']=="at") $_SESSION['class']="auteur";

if (!$_SESSION['class']) $_SESSION['class']="titre";
$eboox_class=$_SESSION['class'];
$_SESSION['class']=$eboox_class;

$limit=20;
$start=0;
if ($_GET['p']) $p=$_GET['p']; else $p=1;
$start=($p*$limit)-$limit;

if ($page=="Favoris") $addsqlt=$mysql_prefix."ebooks."; else $addsqlt="";

    if ($eboox_class=="titre") {
    	$classtext="Classement par titre";
    	$classsql=$addsqlt."titre LIMIT $start,$limit";
    }
    if ($eboox_class=="nouveautee") {
    	$classtext="Classement par nouveautées";
    	$classsql=$addsqlt."date DESC, id DESC LIMIT $start,$limit";
    }
    if ($eboox_class=="aleatoire") {
    	$classtext="Livres aléatoires, seulement $limit livres affichés";
    	$classsql="RAND() LIMIT $limit";
    }
    if ($eboox_class=="auteur") {
    	$classtext="Classement par auteur";
    	$classsql=$addsqlt."auteur LIMIT $start,$limit";
    }

// RECHERCHE DE LIVRES ------------------------------------------------------------------
if ($page=="Books") {
	// effacer la recherche
	if ($_POST['delsearchbook']=="1") {
		unset($_SESSION['searchbook']);
		unset($_SESSION['searchtitre']);
		unset($_SESSION['searchauteur']);
		unset($_SESSION['searchauteurstrict']);
		unset($_SESSION['searchdescr']);
		unset($_SESSION['searchsujet']);
		
	}
	
	//gérer les options de recherche
	if($_POST['searchchange']=="go") {
		$_SESSION['searchtitre']=$_POST['checktitre'];
		$_SESSION['searchauteur']=$_POST['checkauteur'];
		$_SESSION['searchauteurstrict']=$_POST['checkauteurstrict'];
		$_SESSION['searchdescr']=$_POST['checkdescr'];
		$_SESSION['searchsujet']=$_POST['checksujet'];
		
	} 

	// créé la requette de recherche
	if($_POST['searchbook']!=""||$_POST['searchbook']!=" "||$_SESSION['searchbook']!="") {
		if(isset($_POST['searchbook'])) {
			$searchbook=$_POST['searchbook'];
			if (isset($_POST['checktitre'])) $_SESSION['searchtitre']=$_POST['checktitre']; else $_SESSION['searchtitre']=1;
			if (isset($_POST['checkauteur'])) $_SESSION['searchauteur']=$_POST['checkauteur']; else $_SESSION['searchauteur']=1;
			if (isset($_POST['checkauteurstrict'])) $_SESSION['searchauteurstrict']=$_POST['checkauteurstrict']; else $_SESSION['searchauteurstrict']=0;
			if (isset($_POST['checkdescr'])) $_SESSION['searchdescr']=$_POST['checkdescr']; else $_SESSION['searchdescr']=1;
			if (isset($_POST['checksujet'])) $_SESSION['searchsujet']=$_POST['checksujet']; else $_SESSION['searchsujet']=1;
		}
		else $searchbook=$_SESSION['searchbook'];
		$_SESSION['searchbook']=$searchbook;
			$checktitre=$_SESSION['searchtitre'];
			$checkauteur=$_SESSION['searchauteur'];
			$checkauteurstrict=$_SESSION['searchauteurstrict'];
			$checkdescr=$_SESSION['searchdescr'];
			$checksujet=$_SESSION['searchsujet'];
			$_SESSION['searchtitre']=$checktitre;
			$_SESSION['searchauteur']=$checkauteur;
			$_SESSION['searchauteurstrict']=$checkauteurstrict;
			$_SESSION['searchdescr']=$checkdescr;
			$_SESSION['searchsujet']=$checksujet;
		
		
		$search=strip_tags($searchbook);
		
		if ($checktitre==1||$checkauteur==1||$checkdescr==1||$checksujet==1) {
			$request = ' WHERE (';
			$mots = explode(' ',$search);
			if ($checktitre==1) {
				foreach($mots as $mot)
	        	{
	                $request .= ' titre LIKE "%'.$mot.'%" AND';
	        	} 
	        	$request .= ' 1=1)';
			}
			if ($checkauteur==1) {
				if ($request!=" WHERE (") $request .= ' OR (';
				if ($checkauteurstrict==1) $request .= ' auteur = "'.$search.'" AND';
				else foreach($mots as $mot)
	        	{
	                $request .= ' auteur LIKE "%'.$mot.'%" AND';
	        	} 
	        	$request .= ' 1=1)';
			}
			if ($checkdescr==1) {
				if ($request!=' WHERE (') $request .= ' OR (';
				foreach($mots as $mot)
		        {
		                $request .= ' descr LIKE "%'.$mot.'%" AND';
		        }
		        $request .= ' 1=1)';
			}
			if ($checksujet==1) {
				if ($request!=' WHERE (') $request .= ' OR (';
				foreach($mots as $mot)
		        {
		                $request .= ' sujet LIKE "%'.$mot.'%" AND';
		        }
		        $request .= ' 1=1)';
			}
		  
		} else $request="";    
		
	} else {
		$request="";
		$search="";
	}
}	
	// trouve le nombre de pages à afficher
	if ($page=="Books") $querym="SELECT * FROM ".$mysql_prefix."ebooks".$request.";";
	if ($page=="Favoris") $querym="SELECT ".$mysql_prefix."ebooks.* FROM ".$mysql_prefix."ebooks,".$mysql_prefix."favoris WHERE ".$mysql_prefix."ebooks.id=".$mysql_prefix."favoris.book;";
	//echo $querym;
	$qm = $pdo->prepare($querym);
	$qm->execute();
    $pmax = ceil ($qm->rowCount()/$limit);


// OPERATIONS SUR LES LIVRES ------------------------------------------------------------
// modifier un livre
if ($_POST['opsql']=="modifbook"&&$user['type']=="admin") {
	if ($_POST['opsql']!="") modifcouverture($_POST['bookid'],$_POST['ccouv'],$pdo,$mysql_prefix);
	$query = "UPDATE ".$mysql_prefix."ebooks SET titre=:titre, auteur=:auteur, sujet=:sujet, descr=:descr WHERE id=:id;";
	$q = $pdo->prepare($query);
	$q->bindParam('titre', $_POST['ctitre'], PDO::PARAM_STR);
	$q->bindParam('auteur', $_POST['cauteur'], PDO::PARAM_STR);
	$q->bindParam('sujet', $_POST['csujet'], PDO::PARAM_STR);
	$q->bindParam('descr', $_POST['cdescr'], PDO::PARAM_STR);
	$q->bindParam('id', $_POST['bookid'], PDO::PARAM_INT);
	$q->execute();
}

// effacer un livre
if (isset($_POST['deletebook'])&&$user['type']=="admin") {
	$querydel="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
	$qdel = $pdo->prepare($querydel);
	$qdel->bindParam('id', $_POST['deletebook'], PDO::PARAM_INT);
	$qdel->execute();
	$delbook=$qdel->fetch();
	unlink('Books/'.$delbook['pathfile'].$delbook['filename']);
	if (file_exists('Books/'.$delbook['pathfile'].'cover.jpg')) unlink('Books/'.$delbook['pathfile'].'cover.jpg');
	$querydel2 = "DELETE FROM ".$mysql_prefix."ebooks WHERE id=:id;";
    $qdel2 = $pdo->prepare($querydel2);
    $qdel2->bindParam('id', $_POST['deletebook'], PDO::PARAM_INT);
    $qdel2->execute();
    $query = "DELETE FROM ".$mysql_prefix."favoris WHERE book=:book;";
    $q = $pdo->prepare($query);
    $q->bindParam('book', $_POST['deletebook'], PDO::PARAM_INT);
    $q->execute();
	if (file_exists('Books/'.$delbook['pathfile'])) del_empty_folder ('Books/'.$delbook['pathfile']);
}

// ajouter en favoris
if (isset($_POST['addfavbook'])) {
	$query = "INSERT INTO ".$mysql_prefix."favoris (book, user) 
    VALUES (:book, :user);";
    $q = $pdo->prepare($query);
    $q->bindParam('book', $_POST['addfavbook'], PDO::PARAM_INT);
    $q->bindParam('user', $user['id'], PDO::PARAM_INT);
    $q->execute();
}

// supprimer le favoris
if (isset($_POST['delfavbook'])) {
	$query = "DELETE FROM ".$mysql_prefix."favoris WHERE book=:book;";
    $q = $pdo->prepare($query);
    $q->bindParam('book', $_POST['delfavbook'], PDO::PARAM_INT);
    $q->execute();
}
// ajouter un nodoublon
if ($_POST['doublbook']!=""&&$user['type']=="admin") {
	$date=date('Y-m-d H:i:s');
	$querydbl = "INSERT INTO ".$mysql_prefix."doubloncheck (titre, date) 
    VALUES (:titre, :date);";
    $qdbl = $pdo->prepare($querydbl);
    $qdbl->bindParam('titre', $_POST['doublbook'], PDO::PARAM_STR);
    $qdbl->bindParam('date', $date, PDO::PARAM_STR);
    $qdbl->execute();
}
// OPERATIONS SUR LES AUTEURS ----------------------------------------------------------------
//ajout d'un auteur vérifié
if ($_POST['addauteurvalid']!=""&&$user['type']=="admin") {
	$query = "INSERT INTO ".$mysql_prefix."auteurvalid (auteur) VALUES (:auteur);";
    $q = $pdo->prepare($query);
    $q->bindParam('auteur', $_POST['addauteurvalid'], PDO::PARAM_STR);
    $q->execute();
}
//modification du nom d'un auteur
if ($_POST['modifauteurname']!=""&&$user['type']=="admin") {
	$query = "UPDATE ".$mysql_prefix."ebooks SET auteur=:auteur WHERE auteur=:prevauteur;";
	$q = $pdo->prepare($query);
	$q->bindParam('prevauteur', $_POST['prevauteurname'], PDO::PARAM_STR);
	$q->bindParam('auteur', $_POST['modifauteurname'], PDO::PARAM_STR);
	$q->execute();
	$query = "UPDATE ".$mysql_prefix."auteurvalid SET auteur=:auteur WHERE auteur=:prevauteur;";
	$q = $pdo->prepare($query);
	$q->bindParam('prevauteur', $_POST['prevauteurname'], PDO::PARAM_STR);
	$q->bindParam('auteur', $_POST['modifauteurname'], PDO::PARAM_STR);
	$q->execute();
	for ($i=0;$i<$_POST['nbchoix'];$i++){
    	if ($_POST['auteurtomodif'.$i.'']!="") {
    		//echo $_POST['auteurtomodif'.$i.''];
			$query = "UPDATE ".$mysql_prefix."ebooks SET auteur=:auteur WHERE auteur=:auteurtomodif;";
			$q = $pdo->prepare($query);
			$q->bindParam('auteurtomodif', $_POST['auteurtomodif'.$i.''], PDO::PARAM_STR);
			$q->bindParam('auteur', $_POST['modifauteurname'], PDO::PARAM_STR);
			$q->execute();	
    	}
    }
}
// ajout d'un auteur vérifié mutliple
if ($_POST['opsql']=="validmultiauteur"&&$user['type']=="admin") {
	$query = "INSERT INTO ".$mysql_prefix."auteurvalid (auteur) VALUES (:auteur);";
    $q = $pdo->prepare($query);
    $q->bindParam('auteur', $_POST['auteurref'], PDO::PARAM_STR);
    $q->execute();
    for ($i=0;$i<$_POST['nbchoix'];$i++){
    	if ($_POST['auteurtomodif'.$i.'']!="") {
    		//echo $_POST['auteurtomodif'.$i.''];
			$query = "UPDATE ".$mysql_prefix."ebooks SET auteur=:auteur WHERE auteur=:auteurtomodif;";
			$q = $pdo->prepare($query);
			$q->bindParam('auteurtomodif', $_POST['auteurtomodif'.$i.''], PDO::PARAM_STR);
			$q->bindParam('auteur', $_POST['auteurref'], PDO::PARAM_STR);
			$q->execute();
			$query = "UPDATE ".$mysql_prefix."auteurvalid SET auteur=:auteur WHERE auteur=:auteurtomodif;";
			$q = $pdo->prepare($query);
			$q->bindParam('auteurtomodif', $_POST['auteurtomodif'.$i.''], PDO::PARAM_STR);
			$q->bindParam('auteur', $_POST['auteurref'], PDO::PARAM_STR);
			$q->execute();
    	}
    }
}






?>