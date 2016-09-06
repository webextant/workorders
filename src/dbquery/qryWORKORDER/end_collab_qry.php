<?php
/************************************************************************************************
Ends ALL user collaboration for a workorder 
Author: Raymond Brady
Date Created: 9/6/2016
************************************************************************************************/
require_once('./resources/library/workorder.php');

$element = "Workorder";
$element_function = "updated";

if (!isset($_SESSION['user_email']) || !isset($_POST['id']) || !isset($_POST['key'])) {
    $QUERY_PROCESS = "ERROR|Required fields are missing.";
    return;
}
// Gather data, process POST, and update the workorder
$currentUserEmail = filter_var($_SESSION['user_email'], FILTER_SANITIZE_EMAIL);
$id = filter_var(trim($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
$key = filter_var(trim($_POST['key']), FILTER_SANITIZE_STRING);
$comment = "Thanks for the assist! I ended collaboration.";
if (isset($_POST['endcollabcomment']) && $_POST['endcollabcomment'] != null ){
    $comment = filter_var(trim($_POST['endcollabcomment']), FILTER_SANITIZE_STRING);
}

$workorderDataAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
// read the record before making the changes. Used for sending collab emails
$wo = $workorderDataAdapter->Select($id);
$woViewModel = new WorkorderViewModel($wo, $key, $currentUserEmail);

$QUERY_PROCESS = $workorderDataAdapter->EndCollaboration($id, $comment);

// send emails.
$fromEmailAddress = 'noreply@dumasisd.org';
$emailAdapter = new WorkorderEmailAdapter($fromEmailAddress);
$emailAdapter->SendEndCollab($wo, $woViewModel);
?>