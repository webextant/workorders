<?php
//get Version info
$RegDomain =$appInfoDbAdapter->Get('RegDomain');
$system_version2 =$appInfoDbAdapter->Get('RegDomain');


			 
?>
<section>
  <h1>Create New User</h1>
  <div class="info">
    <p>&nbsp;</p>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h2 class="panel-title">REGISTRATION SETTINGS</h2>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <form action="./?I=<?php echo $_GET['I']; ?>" method="post" enctype="multipart/form-data">
              <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qrySETTINGS-registration_settings_qry",$pg_encrypt_key,"encode") ?>" />
              <label><h2>Limit Domains</h2></label>
              If you would like to limit domains from registering enter them here (comma seperated).  Otherwise leave blank.
              <?php
			  $domains =  $system_version2['INFO_value'];
			  $domains = str_replace('}',',',$domains);
			  $domains = str_replace('{domain=','',$domains);
			  $domains = rtrim($domains, ",");
			 
			 
			  ?>
              
              <textarea name="limit_domains" type="text" value="" class="form-control"><?php echo $domains; ?></textarea>
              
              <button type="submit" class="btn btn-success">Update Domains</button>
            </form>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
