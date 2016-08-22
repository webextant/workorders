<?php
/************************************************************************************************
Work with a workorder. View, approve, deny, or collab depending on input
Author: Raymond Brady
Date Created: 8/16/2016
************************************************************************************************/
    require_once('./resources/library/workorder.php');
    require_once('./resources/library/collaborator.php');

    $woId = $header_GET_array[0];
    $approveKey = $header_GET_array[1];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word);
    $wo = $woDbAdapter->Select($woId);
    $woViewModel = new WorkorderViewModel($wo, $approveKey, $_SESSION['user_email']);
    $collabViewModel = new CollaboratorViewModel($dsn, $user_name, $pass_word, $_SESSION['user_email']);

    $acceptBtnText = "APPROVE (not final)";
    $rejectBtnText = "DENY";
    if ($woViewModel->isFinalApproval){
        $acceptBtnText = "APPROVE (final)";
        $rejectBtnText = "DENY";
        $finalApprovalHiddenClass = "hidden";
    }

    if (!$woViewModel->valid):
        echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Unable to view the workorder. You may not be authorized...</div>';
        die();
    endif;

    if ($woViewModel->hasCollaborator){
        $addCollabFormPostType = pg_encrypt("qryWORKORDER-add_comment_qry",$pg_encrypt_key,"encode");        
    } else {
        $addCollabFormPostType = pg_encrypt("qryWORKORDER-add_collab_qry",$pg_encrypt_key,"encode");
    }
?>
    <!-- Page row 1 -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?=$wo->formName?></h1>
            <ol class="breadcrumb">
                <?php
                    if ($login->isUserLoggedIn() == true):
                        if($_SESSION['user_perms'] <=2):
                ?>
                        <li class="">
                            <i class="fa fa-fw fa-folder"></i><a href="index.php?I=<?php echo pg_encrypt("APPROVAL-needs_approval",$pg_encrypt_key,"encode"); ?>"> NEEDS APPROVAL</a>
                        </li>
                <?php
                        endif;
                    endif;
                ?>
                <li class="active">
                    <i class="fa fa-fw fa-file"></i> <?=$woViewModel->workorderIdText?>
                </li>
                <li>
                    <?=$wo->createdAt?>
                </li>
                <li>From: <?=$wo->createdBy?></li>
            </ol>
        </div>
    </div>
    <!-- END Page row 1 -->

    <!-- Page row 2 -->
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="<?=$woViewModel->stateColorClass?>"><?=$woViewModel->approveState . " (" . $wo->currentApprover . ")"?></div>
<?php if(!$woViewModel->isClosed && ($woViewModel->userIsCurrentApprover || $woViewModel->userIsCollaborator)): ?>
            <div id="collaboratorInfo" class="<?=$woViewModel->collaboratorStateClass?>">
                <i class="fa fa-info-circle" style="float:right;" aria-hidden="true" title="Collaborator can view and comment on this work item." ></i>
                <h4><i class="fa fa-users" aria-hidden="true" ></i> Collaborator</h4>
    <?php if($woViewModel->hasCollaborator): ?>
                    <?php
                        foreach ($woViewModel->currentCollaborators as $key => $value):
                            $fname = $value['user_fname'];
                            $lname = $value['user_lname'];
                            $email = $value['user_email'];
                            echo "<p>$fname $lname ($email)</p>";
                        endforeach;
                    ?>
    <?php endif; ?>
                <form id="addcollabform" action="./?I=<?=pg_encrypt('WORKORDER-work|'.$wo->id."|".$wo->approverKey,$pg_encrypt_key,'encode')?>" method="post">
                    <input type="hidden" id="post_type" name="post_type" value="<?php echo $addCollabFormPostType ?>" />
                    <input type="hidden" id="id" name="id" value="<?=$wo->id?>" />
                    <input type="hidden" id="key" name="key" value="<?=$approveKey?>" />
                    <input type="hidden" id="collabcomment" name="collabcomment" value="" />
                  <?php if(!$woViewModel->hasCollaborator): ?>
                    <select id="collabUserSelect" name="collabUserSelect" class="form-control">
                        <option value="" disabled selected hidden>Select a Collaborator</option>
                    </select>
                  <?php endif; ?>
                </form>
            </div>
