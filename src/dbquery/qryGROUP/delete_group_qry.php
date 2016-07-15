<?php
$element = "Group";
$element_function = "DELETED";
//Define Variables for the form
require_once "./resources/library/groups.php";
$groupDbAdapter = new groupDataAdapter($dsn, $user_name, $pass_word);

$GRP_id_active = pg_encrypt($_POST['group_id'].$general_seed,$pg_encrypt_key,"decode"); //$_POST['group_id'];
$transfer_GRP_id = pg_encrypt($_POST['transferTo'].$general_seed,$pg_encrypt_key,"decode"); // $_POST['transferTo'];
//echo "--------------------------------------------------------------------------------------------   "  . $transfer_GRP_id.  "   ------ ".$GRP_id_active;
if($GRP_id_active <> '' && $transfer_GRP_id <> ''){
	$QUERY_PROCESS = $groupDbAdapter->Delete($GRP_id_active,$transfer_GRP_id);
}

?>
