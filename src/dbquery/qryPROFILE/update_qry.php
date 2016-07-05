<?php
$element = "Profile";
$element_function = "Updated";
//Define Variables for the form
$USR_pass = $conn->real_escape_string($_POST["set_pass"]);
$USR_passC = $conn->real_escape_string($_POST["set_pass_conf"]);

$USR_fname =$conn->real_escape_string($_POST['user_first_name']);
$USR_lname = $conn->real_escape_string($_POST['user_last_name']);
$USR_username = $conn->real_escape_string($_POST['user_email']);
if($USR_pass == $USR_passC){
	if($USR_pass != ''){
		$USR_pass = sha1($USR_pass.$loginSeed);
		$USR_pass = ", USR_pass = '".$USR_pass."'";
	}
	
	//form query
	$qry = "UPDATE users SET 
	
	USR_fname = '".$USR_fname."',
	USR_lname = '".$USR_lname."',
	USR_username = '".$USR_username."'
	".$USR_pass."
	WHERE USR_id = ".$USR_id_loggedIn; //$TA_id is defined at the top of dashboard_main.php
	$message = "No query ran!!!";
	///echo $qry;
	
	
	$QUERY_PROCESS = mysqltng_query($qry);
	//call query process to make sure there are not errors in the query
	require_once("dbquery/QUERY_PROCESS.php");

//fix profile info when updated
$loginCheck = "SELECT * FROM users where USR_username='".$USR_username."'";
$res=mysqltng_query($loginCheck);
//if you change this info make sure to change it under the main_head.php
$fname = mysqltng_result( $res,0,"USR_fname" );
$lname = mysqltng_result( $res,0,"USR_lname" );
$USR_id = mysqltng_result( $res,0,"USR_id" );
}else{
	?>
        <div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <strong>ERROR!!: </strong> Your passwords do not match.
      
   
    </div>
    <?php
}

?>