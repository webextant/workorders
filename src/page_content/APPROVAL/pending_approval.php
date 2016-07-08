<?php
	//workorder class for loading workorder queries
    require_once "./resources/library/workorder.php";
    
    $currentUserEmail = $_SESSION['user_email'];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
   // $workorders = $woDbAdapter->SelectAll(300);
  
	$where_perams = '{"createdBy| =": "'.$currentUserEmail.'","approveState| =": "PendingApproval"}';
	$where_object = json_decode($where_perams);
	$workorders = $woDbAdapter->SelectWhereJSON($where_object);
?>

		<section>
			<h1>YOUR PENDING WORKORDERS</h1>
			<div class="info">
				<p>&nbsp;</p>
			</div>
			<table id="matrixDT" class="display" cellspacing="0" width="100%">
				<?php
				$th_fields = "
				<th>Created Date</th>
				<th>Current Approver</th>
				<th>Form Name</th>
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
                                <td><h4><?php echo  $value->currentApprover; ?></h4></td>
                                <td><h4><?php echo  $value->formName; ?></h4></td>
                                <td><h4><?php echo "<a href='./workorderview.php?id=".$value->id."&key=" . $value->viewOnlyKey . "' class=\"btn btn-primary\">VIEW</a>"; ?></h4></td>
                                
							</tr>
                            <?php	
						
								
                            }
                        ?>
				</tbody>
			</table>
