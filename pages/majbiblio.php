<!--<div class="card p-3 mb-4" id="content"><h3>Livre ajoutés :</h3></div>-->
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
  </head>

  <body>




<? 
include ("../conf/config.inc.php");
include ("../libs/libs.php");
try {
    $pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_dbase.'', $mysql_user, $mysql_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
$user=$_GET['user'];
if (!$user) die('Probleme de connexion');
$pathproc=rand();
if (!$pathproc) die('Probleme de chemin');
$fileproc=urldecode($_GET['fileproc']);
if (!$fileproc) die('Probleme de fichier');


//echo "User : ".$user."</br>";
//echo "File : ".$fileproc."</br>";
//echo "Path : ".$pathproc."</br>";

function selectcoverandcontent($racine,$pathto) {
	$dp = opendir($racine);
	while($entree = readdir($dp)){
		if(is_file($racine.$entree)) {
		// traitement pour le fichier trouvé 
			if (strtolower($entree)=="content.opf"||strtolower($entree)=="cover.jpg")
			rename($racine.$entree,$pathto.$entree);
		}
		else if (!is_file($racine.$entree)) {
			// c'est un dossier! 
			if( $entree != '.' && $entree != '..') selectcoverandcontent($racine.$entree.'/',$pathto);
		}
	}
	closedir($dp);
}

function findcover($racine,$pathto) {
	$dp = opendir($racine);
	$file['name']="";
	$file['size']=0;
	while($entree = readdir($dp)){
		if(is_file($racine.$entree)) {
		// traitement pour le fichier trouvé 
			$extension=strtolower(strrchr($entree,'.'));
			if (in_array ($extension, array ('.gif','.jpg','.jpeg','.png'))){
              if (filesize($racine.$entree)>$file["size"]) {
              	$file['name']=$racine.$entree;
              	$file['size']=filesize($racine.$entree);
              	rename($racine.$entree,$pathto.'cover'.$extension);
              	convertImage($pathto.'cover'.$extension,$pathto);
              }
            }
		}
		else if (!is_file($racine.$entree)) {
			// c'est un dossier! 
			if( $entree != '.' && $entree != '..') findcover($racine.$entree.'/',$pathto);
		}
	}
	closedir($dp);
}

function convertImage($originalImage,$pathto)
{
    // jpg, png, gif or bmp
    $outputImage=$pathto."cover.jpg";
    $quality="97";
    $exploded = explode('.',$originalImage);
    $ext = $exploded[count($exploded) - 1]; 

    if (preg_match('/jpg|jpeg/i',$ext))
        $imageTmp=imagecreatefromjpeg($originalImage);
    else if (preg_match('/png/i',$ext))
        $imageTmp=imagecreatefrompng($originalImage);
    else if (preg_match('/gif/i',$ext))
        $imageTmp=imagecreatefromgif($originalImage);
    else
        return 0;

    // quality is a value from 0 (worst) to 100 (best)
    imagejpeg($imageTmp, $outputImage, $quality);
    imagedestroy($imageTmp);

    return 1;
}

function redimcover($pathto) {
	if (file_exists($pathto."cover.jpg")) {
		$img = $pathto."cover.jpg";
		$pattern = imagecreatetruecolor(250, 400);
		$dimensions = getimagesize($img);
		$image = imagecreatefromjpeg($img);
		imagecopyresampled($pattern, $image, 0, 0, 0, 0, 250, 400, $dimensions[0], $dimensions[1]);
		imagedestroy($image);
		imagejpeg($pattern, $img, 100);	
	}
	// sinon créer image à partir du titre et de l'auteur
	
}

function moovebook($livrepath,$livre,$auteur,$titre,$pathto) {
	if (!file_exists("../Books/".$auteur."/".$titre)) mkdir_r("../Books/".$auteur."/".$titre, 0777);
	rename($livrepath,"../Books/".$auteur."/".$titre."/".$livre);
	if (file_exists($pathto."cover.jpg")) rename($pathto."cover.jpg","../Books/".$auteur."/".$titre."/cover.jpg");
	//rename("../upload/files/infostemp/content.opf","../Books/".$auteur."/".$titre."/content.opf");
}

function mkdir_r($dirName, $rights=0777){
    $dirs = explode('/', $dirName);
    $dir='';
    foreach ($dirs as $part) {
        $dir.=$part.'/';
        if (!is_dir($dir) && strlen($dir)>0)
            mkdir($dir, $rights);
    }
}
?>


<?php
//lister les fichiers epub dans upload/files
$dir = "../upload/files/";
if (!file_exists($dir.$user."/".$pathproc."/tmp")) mkdir_r($dir.$user."/".$pathproc."/tmp", 0777);
if (!file_exists($dir.$user."/".$pathproc."/infostemp")) mkdir_r($dir.$user."/".$pathproc."/infostemp", 0777);

$i=0;
if (is_dir($dir)) {
   if ($dh = opendir($dir)) {
       while (($file = readdir($dh)) !== false) {
           if( $file != '.' && $file != '..' && preg_match('#\.(epub)$#i', $file) && $file==$fileproc) {
           //echo "fichier : $file : type : " . filetype($dir . $file) . "<br />\n";
           //décompresser dans tmp/
			$new_file = str_replace('.epub','.zip',$file);
			rename($dir.$file,$dir.$new_file);
			$zip = new ZipArchive;
			$res = $zip->open($dir.$new_file);
			if ($res === TRUE) {
			  $zip->extractTo($dir.$user.'/'.$pathproc.'/tmp/');
			  $zip->close();
			  rename($dir.$new_file,$dir.$file);
			  //trouver content.opf et cover.jpg et les déplacer à la base infostemp
			  selectcoverandcontent($dir.$user.'/'.$pathproc.'/tmp/',$dir.$user.'/'.$pathproc.'/infostemp/');
			  //extraire les données du content.opf
			  if (file_exists($dir.$user.'/'.$pathproc.'/infostemp/content.opf')) {
				  $package = simplexml_load_file($dir.$user.'/'.$pathproc.'/infostemp/content.opf');
				  $titre = $package->metadata->children('dc', true)->title;
				  $auteur = $package->metadata->children('dc', true)->creator;
				  $descr = $package->metadata->children('dc', true)->description;
				  //$date = $package->metadata->children('dc', true)->date."<br>";
				  $sujet = $package->metadata->children('dc', true)->subject;
				  $identifier = $package->metadata->children('dc', true)->identifier;
				  $lang = $package->metadata->children('dc', true)->language;
				  //vérifier si livre existe, si il n'existe pas :
				  $query = "SELECT id FROM ".$mysql_prefix."ebooks WHERE (auteur=:auteur AND titre=:titre) OR identifier=:identifier;";
				  $q = $pdo->prepare($query);
				  $q->bindParam('auteur', $auteur, PDO::PARAM_STR);
				  $q->bindParam('titre', $titre, PDO::PARAM_STR);
				  $q->bindParam('identifier', $identifier, PDO::PARAM_STR);
				  $q->execute();
				  if ($q->rowCount()==0) {
					  //vérifier si la couverture existe et la chercher si besoin (rechercher la plus grosse image), sinon la créer avec GD
					  if (!file_exists($dir.$user.'/'.$pathproc.'/infostemp/cover.jpg')) {
					    findcover($dir.$user.'/'.$pathproc.'/tmp/',$dir.$user.'/'.$pathproc.'/infostemp/'); 
					  }
					  //redimensionner la couverture 
					  redimcover($dir.$user.'/'.$pathproc.'/infostemp/');
					  //transférer dans le rep books/auteur/livre/ 
					  moovebook($dir.$file,$file,$auteur,$titre,$dir.$user.'/'.$pathproc.'/infostemp/');
					  //ajouter dans la bdd
						$date=date('Y-m-d H:i:s');
						$pathfile=$auteur."/".$titre."/";
				    	$query2 = "INSERT INTO ".$mysql_prefix."ebooks (user,titre,auteur,descr,date,sujet,identifier,lang,filename,pathfile) 
					    VALUES (:user,:titre,:auteur,:descr,:date,:sujet,:identifier,:lang,:filename,:path);";
					    $q2 = $pdo->prepare($query2);
					    $q2->bindParam('user', $user, PDO::PARAM_INT);
					    $q2->bindParam('titre', $titre, PDO::PARAM_STR);
					    $q2->bindParam('auteur', $auteur, PDO::PARAM_STR);
					    $q2->bindParam('descr', $descr, PDO::PARAM_STR);
					    $q2->bindParam('date', $date, PDO::PARAM_STR);
					    $q2->bindParam('sujet', $sujet, PDO::PARAM_STR);
					    $q2->bindParam('identifier', $identifier, PDO::PARAM_STR);
					    $q2->bindParam('lang', $lang, PDO::PARAM_STR);
					    $q2->bindParam('filename', $file, PDO::PARAM_STR);
					    $q2->bindParam('path', $pathfile, PDO::PARAM_STR);
					    $q2->execute();
					  //afficher le livre ajouté
					  //echo $file;
					  showbook($i,$titre,$auteur,$descr,$sujet,$file,$pathfile);
					  $i++;
				  } else {
					  // si le livre existe déjà le préciser
					echo "<h4 class=\"text-danger\">Le livre ".$file." existe déjà ! </h4>";
					//effacer l'epub original
					unlink($dir.$file);	
				  }
			  } else {
				  // si le livre existe déjà le préciser
				echo "<h4 class=\"text-danger\">Impossible de lire les données de ".$file." ! </h4>";
				//effacer l'epub original
				unlink($dir.$file);
			  }

			} else { 
				echo "Unzip fail ".$dir.$file;
			}
			//effacer le dossier temporaire du livre
			clear_dir($dir.$user.'/'.$pathproc.'/');
           }
       }
       // on ferme la connection
       closedir($dh);
   }
}

?>
  </body>
</html>
