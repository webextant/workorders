<?php
/*
QUERY PROCESS -- this does a check on all queries to make sure they do not fail.  If they fail it shows an alert and sends an email
*/
$mysql_error = $conn->errno . ": " . $conn->error;

$mysql_error_exp = explode('for key',$mysql_error);
$mysql_error = $mysql_error_exp[0];


// Query process display data located in dashboard.php 
?>