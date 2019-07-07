<div class="d-none d-lg-block">
<div class="chatbox chatbox--tray" style="z-index:1;">
    <div class="chatbox__title">
        <h5 style="color:#fff">Messagerie</h5>
        <button class="chatbox__title__tray">
            <span></span>
        </button>

    </div>
    <div class="chatbox__body" id="messages">
    <?php 	
                // on récupère les 10 derniers messages postés
                $requete2 = $pdo->query("SELECT * FROM ".$mysql_prefix."messages ORDER BY id DESC LIMIT 0,10");
	
	    // on inscrit tous les nouveaux messages dans une variable
	    while($donnees = $requete2->fetch()){
                    $querychat="SELECT * FROM ".$mysql_prefix."users WHERE id=:id;";
                    $qchat = $pdo->prepare($querychat);
					$qchat->bindParam('id', $donnees['user'], PDO::PARAM_INT);
                    $qchat->execute();
                    
                    $users=$qchat->fetch();
                    
                    $image ="https://www.gravatar.com/avatar/".md5($users['email'])."?s=200&d=".urlencode("https://".$_SERVER['SERVER_NAME']."/images/".$users['type'].".jpg")."";
                    
                    if ($user['id']==$donnees['user']) {
                    	echo"<div class=\"chatbox__body__message chatbox__body__message--left\" id=\"".$donnees['id']."\">
					            <img src=\"".$image."\" alt=\"Picture\">
					            <p style=\"background-color:lightgrey;\"><b>".$users['nick']."</b><br>".$donnees['message']."</p>
					        </div>";	
                    } else {
                    	echo"<div class=\"chatbox__body__message chatbox__body__message--right\" id=\"".$donnees['id']."\">
				            <img src=\"".$image."\" alt=\"Picture\">
				            <p><b>".$users['nick']."</b><br>".$donnees['message']."</p>
				        </div>";	
                    }
	    }
	//$messages = "<p id=\"0\">Test mise à jour 5 sec</p>";
	  
	 ?>	
    </div>
<form method="POST" action="pages/chat/send.php">
	<input type="hidden" name="sendchat" value="go">
    <textarea class="chatbox__message form-control" name="messagechat" id="messagechat" placeholder="Laissez un message."></textarea>
    <button class="btn btn-info btn-sm btn-block" type="submit" id="submitchat">Envoyer</button>
</form>
</div>

</div>