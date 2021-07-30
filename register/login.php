<?php require("require/header.php"); ?>
<?php 
if (logged_in()){
	redirect("index.php");
}
?>
							
<div class="reglog">
	<center><img class="img" src="ng.png"></center>
	<center><a class="rlt">Conecteaza-ti contul</a></center>
<br><?php validate_user_login(); ?>
	<form method="post" role="form">
		<input type="text" name="email" class="irl" placeholder="Email" required>
		<input type="password" name="password" class="irl" placeholder="Parola" required>
		<center><a class="tc"><input type="checkbox">Tine-ma minte!</a></center>
		<input type="submit" name="login-submit" class="rls" value="Conectare">
	</form>
	
	<center><a class="fp" href="forgot.php">Mi-am uitat parola.</a></center><br>

	<center><a class="hr">Sau</a></center><hr>

	<a class="btn" href="register.php">Creeaza un cont</a>

	
</div>