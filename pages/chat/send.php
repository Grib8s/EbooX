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

	if($_POST['message']!=""){ // si on a envoyé des données avec le formulaire

    if(!empty($user['id']) AND !empty($_POST['message'])){ // si les variables ne sont pas vides
    
        
        $message = $_POST['message']; // on sécurise nos données
		$date = date('Y-m-d H:i:s');
        // puis on entre les données en base de données :
        $insertion = $pdo->prepare('INSERT INTO '.$mysql_prefix.'messages VALUES("", :pseudo, :message, :date)');
        $insertion->execute(array(
            'pseudo' => $user['id'],
            'message' => $message,
            'date' => $date
        ));

    }
    else{
        echo "Vous avez oublié de remplir un des champs !";
    }

	}
}
?>