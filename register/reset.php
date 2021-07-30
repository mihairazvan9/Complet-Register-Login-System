<?php require("require/header.php"); ?>
<?php code_verification(); ?>

<div class="reglog">
	<center><img class="img" src="ng.png"></center>
	<center><a class="rlt">Resetare parola</a></center>
	<br><?php reset_password(); ?>
	<form method="post">
		<input type="password"required name="newpassword" class="irl" placeholder="New password" autocomplete="off"/>
		<input type="password"required name="confirmpassword" class="irl" placeholder="Retype new password" autocomplete="off"/>
		<input type="hidden" name="email" value="<?= $email ?>">    
		<input type="hidden" name="validation" value="<?= $validation_code ?>">
		<input type="submit" name="register-submit" class="rls" value="Continua">
	</form>
</div>