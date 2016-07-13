<?php
/************************************************************************************************
Updates a previously submitted workorder
Author: Raymond Brady
Date Created: 7/12/2016
************************************************************************************************/
require_once('./resources/library/workorder.php');
require_once('./resources/library/pacman.php');

$element = "Workorder";
$element_function = "Updated";

// Gather data, process POST, and update the workorder data
$currentUserEmail = $_SESSION['user_email'];
$formPostHandler = new Pacman($_POST); // form post handler now supports compiling updated workorder data.
$workorderDataAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);

$QUERY_PROCESS = $workorderDataAdapter->UpdateFormData($formPostHandler->woId, $formPostHandler->asJSON());

?>