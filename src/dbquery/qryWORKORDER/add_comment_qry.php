<?php
/************************************************************************************************
Adds a comment to a workorder 
Author: Raymond Brady
Date Created: 8/22/2016
************************************************************************************************/
require_once('./resources/library/workorder.php');

$element = "Workorder";
$element_function = "updated";

if (!isset($_SESSION['user_email']) || !isset($_POST['id']) || !isset($_POST['collabcomment'])) {
    $QUERY_PROCESS = "ERROR|Required fields are missing.";
    return;
}
// Gather data, process POST, and update the workorder with collaborator
$currentUserEmail = filter_var($_SESSION['user_email'], FILTER_SANITIZE_EMAIL);
$id = filter_var(trim($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
$key = filter_var(trim($_POST['key']), FILTER_SANITIZE_STRING);
$comment = filter_var(trim($_POST['collabcomment']), FILTER_SANITIZE_STRING);

$workorderDataAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
$QUERY_PROCESS = $workorderDataAdapter->AddComment($id, $comment);
?>