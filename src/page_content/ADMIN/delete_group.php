<?php
/************************************************************************************************
Show matrices you created in a list
Author: Michael Keough
Date Modified: 12/3/2015
************************************************************************************************/
require_once "./resources/library/groups.php";
$GRP_id_active = $header_GET_array[0];
$GRP_name_active = $header_GET_array[1];

$groupDbAdapter = new groupDataAdapter($dsn, $user_name, $pass_word);
?>

<section>
  <h1>Delete the group named (<span style="color:#1C4AF8"><?php echo $GRP_name_active; ?></span>)</h1>
  <a class="btn btn-primary"  href="./?I=<?php echo pg_encrypt("ADMIN-list_groups",$pg_encrypt_key,"encode") ?>" />Go Back to Group List</a>
  <hr>
  <div class="info">
    <p>&nbsp;</p>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-red">
        <div class="panel-heading">
          <h2 class="panel-title">GROUP LIST</h2>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <form action="./?I=<?php echo pg_encrypt("ADMIN-list_groups",$pg_encrypt_key,"encode"); ?>" method="post" enctype="multipart/form-data">
              <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryGROUP-delete_group_qry",$pg_encrypt_key,"encode") ?>" />
              <input type="hidden" name="group_id" value="<?php echo pg_encrypt($GRP_id_active.$general_seed,$pg_encrypt_key,"encode"); ?>">
              <section>
                <h1>Select a Group</h1>
                <div class="info">
                  <p>You may have users in the group you are trying to delete.  These users must be moved to another group before deleting it.</p>
                </div>
                <table id="matrixDT" class="display" cellspacing="0" width="100%">
                  <?php
				$th_fields = "
				<th>Group Name</th>
				<th>SELECT</th>
			
				";
				?>
                  <thead>
                    <tr> <?php echo $th_fields; ?> </tr>
                  </thead>
                  <tfoot>
                    <tr> <?php echo $th_fields; ?> </tr>
                  </tfoot>
                  <tbody>
                    <?php
						
						$groups = $groupDbAdapter->SelectAll('');
						
						foreach ($groups as $key => $value) {
						//for($i=0;$i<mysqltng_num_rows($matrixList_res);$i++){
							$transfer_GRP_id = $value->GRP_id;
							$GRP_name = $value->GRP_name;


							
							if($GRP_name <> $GRP_name_active){

							?>
                    <tr>
                      <td><h4><?php echo $GRP_name; ?></h4></td>
                      <td style="background:#64B8DB"><input required style="border: 0px;width: 100%;height: 2em;" type="radio" name="transferTo" value="<?php echo pg_encrypt($transfer_GRP_id.$general_seed,$pg_encrypt_key,"encode"); ?>"></td>
                    </tr>
                    <?php	
							}
						}
					?>
                  </tbody>
                </table>
              </section>
                            <button type="submit" class="btn btn-danger">DELETE THIS GROUP (this is FINAL)</button>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
