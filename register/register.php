<?php require("require/header.php"); ?>
<?php if (logged_in()){
	redirect("index.php");
}
?>

<div class="reglog">

	<center><img class="img" src="ng.png"></center>
	<center><a class="rlt">Creare cont</a></center>
	<br><?php validate_user_registration(); ?>
	<form method="post" role="form" >
		<input type="text" name="username" class="irl" placeholder="Username" required >
		<input type="email" name="email" class="irl" placeholder="Email Address" required >
		<input type="password" name="password" class="irl" placeholder="Password" required>
		<input type="password" name="confirm_password" class="irl" placeholder="Confirm Password" required>
		<center><a class="tc"><input type="checkbox" required>Accept termeni si conditiile!</a></center>	
		<input type="submit" name="register-submit" class="rls" value="Continua">
	</form>

	<center><a class="hr">Sau</a></center><hr>

	<a class="btn" href="login.php">Intra in cont</a>
</div>
