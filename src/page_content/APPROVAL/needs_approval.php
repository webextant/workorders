<?php
	//workorder class for loading workorder queries
    require_once "./resources/library/workorder.php";
    
    $currentUserEmail = $_SESSION['user_email'];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
   // $workorders = $woDbAdapter->SelectAll(300);
   if($_SESSION['user_perms'] >2){
		$query_block = '"createdBy|=": "'.$currentUserEmail.'",';
   }
	$where_perams = '{"currentApprover| =": "'.$currentUserEmail.'","approveState| =": "PendingApproval"}';
	$where_object = json_decode($where_perams);
	$workorders = $woDbAdapter->SelectWhereJSON($where_object);
?>

		<section>
			<h1>NEEDS YOUR APPROVAL</h1>
			<div class="info">
				<p>&nbsp;</p>
			</div>
			<table id="matrixDT" class="display" cellspacing="0" width="100%">
				<?php
				$th_fields = "
				<th>Date Created</th>
				
            <th>Created By</th>
            <th>Approve / Reject</th>
            <th>Type</th>
			<th>Edit</th>
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
                                <td><h4><?php echo "<a href='./workorderview.php?id=".$value->id."&key=" . $value->approverKey . "' class=\"btn btn-primary\">VIEW</a>"; ?></h4></td>
                                <td><?php echo $value->formName; ?></td>
                                <td><h4><a href="index.php?I=<?php echo pg_encrypt("WORKORDER-edit|".$value->id."|".$value->approverKey,$pg_encrypt_key,"encode"); ?>" class="btn btn-primary">EDIT</a></h4></td>
                                
							</tr>
                            <?php	
						
								
                            }
                        ?>
				</tbody>
			</table>
