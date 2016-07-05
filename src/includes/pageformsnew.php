<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    New Workorder Form
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-edit"></i> New Form
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div id='save-form' class="col-lg-12">
              <form class="form-horizontal" id="formsaveform" action="forms.php" method="post">
              <fieldset>
              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-4 control-label" for="formname">Form Name</label>
                <div class="col-md-4">
                  <input id="formname" name="formname" type="text" class="form-control input-md" required="true">
                </div>
              </div>
              <!-- Textarea -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="formdesc">Form Description</label>
                <div class="col-md-4">
                  <textarea class="form-control" id="formdesc" name="formdesc" required="true"></textarea>
                </div>
              </div>
              <input name="xmldata" type="hidden">
              <!-- Button -->
              <div class="form-group">
                <label class="col-md-4 control-label" for="savebtn"></label>
                <div class="col-md-4">
                  <button id="savebtn" name="savebtn" class="btn btn-primary">Add Form</button>
                </div>
              </div>

              </fieldset>
              </form>
              <hr/>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div id='build-form' class="col-lg-12">
              <textarea id='form-builder-template'></textarea>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-2"></div>
            <div id='preview-form' class="col-lg-8">
              <hr/>
              <button id='previewbtn' class='btn btn-primary btn-block'>Preview Form</button>
              <hr/>
              <div id='rendered-form' class='row'></div>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <!-- /.row -->


        <script>
          jQuery(document).ready(function($) {
            'use strict';
            var template = document.getElementById('form-builder-template');
            jQuery(template).formBuilder();
          });
          jQuery('#previewbtn').click(function(){
            var template = document.getElementById('form-builder-template'),
            formContainer = document.getElementById('rendered-form'),
            formRenderOpts = { container: $(formContainer) };
            $(template).formRender(formRenderOpts);
          });
          jQuery("#formsaveform").submit(function(e){
            var xmlString = jQuery(".frmb").toXML();
            $('input[name="xmldata"]').val(xmlString);
            return true;
          });
        </script>


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
