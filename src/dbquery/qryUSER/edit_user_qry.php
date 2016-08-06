<?php

require_once "./resources/library/user.php";

$usrDbAdapter = new UserDataAdapter($dsn, $user_name, $pass_word);


$element = "User";
$element_function = "Updated";
//Define Variables for the form
		$user_email = $_POST['user_email'];
		$user_fname= $_POST['first_name'];
		$user_lname= $_POST['last_name'];
		$user_groups= $_POST['group_list'];
		$user_password= $_POST['user_password'];
		$user_id =pg_encrypt($_POST['userID'].$general_seed,$pg_encrypt_key,"decode");
		//$form_manager = $_POST['limit_domains'];
		$user_perms =$_POST['user_role'];
	
$QUERY_PROCESS = $usrDbAdapter->AdminUpdate($user_id, $user_email, $user_fname,$user_lname,$user_groups,$user_perms, $user_password);

?>
