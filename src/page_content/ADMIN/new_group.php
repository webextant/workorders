<section>
  <h1>Create a Group</h1>
  <a class="btn btn-primary"  href="./?I=<?php echo pg_encrypt("ADMIN-list_groups",$pg_encrypt_key,"encode") ?>" />Go Back to List</a>
  <hr>
  <div class="info">
    <p>&nbsp;</p>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h2 class="panel-title">GROUP DETAILS</h2>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <form action="./?I=<?php echo  pg_encrypt("ADMIN-list_groups",$pg_encrypt_key,"encode"); ?>" method="post" enctype="multipart/form-data">
              <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryGROUP-new_group_qry",$pg_encrypt_key,"encode") ?>" />

              <label>Group Name</label>
              <input name="group_name" type="text" value="<?php echo $GRP_name; ?>" class="form-control">
              
              <button type="submit" class="btn btn-danger">CREATE</button>
            </form>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
