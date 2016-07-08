<?php
require_once "./resources/library/user.php";
$usrDbAdapter = new UserDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);

$user_id = $header_GET_array[0];

$where_perams = '{"user_id| =": "'.$user_id.'"}';
$where_object = json_decode($where_perams);

$users = $usrDbAdapter->SelectWhereJSON($where_object);

	foreach ($users as $key => $value) {
	//for($i=0;$i<mysqltng_num_rows($matrixList_res);$i++){
		$user_name = $value->user_name;
		$user_email = $value->user_email;
		$user_name= $value->user_name;
		$user_fname= $value->user_fname;
		$user_lname= $value->user_lname;
		$user_id =$value->user_id;
		$form_manager = $value->form_manager;
		$user_perms =$value->user_perms;
	}
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
              <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryUSER-edit_user_qry",$pg_encrypt_key,"encode") ?>" />
              <input type="hidden" name="useriD" value="<?php echo pg_encrypt($user_id.$general_seed,$pg_encrypt_key,"encode"); ?>">

              <label>User Username</label>
              <input name="username" type="text" value="<?php echo $user_name; ?>" class="form-control">
              <label>User First Name</label>
              <input name="first_name" type="text" value="<?php echo $user_fname; ?>" class="form-control">
              <label>User Last Name</label>
              <input name="last_name" type="text" value="<?php echo $user_lname; ?>" class="form-control">
              <label>User email (also username)</label>
              <input name="user_email" type="email" value="<?php echo $user_email; ?>" class="form-control">
              <label>User Role</label>
              <select required name="user_role" class="form-control">
             
                <option <?php if($user_perms == 3) echo "selected='selected'"; ?> value="1">STAFF</option>
                <option <?php if($user_perms == 2) echo "selected='selected'"; ?> value="2">ADMIN</option>
                <option <?php if($user_perms == 1) echo "selected='selected'"; ?> value="3">SUPER</option>
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
