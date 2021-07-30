<?php

/** Helful functions **/

function clean($string){
	return htmlentities($string);
}


function redirect($location){
	return header("Location: {$location}");
}


function set_message($message){
	if(!empty($message)){
		$SESSION['message'] = $message;
	}else{
		$message = "";
	}
}


function display_message(){
	if(isset($SESSION['message'])){
		echo $SESSION['message'];
		unset($SESSION['message']);
	}
}


function token_generator(){
	$token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
	return $token;
}


function email_exist($email){
	$sql = "SELECT id FROM users WHERE email = '$email'";
	$result = query($sql);
	if(row_count($result) == 1 ){
		return true;
	}else{
		return false;
	}
}


function username_exist($username){
	$sql = "SELECT id FROM users WHERE username = '$username'";
	$result = query($sql);
	if(row_count($result) == 1 ){
		return true;
	}else{
		return false;
	}
}


/** Validation user register functions **/
function validate_user_registration(){
	$errors = [];
	$min = 1;
	$max = 30;

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		$username 			= clean($_POST['username']);
		$email 				= clean($_POST['email']);
		$password 			= clean($_POST['password']);
		$confirm_password 	= clean($_POST['confirm_password']);

		if(empty($username)){
			$errors[] = "Trebuie sa introduci un nume utilizator.";
		}

		if(username_exist($username)){
			$errors[] = "Acest nume utilizator exista deja.";
		}

		if(strlen($username) < $min) {
			$errors[] = "Nume utilizator prea scurt (minim {$min} caractere).";
		}

		if(strlen($username) > $max) {
			$errors[] = "Nume utilizator prea lung (maxim {$max} caractere).";
		}

		if(email_exist($email)){
			$errors[] = "Acest email exista deja.";
		}

		if(empty($password)){
			$errors[] = "Trebuie sa introduci o parola.";
		}

		if(strlen($password) < $min) {
			$errors[] = "Parola prea scurta (minim {$min} caractere).";
		}

		if(strlen($password) > $max) {
			$errors[] = "Parola prea lunga (maxim {$max} caractere).";
		}
		
		if($password !== $confirm_password){
			$errors[] = "Parola nu este la fel.";
		}

		if(!empty($errors)) {
			foreach($errors as $error){
				echo validation_errors($error);
			}
		}else{
			
			if(register_user($username, $email, $password)){
				redirect("redirect.php?msg=succes_reg");			
			}else{
				redirect("redirect.php?msg=error_reg");
			}
		}
	}
}//Register User Request Function


function register_user($username, $email, $password){
	$username = escape($username);
	$email    = escape($email);
	$password = escape($password);
	
	if(email_exist($email)){
		return false;
	}else if(username_exist($username)){
		return false;
	}else{
		
		$password = md5($password);
		$validation_code = md5($username . microtime());
		$sql = "INSERT INTO users(username, email, password, validation_code, active)
				VALUES('$username', '$email', '$password', '$validation_code', 0)";
		$result = query($sql);
		confirm($result);

// Trimitem emailul

		$subject = "Activetate Account";
		$msg = "Please click the link below to active you Account 
		http://youwebsite.com/activate.php?email=$email&code=$validation_code";
		$headers = "From: noreply@siteulmeu.com";
		send_email($email, $subject, $msg, $headers);

		return true;
	}
}


// Trimitere mail
function send_email($email, $subject, $msg, $headers){
	return mail($email, $subject, $msg, $headers);
}


// Activate user function 
function activate_user(){
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		
		if(isset($_GET['email'])){
			$email = clean($_GET['email']);
			$validation_code = clean($_GET['code']);
			$sql = "SELECT id FROM users WHERE email = '".escape($_GET['email'])."' 
			AND validation_code = '".escape($_GET['code'])."' ";
			$result = query($sql);
			confirm($result);

			if(row_count($result) == 1){
				$sql2 = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '".escape($email)."' AND validation_code = '".escape($validation_code)."' ";
				$result2 = query($sql2);
				confirm($result2);
				redirect("redirect.php?msg=succes_act");
			}else{
				redirect("redirect.php?msg=error_act");
			}
		}	
	}
}


//Validation error message
function validation_errors($error_message){
	$error_message = <<<DELIMITER
	<p class="val_errors">$error_message</p>
DELIMITER;
return $error_message;
}


//Validation login sys
function validate_user_login(){

	$errors =[];
	$min = 3;
	$max = 30;

	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$email 		= clean($_POST['email']);
		$password 	= clean($_POST['password']);
		$remember	= isset($_POST['remember']);

		if(empty($email)){
			$errors[] = "Trebuie sa introduci un email.";
		}

		if(empty($password)){
			$errors[] = "Trebuie sa introduci o parola.";
		}

		if(strlen($password) < $min) {
			$errors[] = "Parola prea scurta (minim {$min} caractere).";
		}

		if(strlen($password) > $max) {
			$errors[] = "Parola prea lunga (maxim {$max} caractere).";
		}

		if(!empty($errors)) {
			foreach($errors as $error){
				echo validation_errors($error);
			}
		}else{
			if(login_user($email, $password, $remember)){
				redirect("admin.php");
			}else{
				echo validation_errors("Datele nu se potrivesc.");
			}
		}
	}
}


