<?php
/************************************************************************************************
Show matrices you created in a list
Author: Michael Keough
Date Modified: 12/3/2015
************************************************************************************************/
require_once "./resources/library/user.php";

$usrDbAdapter = new UserDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
?>

		<section>
			<h1>USER LIST</h1>
			<div class="info">
				<p>&nbsp;</p>
			</div>
			<table id="matrixDT" class="display" cellspacing="0" width="100%">
				<?php
				$th_fields = "
				<th>EMAIL</th>
				<th>Fist</th>
				<th>Last</th>
				<th>GROUP</th>
				<th>ROLE</th>
				<th>EDIT</th>
				<th>Delete</th>
				";
				?>
                <thead>
					<tr>
						<?php echo $th_fields; ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<?php echo $th_fields; ?>
					</tr>
				</tfoot>
				<tbody>
					
                    <?php
						
						$users = $usrDbAdapter->SelectWhereJSON('');
						
						foreach ($users as $key => $value) {
						//for($i=0;$i<mysqltng_num_rows($matrixList_res);$i++){
							$user_name = $value->user_name;
							$user_email = $value->user_email;
							$user_name= $value->user_name;
							$user_fname= $value->user_fname;
							$user_lname= $value->user_lname;
							$user_group= $value->user_group;
							$user_id =$value->user_id;
							$form_manager = $value->form_manager;
							$user_perms =$value->user_perms;

							$ROLE = 'STAFF';
							if($user_perms == 2){
								$ROLE = "<strong style=\"color:green;\">ADMIN</strong>";	
							}else if($user_perms == 1){
								$ROLE = "<strong style=\"color:red;\">SUPER</strong>";	
							}
							

							?>
                            <tr>
                                <td><h4><?php echo $user_email ; ?></h4></td>
                                <td><h4><?php echo $user_fname; ?></h4></td>
                                <td><h4><?php echo $user_lname; ?></h4></td>
                                <td style="background:#92C87C;"><h4><?php echo $user_group; ?></h4></td>
                                <td><?php echo $ROLE; ?></td>
                                <td><h4><a class="btn btn-primary" style="width:100%" href="./?I=<?php echo pg_encrypt("ADMIN-edit_user|".$user_id,$pg_encrypt_key,"encode") ?>" />Edit User</a>
</h4></td>
                                <td> 
                                  <form role="form" action="./?I=<?php echo pg_encrypt("USER-list_user",$pg_encrypt_key,"encode") ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryUSER-deleteUSER",$pg_encrypt_key,"encode") ?>" />
                               <input type="hidden" name="user_id" value="<?php echo pg_encrypt($USR_id.$general_seed,$pg_encrypt_key,"encode"); ?>">
         
                                <input type="submit" class="btn btn-danger" style="width:100%" href="#" target="new" Value="DELETE">
                                </form>
</td>
							</tr>
                            <?php	
						
						}
					?>
				</tbody>
			</table>
