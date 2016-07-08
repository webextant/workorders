<?php
    require_once('./resources/appconfig.php');
    require_once("./resources/library/appinfo.php");
    $appInfoDbAdapter = new AppInfo($dsn, $user_name, $pass_word);
    $system_version =$appInfoDbAdapter->Get('System Version');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DISD - Workorders</title>

    <?php require_once('./includes/headlinks.php'); ?>
    <?php require_once('./includes/headlinksfb.php'); ?>

</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
        <?php require_once('./includes/navbar.php') ?>
        <!-- Form creation code -->
        <?php
            require_once('./config/db.php');
            require_once('./resources/library/forms_db_controller.php');
            require_once('./resources/library/workorder.php');
            require_once('./resources/library/workflow.php');
            require_once('./resources/library/approver.php');
            require_once('./resources/library/pacman.php');
            require_once('./resources/library/email.php');
        ?>
        <!-- Render Form from post data -->
        <?php
        $currentUserEmail = $_SESSION['user_email'];
        $currentUserGroup = $_SESSION['user_group'];
        $hideFormClassString = "";
        $fromEmailAddress = 'noreply@dumasisd.org';
        // Handle post
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')
        {
            // When id is sent then render the form
            if(isset($_POST['id']))
            {
                $formId = $_POST['id'];
                $formsDataAdapter = new FormsDataController();
                $formToRender = $formsDataAdapter->getFormById($formId);
                $formName = $formToRender['FormName'];
                $formDescription = $formToRender['Description'];
                $formXml = base64_decode($formToRender['FormXml']);
            } else {
                try {
                    // POST['id'] is not present. Should handle posted form data here
                    $formPostHandler = new Pacman($_POST);
                    // Need to load the form. Form data is stored with new workorder.
                    $formsDataAdapter = new FormsDataController();
                    $form = $formsDataAdapter->getFormById($formPostHandler->formId);
                    // Setup data needed for creating workorder and rendering page
                    $hideFormRenderingClassString = "hidden";
                    $formName = $formPostHandler->formName; //$_POST['form-name'];
                    $formDescription = $formPostHandler->formDescription; //$_POST['form-description'];
                    // Form Workflow field holds array of approver email addresses. We need to transform this to work with the data.
                    $approverArray = explode(',', $form['Workflow']);
                    $approvers = ApproverHelper::NewApproverArrayFromEmailArray($approverArray);
                    // Get the groupWorkflow for the users group
                    $groupWorkflows = $form['GroupWorkflows'];
                    $groupWorkflows = json_decode($groupWorkflows);
                    $userGroupApproverArray = $groupWorkflows->$currentUserGroup;
                    $groupApprovers = ApproverHelper::NewApproverArrayFromEmailArray($userGroupApproverArray);
                    // merge the approver arrays with group first and create the workflow for the form
                    $mergedApprovers = ApproverHelper::MergeApproverArrays($groupApprovers, $approvers);
                    $workflow =  new Workflow($formName . ' Workflow', $mergedApprovers);
                    
                    $wo = new Workorder();
                    $wo->formName = $formPostHandler->formName;
                    $wo->formId = $formPostHandler->formId;
                    $wo->description = $formPostHandler->formDescription;
                    $wo->formXml = $formPostHandler->asFormXML();
                    $wo->formData = $formPostHandler->asJSON();
                    $wo->currentApprover = ApproverHelper::setNextOrFirstCurrent($mergedApprovers, $currentUserEmail)->email;
                    $wo->workflow = $workflow->asJSON();
                    $wo->approveState = ApproveState::PendingApproval;
                    $wo->approverKey = generateApproverKey();
                    $wo->viewOnlyKey = generateApproverKey(); // TODO: rename key gen function. It generates basic key. Not exclusive to approvers.
                    $wo->createdBy = $currentUserEmail;
                    $wo->updatedBy = $currentUserEmail;
                    $wo->notifyOnFinalApproval = $form['notifyOnFinalApproval'];

                    $woDataAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
                    $woDataAdapter->Insert($wo);
                    $wo->id = $woDataAdapter->lastInsertId;

                    // create a vew model for the email adapter to use. Helps to generate more detailed emails.
                    $woViewModel = new WorkorderViewModel($wo, $wo->approverKey);
                    // send emails.
                    $emailAdapter = new WorkorderEmailAdapter($fromEmailAddress);
                    $emailAdapter->SendViewOnlyCreatedToCreator($wo, $woViewModel);
                    $emailAdapter->SendNeedsApprovalToCurrentApprover($wo, $woViewModel);

                    $formSubmissionMessage = "<div class='container'><div class='alert alert-success'><i class='fa fa-info-circle'></i><b> " . $formName . "</b> was saved. Check your email.</div></div>";
                    
                } catch (Exception $e) {
                    $formSubmissionMessage = "<div class='container'><div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> There was a problem saving " . $formName . ".<p>" . $e->getMessage . "</p></div></div>";
                }
            }
            
        } else {
            // TODO: redirect to unauth page. Page only handles POST
            $formSubmissionMessage = "<div class='container'><div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Bad request. </div></div>";
        }
        
        ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           <?php echo $formName; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-file"></i> <?php echo $formDescription; ?>
                            </li>
                        </ol>
                        <?php echo $formSubmissionMessage; ?>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row" <?php echo $hideFormRenderingClassString; ?>>
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <textarea id="form-builder-template" hidden><?php echo $formXml; ?></textarea>
                        <div id="rendered-form">
                            <form id="workorderform" class="form-horizontal" method="post"></form>
                            <button id="formSubmitButton" class="btn btn-success pull-right">Send</button>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <?php require_once('./includes/jsbs.php'); ?>
    <?php require_once('./includes/jsfb.php'); ?>
    <?php require_once('./includes/jsjqvalidation.php'); ?>

    <script>
        var template = document.getElementById('form-builder-template');
        var formContainer = document.getElementById('rendered-form');
        $(template).formRender({
            container: jQuery('form', formContainer)
        });

        jQuery('#formSubmitButton').click(function(){
            jQuery('#workorderform').submit();
        });

        jQuery("#workorderform").validate({
            submitHandler: function(form){
                // Set the xml data string before sending to server.
                 $xml = jQuery('#form-builder-template').val();
                 $('<input />').attr('type', 'hidden')
                    .attr('name', "form-xml-schema")
                    .attr('value', $xml)
                    .appendTo(form);
                 $('<input />').attr('type', 'hidden')
                    .attr('name', "form-name")
                    .attr('value', <?php echo "'" . $formName . "'"; ?>)
                    .appendTo(form);
                 $('<input />').attr('type', 'hidden')
                    .attr('name', "form-id")
                    .attr('value', <?php echo "'" . $formId . "'"; ?>)
                    .appendTo(form);
                 $('<input />').attr('type', 'hidden')
                    .attr('name', "form-description")
                    .attr('value', <?php echo "'" . $formDescription . "'"; ?>)
                    .appendTo(form);
                
                form.submit();
            },
            ignore: [],
        });

    </script>
</body>

</html>
