<?php
/************************************************************************************************
Updates workorder data with posted values. Requires valid approver key before update. 
Author: Raymond Brady
Date Created: 8/17/2016
************************************************************************************************/
    $element = "Workorder";
    $element_function = "updated";
    
    $id = filter_var(trim($_POST['id']), FILTER_SANITIZE_STRING);
    $key = filter_var(trim($_POST['key']), FILTER_SANITIZE_STRING);
    $comment = filter_var(trim($_POST['comment']), FILTER_SANITIZE_STRING);
    $approve = filter_var(trim($_POST['approve']), FILTER_SANITIZE_STRING);
    if ($id == null || $key == null || @comment == null || @approve == null)
    {
        die("Invalid request. Make sure required data is provided with the request.");
    }
    
    require_once('./resources/library/workorder.php');
    require_once('./resources/library/approver.php');
    require_once('./resources/library/workflow.php');
    require_once('./config/db.php');
    
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word);
    $wo = $woDbAdapter->Select($id);
    /**
    *   Updates occur without a user logged in as long as the update key is valid.
    *   We need to use the current approvers email address.
    *   NOTE: WorkorderDataAdapter will throw when updating if currentUserEmail is not set.
    */
    $userEmailAddress = $wo->currentApprover;
    $woDbAdapter->currentUserEmail = $userEmailAddress;
    // WorkorderViewModel is used to validate the key.
    $woViewModel = new WorkorderViewModel($wo, $key);
    if ($woViewModel->valid != true || $woDbAdapter->currentUserEmail == null)
    {
        die("Invalid request. Required data not provided or you are authorized to make changes.");
    }
    // Update the comments
    $comments = json_decode($wo->comments, true);
    if ($comments == null) {
        $comments = array();
    }
    $woComment = new WorkorderComment();
    $woComment->commentData = $comment;
    $woComment->createdAt = date('Y-m-d H:i:s');
    $woComment->createdBy = $userEmailAddress;
    array_push($comments, $woComment);
    $wo->comments = json_encode($comments);
    // Must decode the workflow in order to manipulate approvers. Must encode back before saving to DB
    $workflow = json_decode($wo->workflow, true);
    $approvers = ApproverHelper::toApproverArray($workflow);
    // some default flags
    $fromEmailAddress = 'noreply@dumasisd.org';
    $isFinalApprover = false;
    $sendFinalApproveNotifications = false;
    $newApproverKey = generateApproverKey(); // Change the approver key as needed.
    // Check if approver is final
    if ($wo->currentApprover == ApproverHelper::getFinal($approvers)->email) {
        $isFinalApprover = true;
    }
    // Update approve status, keys, and current approver.
    if ($approve == "true") {
        if ($isFinalApprover == true){
            $wo->approveState = ApproveState::ApproveClosed; // Final. Work is now completed
            $sendFinalApproveNotifications = true;
            $wo->approverKey = ""; // No more access as approver.
            $newApproverKey = ""; // viewmodel will need this in order to work. 
        } else {
            $wo->approveState = ApproveState::PendingApproval; // waiting on more approvers
            $wo->approverKey = $newApproverKey;
            $nextApprover = ApproverHelper::getNext($approvers);
            ApproverHelper::setCurrent($approvers, $nextApprover);
            $wo->currentApprover = ApproverHelper::getCurrent($approvers)->email;
        }
    } else {
        // rejected by the current approver.
        if ($isFinalApprover == true){
            $wo->approveState = ApproveState::RejectClosed; // Final approver. Closed as rejected
            $sendFinalApproveNotifications = false; // do not notify final approval contacts            
            $wo->approverKey = ""; // No more access as approver. 
            $newApproverKey = ""; // viewmodel will need this in order to work. 
        } else {
            $wo->approveState = ApproveState::RejectClosed; // Closed as rejected
            $sendFinalApproveNotifications = false; // do not notify final approval contacts
            $wo->approverKey = ""; // No more access as approver. 
            $newApproverKey = ""; // viewmodel will need this in order to work. 
        }
    }
    // Must encode workflow back before saving to DB
    $updatedWorkflow = new Workflow($workflow['name'], $approvers);
    $wo->workflow = json_encode($updatedWorkflow);
    $woDbAdapter->Update($id, $wo);
    $woViewModel = new WorkorderViewModel($wo, $newApproverKey); // update the viewmodel since the comments have been updated.
    
    // send notifications
    $emailAdapter = new WorkorderEmailAdapter($fromEmailAddress);
    if ($approve == "true") {
        if ($isFinalApprover != true) {
            $emailAdapter->SendNeedsApprovalToCurrentApprover($wo, $woViewModel);
            $emailAdapter->SendUpdatedDetailsToCreator($wo, $woViewModel);
        }
        if ($sendFinalApproveNotifications == true) {
            $emailAdapter->SendViewOnlyFinalApprovalNotifications($wo, $woViewModel);        
        }
    } else {
        $emailAdapter->SendViewOnlyRejectedToCreator($wo, $woViewModel);
    }
    
?>
