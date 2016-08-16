<?php
/************************************************************************************************
Adds a user as a collaborator to a workorder 
Author: Raymond Brady
Date Created: 8/16/2016
************************************************************************************************/
require_once('./resources/library/workorder.php');
require_once('./resources/library/pacman.php');

$element = "Workorder";
$element_function = "updated";

// Gather data, process POST, and update the workorder with collaborator
$currentUserEmail = $_SESSION['user_email'];
//$formPostHandler = new Pacman($_POST); // form post handler now supports compiling updated workorder data.
//$workorderDataAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);

$QUERY_PROCESS =  ""; //$workorderDataAdapter->UpdateFormData($formPostHandler->woId, $formPostHandler->asJSON());

?>