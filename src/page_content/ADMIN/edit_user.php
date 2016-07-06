<?php
$USR_id = $header_GET_array[0];
?>
<section>
  <h1>Create New User (STILL IN DEVELOPMENT)</h1>
  <a class="btn btn-primary"  href="./?I=<?php echo pg_encrypt("ADMIN-list_user",$pg_encrypt_key,"encode") ?>" />Go Back to List</a>
  <hr>
  <div class="info">
    <p>&nbsp;</p>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h2 class="panel-title">USER DETAILS</h2>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <form action="./?I=<?php echo $_GET['I']; ?>" method="post" enctype="multipart/form-data">
              <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryUSER-editUSER_qry",$pg_encrypt_key,"encode") ?>" />
              <input type="hidden" name="user_id" value="<?php echo pg_encrypt($USR_id.$general_seed,$pg_encrypt_key,"encode"); ?>">

              <label>User First Name</label>
              <input name="first_name" type="text" value="<?php echo $USR_fname; ?>" class="form-control">
              <label>User Last Name</label>
              <input name="last_name" type="text" value="<?php echo $USR_lname; ?>" class="form-control">
              <label>User email (also username)</label>
              <input name="user_email" type="email" value="<?php echo $USR_username; ?>" class="form-control">
              <label>User Role</label>
              <select required name="user_role" class="form-control">
             
                <option <?php if($USR_role == 1) echo "selected='selected'"; ?> value="1">STUDENT</option>
                <option <?php if($USR_role == 2) echo "selected='selected'"; ?> value="2">INSTRUCTOR</option>
                <option <?php if($USR_role == 3) echo "selected='selected'"; ?> value="3">ADMINISTRATOR</option>
              </select>
              <button type="submit" class="btn btn-success">EDIT USER</button>
            </form>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
