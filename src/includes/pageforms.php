<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Workorder Forms
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-edit"></i> Existing Forms
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
    <div class="row">
      <form method="post" class="form-inline" role="form">
          <div class="form-group col-lg-3">
              <input type="text" class="form-control" id="formname" name="formname" placeholder="Form Name" style="width:100%;">
          </div>
          <div class="form-group col-lg-7">
              <input type="text" class="form-control" id="formdesc" name="formdesc" placeholder="Form Description" style="width:100%;">
          </div>
          <div class="form-group col-lg-2">
            <button type="submit" class="btn btn-primary">Add Form</button>
          </div>
          <input name="addform" type="hidden"> 
      </form>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <?php
          require "./resources/library/forms_db_controller.php";
          // If post check post data and save/update form to db.
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAvailable = 0;
            if (isset($_POST["formAvailable"])) { $formAvailable = 1; }
            if (isset($_POST["updateform"])){
              $fdc = new FormsDataController();
              $result = $fdc->updateForm($_POST["id"], $_POST["formname"], $_POST["formdesc"], $_POST["xmldata"], $_POST["workflow"], $_POST["notifyOnFinalApproval"], $formAvailable, $_POST["groupWorkflows"]);
            } elseif (isset($_POST["deleteform"])) {
              $fdc = new FormsDataController();
              $result = $fdc->deleteForm($_POST["id"]);
            } elseif (isset($_POST["addform"])) {
              $fdc = new FormsDataController();
              $result = $fdc->addForm($_POST["formname"], $_POST["formdesc"], $_POST["xmldata"], $_POST["workflow"], $_POST["notifyOnFinalApproval"], $formAvailable, $_POST["groupWorkflows"]);
              if (!$result){
                echo "<div class='alert alert-danger'>All fields are required. Keep calm and try again...</div>";
              }
            }
          }
          // Show all the forms in the db
          $fdc = new FormsDataController();
          $fdc->renderFormsList();
        ?>
      </div>
    </div>
    </div>
    <!-- /.container-fluid -->

    <!-- Modal -->
    <div id="formPreviewModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:90%;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="previewformname" class="modal-title">Form Name</h4>
                    <p id="previewformdescription">Form Description</p>
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
<!-- /#page-wrapper -->

<script>
  var onPreviewClick = function (formName, formDesc, f) {
    var formxml = atob(f);
    jQuery('#form-template').html(formxml);
    jQuery('#previewformname').html(formName);
    jQuery('#previewformdescription').html(formDesc);
    var template = document.getElementById('form-template'),
    formContainer = document.getElementById('rendered-form'),
    formRenderOpts = { container: $(formContainer) };
    $(template).formRender(formRenderOpts);
    jQuery('#formPreviewModal').modal();
    return false;
  };
</script>
