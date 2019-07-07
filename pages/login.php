    <div class="container">
    	
      <form class="form-signin" method="POST">
      	<div class="card p-3">

        <h1 class="form-signin-heading text-center"><img src="images/books-icon.png" width="50"> EbooX</h1>
        <p class="text-center">Collection privée de livres électroniques.</p>

    
    
    
   <?php 
 if (isset($_SESSION['timerlogin'])){
    $timer = $_SESSION['timerlogin']*5;
    $timetimerok = time()+$timer;
    ?>
    <div id="countdown" class="text-center"></div>
    <script type="text/javascript">
        var iTime = <?php echo $timer; ?>;
        function countdown()
        {                
            var i = setInterval(function(){
                document.getElementById("countdown").innerHTML = '<em class="text-center">Prochaine tentative de login dans ' + iTime + ' secondes.</em>';
                if(iTime==0){
                    document.getElementById("countdown").innerHTML = '<input type="hidden" name="timeok" value="<?php echo $timetimerok; ?>"><label for="inputEmail" class="sr-only">Login</label><input name="login" type="text" id="inputEmail" class="form-control" placeholder="Login" required autofocus><label for="inputPassword" class="sr-only">Mot de passe</label><input name="pass" type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required><button class="btn btn-lg btn-info btn-block" type="submit">Se connecter</button>';
                    clearInterval(i);
                } else {
                    iTime--;
                }                   
            },1000);                        
        }           
        countdown();
    </script>
    <?php
} else {
    ?>


        <label for="inputEmail" class="sr-only">Login</label>
        <input name="login" type="text" id="inputEmail" class="form-control" placeholder="Login" required autofocus>
        <label for="inputPassword" class="sr-only">Mot de passe</label>
        <input name="pass" type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required>
		<input type="hidden" name="timeok" value="<?php echo time(); ?>">
        <button class="btn btn-lg btn-info btn-block" type="submit">Se connecter</button>
    
    <?php
}

?>
  	</div>
      </form>     
    </div> <!-- /container -->