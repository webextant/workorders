<?php
	//workorder class for loading workorder queries
    require_once "./resources/library/workorder.php";
    
    $currentUserEmail = $_SESSION['user_email'];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
   if($_SESSION['user_perms'] >2){
		$query_block = '"createdBy|=": "'.$currentUserEmail.'",';
   }
   $workorders = $woDbAdapter->SelectAllWhereCollaborator(300);
?>

		<section>
			<h1>YOU ARE COLLABORATING</h1>
			<div class="info">
				<p>&nbsp;</p>
			</div>
			<table id="matrixDT" class="display" cellspacing="0" width="100%">
				<?php
				$th_fields = "
				<th>Date Created</th>
				
            <th>Created By</th>
            <th>View</th>
            <th>Type</th>
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
                            foreach ($workorders as $key => $value) {
                               $split_state = preg_split('/(?=[A-Z])/',$value->approveState);
							   $approveState_val = '';
							   $color = '#000000';
							  
							

							?>
                            <tr>
                                <td><h4><?php echo  $value->createdAt; ?></h4></td>
                                <td><h4><?php echo  $value->createdBy; ?></h4></td>
                                <!-- workorderview.php?id=13&key=6EAF159514D440656E37BD6924ECD446 -->
                                <td><h4><?php echo "<a href='./?I=" . pg_encrypt('WORKORDER-work|'.$value->id.'|'.$value->viewOnlyKey,$pg_encrypt_key,'encode') . "' class=\"btn btn-primary\">VIEW</a>"; ?></h4></td>
                                <td><?php echo $value->formName; ?></td>
							</tr>
                            <?php	
						
								
                            }
                        ?>
				</tbody>
			</table>
            </section>
            