<?php endif; ?>
            <?php 
                foreach ($woViewModel->fieldData as $fieldkey => $value):
                    echo "<h4>" . $value["Label"] . "</h4>";
                    echo "<P>" . $value["Data"] . "</p>";
                endforeach;
            ?>
            <h4>Discussion</h4>
            <?php
                if (count($woViewModel->comments) == 0):
                    echo "<span>No comments posted.</span>";
                else:
                    echo "<ul style='margin:0px auto 0px auto;padding:0;list-style-type:none;max-width:475px;'>";
                    foreach ($woViewModel->comments as $commentkey => $value):
                        echo "<li style='padding:12px;margin:10px 0; background-color:#f5f5f5;border-radius:5px;border:1px solid #e1e1e1;'>";
                        echo "<div class='media'>";
                        echo "<div class='media-body'>";
                        echo "<div style='margin:0px 12px 0px 0px;' class='pull-left'><i class='fa fa-user fa-3x'></i></div>";
                        echo "<h5 class='media-heading'><b>" . $value['createdBy'] . "</b></h4>";
                        echo "<p class='small text-muted'><i class='fa fa-clock-o'></i> " . $value['createdAt'] . "</p>";
                        echo "<p>" . $value['commentData'] . "</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</li>";
                    endforeach;
                    echo "</ul>";
                endif;
            ?>

            <hr/>
<?php if(!$woViewModel->isClosed && ($woViewModel->userIsCurrentApprover || $woViewModel->userIsCollaborator)): ?>
            <form id="updateworkorder" action="./?I=<?=pg_encrypt('APPROVAL-needs_approval|'.$wo->id."|".$wo->approverKey,$pg_encrypt_key,'encode')?>" method="post">
                <div class="form-group">
                    <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryWORKORDER-update_approve_qry",$pg_encrypt_key,"encode") ?>" />
                    <input type="hidden" name="id" value="<?php echo $wo->id; ?>">
                    <input type="hidden" name="key" value="<?php echo $approveKey; ?>">
                    <input type="hidden" id="approve" name="approve" value="true">
                    <label for="comment">Comment</label>
                    <textarea id="comment" name="comment" class="form-control" rows="5" required></textarea>
                </div>
    <?php if ($woViewModel->hasCollaborator && !$woViewModel->isClosed): ?>
                <div class="row">
                    <div id="approve-btn-group" class="col-xs-12">
                        <button id="save-comment-btn" type="button" class="btn btn-success">Save Comment</button>
                        <button id="finish-collab-btn" type="button" class="btn btn-danger pull-right">End Collaboration</button>
                        <a href="index.php?I=<?php echo pg_encrypt("WORKORDER-edit|".$wo->id."|".$approveKey,$pg_encrypt_key,"encode"); ?>" type="button" class="btn btn-primary">Edit Workorder</a>
                    </div>
                </div>
    <?php elseif(!$woViewModel->hasCollaborator && !$woViewModel->isClosed): ?>
                <div class="row">
                    <div id="approve-btn-group" class="col-xs-12">
                        <button id="approve-btn" type="button" class="btn btn-success"><?=$acceptBtnText?></button>
                        <button id="reject-btn" type="button" class="btn btn-danger"><?=$rejectBtnText?></button>
                        <a  href="index.php?I=<?php echo pg_encrypt("WORKORDER-edit|".$wo->id."|".$approveKey,$pg_encrypt_key,"encode"); ?>" type="button" class="btn btn-primary">Edit Workorder</a>
                    </div>
                    <div id="save-collab-btn-group" class="col-xs-12" hidden>
                        <button id="collab-save-btn" type="button" class="btn btn-success">Invite Collaborator</button>
                        <button id="collab-clear-btn" type="button" class="btn btn-danger pull-right">Cancel</button>
                    </div>
                </div>
    <?php endif; ?>
            </form>
<?php endif; ?>
        </div>
        <div class="col-lg-3"></div>
    </div>
    <!-- END Page row 2 -->

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

    <script src="js/library/collaborator.js" ></script>
    <script>
        var cvm = new CollaboratorViewModel(<?=json_encode($collabViewModel->collabUsers)?>);
        var submitCollabComments = function(){
            var comment = jQuery('#comment').val();
            jQuery('#collabcomment').val(comment);
            jQuery('#addcollabform').submit();
        };

        cvm.connectSelectElement("collabUserSelect")
        cvm.subscribeCollabChanged(function(e){
            console.log(e);
            if (e.hasSelection){
                jQuery("#approve-btn-group").hide();
                jQuery("#save-collab-btn-group").show();
            } else {
                jQuery("#save-collab-btn-group").hide();
                jQuery("#approve-btn-group").show();
            }
        });
        jQuery('#collab-clear-btn').click(function(){
            cvm.clearCollab();
        });
        jQuery('#collab-save-btn').click(submitCollabComments);
        jQuery('#save-comment-btn').click(submitCollabComments);

    </script>
