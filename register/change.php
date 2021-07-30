<?php require("require/header.php"); ?>

<?php if (!logged_in()){
	redirect("index.php");
}
?>


<div class="reglog">
	<center><img class="img" src="ng.png"></center>
	<center><a class="rlt">Resetare parola</a></center>
	<br><?php change_pass(); ?>
	<form method="post">

		<input type="password" name="newpassword" placeholder="New Password" class="irl" required>
		<input type="password" name="confirmnewpass" placeholder="Confirm new Password" class="irl" required>

		<input type="hidden"  name="email" value="<?php echo $_SESSION['email']; ?>">
		<input type="submit" name="change" class="rls" value="Continue">


	</form>
</div>