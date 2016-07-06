<?php
    require_once "./resources/library/approver.php";
    require_once "./resources/library/forms_db_controller.php";
    require_once "./resources/library/user.php";
    require_once "./config/db.php";
    $fdc = new FormsDataController();
    $result = $fdc->getFormById($_POST["id"]);
    $approverAdapter = new ApproverDataAdapter($dsn, $user_name, $pass_word);
    $allApprovers = $approverAdapter->SelectAll();
    $userAdapter = new UserDataAdapter($dsn, $user_name, $pass_word);
    $userGroups = $userAdapter->SelectUniqueGroupNames();
    $groupWorkflowSuffix = 'Workflow';  // appended to groupWorkflow id's
?>

<script type="text/javascript">
    <?php
        function getEmailAddress($e)
        {
            return $e->email;
        }
        
        $js_array = json_encode(array_map("getEmailAddress", $allApprovers));
        echo "var autocompleteEmails = " . $js_array . ";\n";
    ?>

    // javascript objects for group workflows
    <?php
        $jsGroupWorkflowsValue = '{}';
        if (!is_null($result['GroupWorkflows'])) {
            $jsGroupWorkflowsValue = $result['GroupWorkflows'];
        }
    ?>
    var groupWorkflows = <?php echo $jsGroupWorkflowsValue; ?>;
    var groupWorkflowSuffix = "<?php echo $groupWorkflowSuffix ?>"; // JS var for appending to groupWorkflow id's
    var userGroups = <?php echo json_encode($userGroups); ?>;
    
    // Javascript function to handle approver list tagit
    function handleApproverSelectOnChange(value)
    {
        if (value != "choose"){
            $('#myEmailTags').tagit('createTag', value);            
        }
        return false;
    }
    // Javascript function to handle additional notifications tagit
    function handleNotificationsSelectOnChange(value)
    {
        if (value != "choose"){
            $('#myFinalEmailTags').tagit('createTag', value);            
        }
        return false;
    }
    
</script>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?php echo htmlspecialchars($result["FormName"]); ?>
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-edit"></i>
                        <?php echo htmlspecialchars($result["Description"]); ?>
                    </li>
                </ol>
                
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div id='save-form' class="col-lg-10">
              <form class="form-horizontal" id="formsaveform" action="forms.php" method="post">
                <input id="id" name="id" type="hidden" value="<?php echo htmlspecialchars($_POST["id"]); ?>">
                <input id="formname" name="formname" type="hidden" value="<?php echo htmlspecialchars($result['FormName']); ?>">
                <input id="formdesc" name="formdesc" type="hidden" value="<?php echo htmlspecialchars($result['Description']); ?>">
                <input id="xmldata" name="xmldata" type="hidden" value="">
                <input id="updateform" name="updateform" type="hidden" value=""> 
                <input id="emailTags" name="workflow" type="text" hidden required value="<?php echo htmlspecialchars($result['Workflow']); ?>">
                <input id="finalEmailTags" name="notifyOnFinalApproval" type="text" value="<?php echo htmlspecialchars($result['notifyOnFinalApproval']); ?>" hidden>
                <input id="groupWorkflows" name="groupWorkflows" type="text" hidden value="<?php echo htmlspecialchars($result['GroupWorkflows']); ?>">
                <label class="checkbox-inline"><input name="formAvailable" type="checkbox" <?php if($result['Available'] == 1) {echo "checked";} ?>> Form Available For Submissions</label>
              </form>
            </div>
            <div class="col-lg-2 text-right">
              <button id="saveformbtn" name="saveformbtn" class="btn btn-primary">Save</button>
              <button id='previewbtn' class='btn btn-success'>Preview</button>
            </div>
        </div>
        <!-- /.row -->
        
        <div class="row">
            <div class="col-lg-12 text-right">
                <hr/>
                <div id="validationMessages" class="bg-danger text-left"></div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#myEmailTags").tagit({
                            availableTags: autocompleteEmails,
                            singleField: true,
                            singleFieldNode: $('#emailTags')
                        });
                    });
                </script>
                <form>
                    <select onchange="handleApproverSelectOnChange(this.value)">
                        <option value="choose">Choose Approver Email</option>
                    <?php
                        foreach ($allApprovers as $key => $value) {
                            echo "<option value='" . $value->email . "'>" . $value->email . "</option>";
                        }
                    ?>
                    </select>
                    <p class="text-left"><b>District Approver List</b> - <small>Always processed after group approval.</small></p>
                    <ul id="myEmailTags"></ul>
                </form>
                
                <?php // BEGIN group workflow approver tabs  ?>
                <script>
                    $(function() {
                        $("#grouptabs").tabs();
                    });
                </script>
                <p class="text-left"><b>Group Approver Lists</b> - <small>Group approvers are processed before district.</small></p>                
                <div id="grouptabs">
                    <ul>
                        <?php
                            // Build the group tabs
                            foreach ($userGroups as $key => $group) {
                                $hrefTabsGroupName = str_replace(" ", "-", $group->name);
                                echo '<li><a href="#tabs-' . $hrefTabsGroupName . '">' . $group->name . '</a></li>';
                            }
                        ?>
                    </ul>
                    <?php
                        // build tab content for each group tab. Using Tagit for UI
                        foreach ($userGroups as $key => $group) {
                            $tabContentGroupName = str_replace(" ", "-", $group->name);
                    ?>
                            
                            <input id="<?php echo $tabContentGroupName . $groupWorkflowSuffix ?>" type="text" value="" hidden />

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $(<?php echo '"#' . $tabContentGroupName . $groupWorkflowSuffix . '"'; ?>).val(groupWorkflows['<?php echo $tabContentGroupName; ?>']);
                                    $(<?php echo '"#' . $tabContentGroupName . 'Tags"'; ?>).tagit({
                                        availableTags: autocompleteEmails,
                                        singleField: true,
                                        singleFieldNode: $(<?php echo '"#' . $tabContentGroupName . $groupWorkflowSuffix . '"'; ?>)
                                    });
                                });
                            </script>
                    <?php
                            
                            echo '<div id="tabs-' . $tabContentGroupName . '">';
                            // build tab content here
                            echo '<ul id="' . $tabContentGroupName . 'Tags"></ul>';

                            echo '</div>';
                        }
                    ?>
                </div>
                <?php // END group workflow approver tabs  ?>
                
                <hr/>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div id='build-form' class="col-lg-12">
              <textarea id='form-builder-template'><?php echo base64_decode(htmlspecialchars($result['FormXml'])); ?></textarea>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div id='final-email-tags' class="col-lg-12 text-right">
                <hr/>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#myFinalEmailTags").tagit({
                            availableTags: autocompleteEmails,
                            singleField: true,
                            singleFieldNode: $('#finalEmailTags')
                        });
                    });
                </script>
                <form>
                    <select onchange="handleNotificationsSelectOnChange(this.value)">
                        <option value="choose">Choose Notification Email</option>
                    <?php
                        foreach ($allApprovers as $key => $value) {
                            echo "<option value='" . $value->email . "'>" . $value->email . "</option>";
                        }
                    ?>
                    </select>
                    <p class="text-left"><b>Additional Notifications upon Final Approval</b></p>
                    <ul id="myFinalEmailTags"></ul>
                </form>
                <hr/>
            </div>
        </div>
        <!-- /.row -->

    <!-- Modal -->
    <div id="formPreviewModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:90%;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Preview</h4>
                </div>
                <div class="modal-body">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <div id="formpreview">
                          <div id='rendered-form' class='row'></div>
                          <textarea id="form-template" style="display:none;"></textarea>
                        </div>
                      </div>
                      <div class="col-md-2"></div>
                    </div>
                  </div>
                </div>
            </div>

        </div>
    </div>

