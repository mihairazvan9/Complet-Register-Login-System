<?php require("functions/init.php"); ?>
<!DOCTYPE html>
<html lang="en">
<html>
<head>
	<meta charset="UTF-8">
	<title>Register sys</title>
	<link rel="stylesheet" href="css/style.css">
	<script src="#"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
	.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 16px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  transition-duration: 0.4s;
  cursor: pointer;

	margin: 0px;
}
.button3 {
  background-color: white; 
  color: black; 
  border: 2px solid #f44336;
}

.button3:hover {
  background-color: #f44336;
  color: white;
}

</style>
<body>
<!--
<div class="navbar">
	<a class="dda" href="register.php">
		<img class="ddi" src="img/4.png">
		<label class="ddl">Cont</label>
	</a>
		
</div>
-->
<div class="navbar">
	<!--
	<button class="dropbtn"><a href="index2.php">Home</a></button>
	<button class="dropbtn"><a href="admin.php">Admin</a></button>
	-->
	<a class="button button3" href="index_reg.php">Home</a>
	<a class="button button3" href="admin.php">Admin</a>
	<div class="dropdown">
<?php if(logged_in()){ ?>
		<button class="dropbtn"><a href="#">Contul meu</a></button>
		<div class="dropdown_content">

			<a href="change.php">Schimba parola</a>
			<a href="logout.php">Deconectare</a>
<?php }else{ ?>

		<a class="button button3" href="login.php">Contul meu</a>


		<!--
		<button class="dropbtn"><a href="login.php">Contul meu</a></button>
	-->
<?php } ?>
		</div>
	</div>
</div>