<?php
	//workorder class for loading workorder queries
    require_once "./resources/library/workorder.php";
    
    $currentUserEmail = $_SESSION['user_email'];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
   // $workorders = $woDbAdapter->SelectAll(300);
   	 $back_Date= date("Y-m-d", strtotime("-6 months", strtotime(date('Y-m-d')))); //2015-05-22 10:35:10

   	 //$1_year_ago = date("Y-m-d H:i:s", strtotime("-1 years", strtotime(date('Y-m-d H:i:s')))); //2015-05-22 10:35:10

   if($_SESSION['user_perms'] >2){
		$query_block = '"createdBy|=": "'.$currentUserEmail.'",';
   }
	$where_perams = '{'.$query_block.'"approveState| like": "%closed","createdAt| >": "'.$back_Date.'"}';
	$where_object = json_decode($where_perams);
	$workorders = $woDbAdapter->SelectWhereJSON($where_object);
	



?>

		<section>
			<h1>CLOSED WORKORDERS (<span style="color:#F98401">Since <?php echo $back_Date; ?></span>)</h1>
			<div class="info">
				<p>&nbsp;</p>
			</div>
			<table id="matrixDT" class="display" cellspacing="0" width="100%">
				<?php
				$th_fields = "
				<th>Created</th>
            <th>Last Approver</th>
            <th>VIEW</th>
            <th>Type</th>
            <th>Approval</th>
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
							   foreach($split_state as $word){
							   		$approveState_val .= $word." ";	
							   }
							   if(strpos($approveState_val,"Closed")){
									$color = "#03A015";   
							   }
							   if(strpos($approveState_val,"Reject")){
									$color = "red";   
							   }
								
								$color = '';
								if($value->approveState == "ApproveClosed"){
									$approveState_val = "Approved";
									$color = '2C8F03';
									$btncode = 'success';
								}else{
									$approveState_val = "Rejected";
									$color = 'F50206';
									$btncode = 'danger';
								}
							
							

							?>
                            <tr>
                                <td><h4><?php echo  $value->createdAt; ?></h4></td>
                                <td><h4><?php echo  $value->currentApprover; ?></h4></td>
                                <td><h4><?php echo "<a href='./?I=" . pg_encrypt('WORKORDER-work|'.$value->id.'|'.$value->viewOnlyKey,$pg_encrypt_key,'encode') . "' class=\"btn btn-".$btncode."\">VIEW</a>"; ?></h4></td>
                                <td><?php echo $value->formName; ?></td>
                                <td style="color:#<?php echo $color; ?>"><?php echo $approveState_val; ?></td>
                                
							</tr>
                            <?php	
						
								
                            }
                        ?>
				</tbody>
			</table>
