<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
  </head>
<? 
// https://programmation-web.net/2012/04/comment-forcer-le-telechargement-dun-fichier-en-php/
set_time_limit(0);
session_start();
$user=$_SESSION['user'];
include ("conf/config.inc.php");
try {
    $pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_dbase.'', $mysql_user, $mysql_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
$iddl=$_GET['idbook'];
$cledl=$_GET['clebook'];

$query="SELECT * FROM ".$mysql_prefix."ebooks WHERE id=:id;";
$q = $pdo->prepare($query);
$q->bindParam('id', $iddl, PDO::PARAM_INT);
$q->execute();
$book=$q->fetch();
$fichier="Books/".$book['pathfile'].$book['filename'];
	
if (md5($book['filename'])==$cledl&&$user['type']!="") { // on vérifier que la clef est ok et que la session user est active
	if (!file_exists($fichier)||!is_readable($fichier)) { // on vérifie l'existance du fichier et sa lecture
	    header("HTTP/1.1 404 Not Found");
    	exit;
	} else { // on envoie le fichier
		if (ini_get("zlib.output_compression")) { // désactivation de la compression du code
		    ini_set("zlib.output_compression", "Off");
		}
		// ajout d'un dl en bdd
		$querydl="SELECT dl FROM ".$mysql_prefix."ebooks_infos WHERE book=:id;";
		$qdl = $pdo->prepare($querydl);
		$qdl->bindParam('id', $iddl, PDO::PARAM_INT);
		$qdl->execute();
		$nbdl=$qdl->fetch();
		if ($nbdl['dl']<1) $nb=0; else $nb=$nbdl['dl'];
		$nb++;
		$queryadl = "UPDATE ".$mysql_prefix."ebooks_infos SET dl=:nb WHERE book=:id;";
		$qadl = $pdo->prepare($queryadl);
		$qadl->bindParam('id', $iddl, PDO::PARAM_INT);
		$qadl->bindParam('nb', $nb, PDO::PARAM_INT);
		$qadl->execute();
		
		
		$size = filesize($fichier);
		session_write_close(); // fermeture de la session pour laisser la navigation active
		// effacement de la mise en cache
ob_start();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$book['filename']."\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($fichier));
while (ob_get_level()) {
		ob_end_clean();
	  }
	  readfile($fichier);
		exit;
	}
	
} else {
	echo "No way !";
	// erreur, tentative de hack
    header("HTTP/1.1 403 Forbidden");
    exit;
}
?>
</html>




