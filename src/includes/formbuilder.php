<div class="row">
    <div id='build-form' class="col-lg-12">
      <div id='form-builder-template' class='row'></div>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div id='preview-form' class="col-lg-12">
      <button id='previewbtn'>Preview</button>
      <div id='rendered-form' class='row'></div>
    </div>
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
</script>
