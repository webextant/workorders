
<section>
  <h1>Create New User</h1>
  <a class="btn btn-primary"  href="./?I=<?php echo pg_encrypt("USER-list",$pg_encrypt_key,"encode") ?>" />Go Back to List</a>
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
              <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryUSER-new_qry",$pg_encrypt_key,"encode") ?>" />
              <label>User First Name</label>
              <input name="first_name" type="text" value="" class="form-control">
              <label>User Last Name</label>
              <input name="last_name" type="text" value="" class="form-control">
              <label>User email (also username)</label>
              <input name="user_email" type="email" value="" class="form-control">
              <label>User Role</label>
              <select required name="user_role" class="form-control">
                <option value="1">STUDENT</option>
                <option value="2">INSTRUCTOR</option>
                <option value="3">ADMMINISTRATOR</option>
              </select>
              <button type="submit" class="btn btn-success">CREATE USER</button>
            </form>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