</div>
hello
<!-- /#page-wrapper -->

        <script>
            // Read data from tag-it elements for each workflow group and compile for server.  
            var compileGroupWorkflowsData = function(){
                var data = {};
                userGroups.map(function(group){
                    var groupDashedName = group.name.replace(/ /g, '-');
                    var groupTags = jQuery("#" + groupDashedName + groupWorkflowSuffix).val().split(",");
                    data[groupDashedName] = groupTags;
                });
                return data;
            };

          var formBuilder;
          jQuery(document).ready(function($) {
            'use strict';
            var template = document.getElementById('form-builder-template');
            var options = {
                disableFields: ['file', 'autocomplete', 'button']
            };
            formBuilder = jQuery(template).formBuilder(options);
            jQuery('.form-actions').html(""); // remove the formBuilder buttons. 
          });
          jQuery('#saveformbtn').click(function(){
              jQuery('#formsaveform').submit();
          });
          jQuery('#previewbtn').click(function(){
            var template = document.getElementById('form-builder-template'),
            formContainer = document.getElementById('rendered-form'),
            formRenderOpts = { container: $(formContainer) };
            $(template).formRender(formRenderOpts);
            jQuery('#formPreviewModal').modal();
          });
          jQuery("#formsaveform").validate({
              submitHandler: function(form){
                var xmlString = formBuilder.data('formBuilder').formData;
                // Sometimes the formData is an xml object and must be serialized to a string
                if (typeof xmlString === 'object') {
                    xmlString = new XMLSerializer().serializeToString(xmlString);
                }
                // Set the xml data string before sending to server.
                jQuery('input[name="xmldata"]').val(xmlString);
                // Set the groupWorkflows data object before send to server.
                jQuery('input[name="groupWorkflows"]').val(JSON.stringify(compileGroupWorkflowsData()));
                form.submit();
              },
              ignore: [],
              errorPlacement: function(error, element) {
                  jQuery("#validationMessages").html("");
                  error.appendTo(jQuery("#validationMessages"));
              },
              messages: {
                  workflow: {
                      required: "<b>District Approver List:</b> At least one email must be added to the list."
                  }
              }
          });
        </script>


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