//User login
function login_user($email, $password, $remember){
	$sql = "SELECT password, id FROM users WHERE email = '".escape($email)."' AND active = 1 ";
	$result = query($sql);

	if(row_count($result) == 1){
		$row = fetch_array($result);
		$db_password = $row['password'];

		if(md5($password) === $db_password){

			if($remember == "on"){
				setcookie('email', $email, time() +60);
			}

			$_SESSION['email'] = $email;
			return true;
		}else {
			return false;
		}
		return true;
	}else{
		return false;
	}
}// end of function


// Login function
function logged_in(){
	if(isset($_SESSION['email']) || isset($_COOKIE['email'])){
		return true;
		echo $email;
	}else{
		return false;
	}
}


//Forgot password
function forgot(){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		if(isset($_SESSION['token']) && $_POST['token'] = $_SESSION['token']){
		$email = escape($_POST['email']);
			if(email_exist($email)){
				$validation_code = md5($email . microtime());	
				$sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."' ";
				$result = query($sql);
				confirm($result);
				redirect("redirect.php?msg=forgot_mail");
				// Send registration confirmation link (reset.php)
				$email = escape($email);
				$subject = 'Reseatare parola';
				$msg = '
				Buna ziua,
				A-ti solicitat un link de resetare a parolei!
				Va rugam sa da-ti click pe link-ul de mai jos pentru a va reseta parola sau ignoral daca nu vrei asta:
				http://yourwebsite.com/reset.php?email='.$email.'&validation_code='.$validation_code;  
				$headers = "From: noreply@siteulmeu.com";
				send_email($email, $subject, $msg, $headers);		
		}else{
				redirect("redirect.php?msg=forgot_mail_error");
			}
		}
	}
}


//Function verification input and validation code
function code_verification(){
	if(isset($_GET['email']) && isset($_GET['validation_code']) || empty($_GET['email']) && empty($_GET['validation_code'])){
		$email = escape($_GET['email']);
		$validation_code = escape($_GET['validation_code']);
		$sql = "SELECT * FROM users WHERE email = '".escape($email)."' AND validation_code = '".escape($validation_code)."' ";
		$result = query($sql);
		confirm($result);
		
		if(row_count($result) == 0){		
			redirect("redirect.php?msg=error_link_invalid");
		}
	}else{
		redirect("redirect.php?msg=error_verification");
	}
}


//Function reset password
function reset_password(){
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
	
	if ( $_POST['newpassword'] == $_POST['confirmpassword'] ) { 
		$new_password = md5($_POST['newpassword']);        
		$email = escape($_GET['email']);
		$validation_code = escape($_GET['validation_code']);
	    
		if($sql = "UPDATE users SET password='$new_password', validation_code= 0 WHERE email='$email'"){
			$result = query($sql);
			confirm($result);		
			redirect("redirect.php?msg=succes_reset_pass");	
			}
		}else{
			echo validation_errors("Cele 2 parole nu sunt la fel.");
		}
	}
}


//Change pass when user are login
function change_pass(){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if($_POST['newpassword'] == $_POST['confirmnewpass']){

			$email = escape($_POST['email']);
			$newpassword = md5($_POST['newpassword']);
			$confirmnewpass = md5($_POST['confirmnewpass']);
			
			if($sql = "UPDATE users SET password = '$newpassword' WHERE email = '$email'"){
				$result = query($sql);
				confirm($result);
				redirect("redirect.php?msg=succes_chenge_pass");
				}
			}else{
				echo validation_errors("Cele 2 parole nu sunt la fel.");
		}
	}
}


// Function redirect message
function redirect_msg(){
	if(isset($_GET['msg'])) {
		
		if($_GET['msg'] == 'succes_reg'){
		echo '<p>Te-ai inregistrat cu succes. Verificati mailul dumneavoastra pentru a activa contul</p>
		<p>Multumim!</p>';
		}

		if($_GET['msg'] == 'error_reg'){
			echo("Nu te poti inregistra! Te rugam sa incerci din nou.");
		}
		
		if($_GET['msg'] == 'succes_act'){
			echo("Contul tau a fost activat cu succes. Acum poti intra in cont.");
		}

		if($_GET['msg'] == 'error_act'){
			echo("Link invalid sau contul a fost activat deja.");
		}

		if($_GET['msg'] == 'forgot_mail'){
			echo "Va rugam sa va verificati mail-ul pentru a confirma link-ul de schimbare a parolei dumneavoastra!";
		}

		if($_GET['msg'] == 'forgot_mail_error'){
			echo "Acest email nu este inregistrat.";
		}

		if($_GET['msg'] == 'error_link_invalid'){
			echo "Link invalid.";
		}

		if($_GET['msg'] == 'error_verification'){
			echo "Verificare esuata.";
		}

		if($_GET['msg'] == 'succes_reset_pass'){
			echo "Parola a fost resetata cu succes.";
		}

		if($_GET['msg'] == 'succes_chenge_pass'){
			echo "Parola a fost resetata cu succes.";
		}

		if(	$_GET['msg'] !== 'succes_reg' 			&&
			$_GET['msg'] !== 'error_reg' 			&& 
			$_GET['msg'] !== 'succes_act' 			&& 
			$_GET['msg'] !== 'error_act' 			&&
			$_GET['msg'] !== 'forgot_mail' 			&&
			$_GET['msg'] !== 'forgot_mail_error' 	&&
			$_GET['msg'] !== 'error_link_invalid' 	&&
			$_GET['msg'] !== 'error_verification' 	&&
			$_GET['msg'] !== 'succes_reset_pass'	&&
			$_GET['msg'] !== 'succes_chenge_pass'){
			redirect("index_reg.php");
		}
	}
}

?>