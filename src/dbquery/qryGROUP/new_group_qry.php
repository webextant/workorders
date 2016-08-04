<?php
$element = "Group";
$element_function = "Created";
//Define Variables for the form
require_once "./resources/library/groups.php";
$groupDbAdapter = new groupDataAdapter($dsn, $user_name, $pass_word);

$GRP_name =$_POST['group_name'];//$conn->real_escape_string($_POST['limit_domains']);

$QUERY_PROCESS = $groupDbAdapter->insert($GRP_name);


?>
