<?php require("require/header.php"); ?>
<?php if(logged_in()){
	redirect("index.php");
}
?>


<div class="reglog">
	<div class="white"><center><img class="img" src="ng.png"></center></div>
	<center><p class="rlt">Resetare parola</p></center>
	<br><?php forgot(); ?>
	<form method="post">

		<input type="email" name="email" placeholder="Email" class="irl" required autocomplete="off">
		<input type="hidden" class="hide" name="token" id="token" value="<?php echo token_generator(); ?>">
		<input type="submit" class="rls" value="Continua">

	</form>
</div>