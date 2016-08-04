<?php
/************************************************************************************************
Show matrices you created in a list
Author: Michael Keough
Date Modified: 12/3/2015
************************************************************************************************/
require_once "./resources/library/groups.php";

$groupDbAdapter = new groupDataAdapter($dsn, $user_name, $pass_word);
?>

		<section>
			<h1>Group List</h1>
              <a class="btn btn-primary"  href="./?I=<?php echo pg_encrypt("ADMIN-new_group",$pg_encrypt_key,"encode") ?>" />New Group</a>

			<div class="info">
				<p>&nbsp;</p>
			</div>
			<table id="matrixDT" class="display" cellspacing="0" width="100%">
				<?php
				$th_fields = "
				<th>Group Name</th>
				<th>Edit</th>
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
						
						$groups = $groupDbAdapter->SelectAll('');
						
						foreach ($groups as $key => $value) {
						//for($i=0;$i<mysqltng_num_rows($matrixList_res);$i++){
							$GRP_id = $value->GRP_id;
							$GRP_name = $value->GRP_name;


							
							

							?>
                            <tr>
                                <td><h4><?php echo $GRP_name; ?></h4></td>
                                
                                <td><h4><a class="btn btn-success" style="width:100%" href="./?I=<?php echo pg_encrypt("ADMIN-edit_group|".$GRP_id,$pg_encrypt_key,"encode") ?>" />Edit Group</a>
								<td><h4><a class="btn btn-danger" style="width:100%" href="./?I=<?php echo pg_encrypt("ADMIN-delete_group|".$GRP_id."|".$GRP_name,$pg_encrypt_key,"encode") ?>" />Delete Group</a>

							</tr>
                            <?php	
						
						}
					?>
				</tbody>
			</table>
