<?php
	$folder = null; // set default value or error in navbar. XDebug
    require_once('./resources/appconfig.php');
    require_once("./resources/library/appinfo.php");
    $appInfoDbAdapter = new AppInfo($dsn, $user_name, $pass_word);
    $system_version =$appInfoDbAdapter->Get('System Version');

    $id = filter_var(trim($_GET['id']), FILTER_SANITIZE_STRING);
    $key = filter_var(trim($_GET['key']), FILTER_SANITIZE_STRING);
    if ($id == null || $key == null)
    {
        die("Invalid request.");
    }
    
    require_once('./resources/library/workorder.php');
    require_once './resources/library/collaborator.php';
    require_once('./config/db.php');
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $_SESSION['user_email']);
    $wo = $woDbAdapter->Select($id);
    $woViewModel = new WorkorderViewModel($wo, $key);
    $collabViewModel = new CollaboratorViewModel($dsn, $user_name, $pass_word, $_SESSION['user_email']);

    $acceptBtnText = "APPROVE (not final)";
    $rejectBtnText = "DENY";
    if ($woViewModel->isFinalApproval){
        $acceptBtnText = "APPROVE (final)";
        $rejectBtnText = "DENY";
        $finalApprovalHiddenClass = "hidden";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DISD - Workorder</title>

    <?php require_once('./includes/headlinks.php'); ?>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php require_once('./includes/navbar.php') ?>
        <!-- Page Content -->
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12"></div>
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <?php echo $wo->formName; ?>
                        </h1>
                        <ol class="breadcrumb">
                           <?php
								if ($login->isUserLoggedIn() == true) {
					   				if($_SESSION['user_perms'] <=2){
							?>
                            <li class="">
                                <i class="fa fa-fw fa-folder"></i><a href="index.php?I=<?php echo pg_encrypt("APPROVAL-needs_approval",$pg_encrypt_key,"encode"); ?>"> NEEDS APPROVAL</a>
                            </li>
                            <?php
									}
								}
							?>
                            <li class="active">
                                <i class="fa fa-fw fa-file"></i> <?php echo $woViewModel->workorderIdText; ?>
                            </li>
                            <li>
                                <?php echo $wo->createdAt; ?>
                            </li>
                        </ol>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <!-- /.row -->

                <!-- Page row -->
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                    <?php if ($woViewModel->valid) { ?>
                        <div class="<?=$woViewModel->stateColorClass?>"><?=$woViewModel->approveState . " (" . $wo->currentApprover . ")"?></div>
                        <div class="alert alert-info">
                            <h4><span class="fa fa-user" aria-hidden="true" /> Requested By</h4>
                            <span><?=$wo->createdBy?></span>
                        </div>
                        <?php 
                            foreach ($woViewModel->fieldData as $fieldkey => $value) {
                                echo "<h4>" . $value["Label"] . "</h4>";
                                echo "<P>" . $value["Data"] . "</p>";
                            }
                        ?>
                        <h4>Approver Comments</h4>
                        <?php
                            if (count($woViewModel->comments) == 0) {
                                echo "<span>No comments posted.</span>";
                            } else {
                                echo "<ul style='list-style-type:none'>";
                                foreach ($woViewModel->comments as $commentkey => $value) {
                                    echo "<li class='message-preview'>";
                                    echo "<div class='media'>";
                                    echo "<div class='media-body'>";
                                    echo "<h5 class='media-heading'><b>" . $value['createdBy'] . "</b></h4>";
                                    echo "<p class='small text-muted'><i class='fa fa-clock-o'></i> " . $value['createdAt'] . "</p>";
                                    echo "<p>" . $value['commentData'] . "</p>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</li>";
                                }                                
                                echo "</ul>";
                            }
                        ?>
                        <?php } else {
                            echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Unable to view the workorder. You may not be authorized...</div>';
                        }
                    ?>
                    
                    <?php if ($woViewModel->approverKeyValid && $woViewModel->valid) { ?>
                        <hr/>
                        <div class="<?=$finalApprovalHiddenClass?>">
                        <h3>Approve or Reject This Item</h3>
                        <span>As the current approver of this item you can either approve or reject it.</span>
                        <li><span>If you approve: The item will be sent to the next approver.</span></li>
                        <li><span>If you are the final approver: The item will be marked as approved.</span></li>
                        <li><span>If you reject: Any previous approvers will be notified and the item will be closed.</span></li>
                        </div>
                        <form id="updateworkorder" method="post">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="key" value="<?php echo $key; ?>">
                                <input type="hidden" id="approve" name="approve" value="true">
                                <label for="comment">Comments</label>
                                <textarea id="comment" name="comment" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="row">
                                <div id="approve-btn-group" class="col-xs-12">
                                    <button id="approve-btn" type="button" class="btn btn-success"><?php echo $acceptBtnText; ?></button>
                                    <button id="reject-btn" type="button" class="btn btn-danger"><?php echo $rejectBtnText; ?></button>
                                    <a  href="index.php?I=<?php echo pg_encrypt("WORKORDER-edit|".$id."|".$key,$pg_encrypt_key,"encode"); ?>" type="button" class="btn btn-primary">Edit Workorder</a>
                                </div>
                                <div id="save-collab-btn-group" class="col-xs-12" hidden>
                                    <button id="collab-save-btn" type="button" class="btn btn-success">Invite Collaborator</button>
                                    <button id="collab-clear-btn" type="button" class="btn btn-danger">Cancel</button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <!-- /.row -->


            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    <?php require_once('./includes/jsbs.php'); ?>
    <?php require_once('./includes/jsjqvalidation.php'); ?>

    <script>
        jQuery('#approve-btn').click(function(){
            jQuery('#updateworkorder').submit();
        });

        jQuery('#reject-btn').click(function(){
            jQuery('#approve').val('false');
            jQuery('#updateworkorder').submit();
        });

        jQuery("#workorderform").validate({
            submitHandler: function(form){
                // do any form pre-processing here
                form.submit();
            },
            ignore: [],
        });

    </script>


    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- underscore -->
    <script src="js/underscore-min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
