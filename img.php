<? 
header('Content-type: image/jpeg');
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
$idimg=$_GET['idimg'];

$query="SELECT pathfile FROM ".$mysql_prefix."ebooks WHERE id=:id;";
$q = $pdo->prepare($query);
$q->bindParam('id', $idimg, PDO::PARAM_INT);
$q->execute();
$book=$q->fetch();
$fichier="Books/".$book['pathfile']."cover.jpg";
if (!file_exists($fichier)) $fichier="images/nocover.jpg";


if ($user['type']!="") { // on vérifier que la session user est active

	$img = LoadJpeg($fichier);
	imagejpeg($img);
	imagedestroy($img);
	
}
function LoadJpeg($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefromjpeg($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a black image */
        $im  = imagecreatetruecolor(250, 400);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 250, 400, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading !', $tc);
    }

    return $im;
}
?>