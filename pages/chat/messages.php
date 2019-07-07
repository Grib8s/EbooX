<? 

// https://programmation-web.net/2012/04/comment-forcer-le-telechargement-dun-fichier-en-php/
set_time_limit(0);
session_start();
$user=$_SESSION['user'];
include ("../../conf/config.inc.php");
try {
    $pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_dbase.'', $mysql_user, $mysql_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


if ($user['type']!="") { // on vérifier que la session user est active


	if(!empty($_GET['id'])){ // on vérifie que l'id est bien présent et pas vide
	
	    $id = (int) $_GET['id']; // on s'assure que c'est un nombre entier
	
	    // on récupère les messages ayant un id plus grand que celui donné
	    $requete = $pdo->prepare('SELECT * FROM '.$mysql_prefix.'messages WHERE id > :id ORDER BY id DESC');
	    $requete->execute(array("id" => $id));
	
	    $messages = null;
	
	    // on inscrit tous les nouveaux messages dans une variable
	    while($donnees = $requete->fetch()){
                    $query="SELECT * FROM ".$mysql_prefix."users WHERE id=:id;";
                    $q = $pdo->prepare($query);
					$q->bindParam('id', $donnees['user'], PDO::PARAM_INT);
                    $q->execute();
                    
                    $users=$q->fetch();
                    
                    $image ="https://www.gravatar.com/avatar/".md5($users['email'])."?s=200&d=".urlencode("https://".$_SERVER['SERVER_NAME']."/images/".$users['type'].".jpg")."";
                    
                    if ($user['id']==$donnees['user']) {
                    	$messages.="<div class=\"chatbox__body__message chatbox__body__message--left\" id=\"".$donnees['id']."\">
					            <img src=\"".$image."\" alt=\"Picture\">
					            <p style=\"background-color:lightgrey;\"><b>".$users['nick']."</b><br>".$donnees['message']."</p>
					        </div>";	
                    } else {
                    	$messages.="<div class=\"chatbox__body__message chatbox__body__message--right\" id=\"".$donnees['id']."\">
				            <img src=\"".$image."\" alt=\"Picture\">
				            <p><b>".$users['nick']."</b><br>".$donnees['message']."</p>
				        </div>";	
                    }
	    }
	//$messages = "<p id=\"0\">Test mise à jour 5 sec</p>";
	    echo $messages; // enfin, on retourne les messages à notre script JS

}

            
}

?>