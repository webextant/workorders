<?php
$element = "User";
$element_function = "Updated";
//Define Variables for the form
$user_name = $value->user_name;
		$user_email = $_POST['user_email'];
		$user_name= $_POST['username'];
		$user_fname= $_POST['first_name'];
		$user_lname= $_POST['last_name'];
		
		$user_id =pg_encrypt($_POST['userID'].$general_seed,$pg_encrypt_key,"decode");
		//$form_manager = $_POST['limit_domains'];
		$user_perms =$_POST['user_role'];
	


//echo $domain_list;
//we need to add a dataAdaptors->set to update values
//we need to add a field to edit_user.php called password
//if assword is not blank then password needs to be set otherwise it needs to be ignored
										// (| seperaated fields where field=$POSTEDvalue, user_id we are updating )
$QUERY_PROCESS = '';//$UserDataAdapter->Set("user_email=$user_email|user_name=$user_name|user_fname=$user_fname|user_lname=$user_lname|user_perms=$user_perms", $user_id);


?>
