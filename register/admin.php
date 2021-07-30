<?php require("require/header.php"); ?>
<?php
if(logged_in()){
	echo "You are login ";
	echo $_SESSION['email'];
}else{
	redirect("index_reg.php");
}
?>