<?php
	//workorder class for loading workorder queries
    require_once "./resources/library/workorder.php";
    
    $currentUserEmail = $_SESSION['user_email'];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
   // $workorders = $woDbAdapter->SelectAll(300);
?>

<div id="page-wrapper">
  <div class="container-fluid">
    <h1>DASHBOARD</h1>
    <?php
	   if($_SESSION['user_perms'] <=2){
	?>
    <div class="col-lg-3 col-md-6"> <a href="index.php?I=<?php echo pg_encrypt("APPROVAL-needs_approval",$pg_encrypt_key,"encode"); ?>">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3"> <i class="fa fa-thumbs-up fa-4x"></i> </div>
            <div class="col-xs-9 text-right">
              <div style="font-size:24px;">APPROVE</div>
              <div>Approvals Needed</div>
            </div>
          </div>
        </div>
        <div class="panel-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
      </div>
      </a> </div>
     <?php
	   }
	 ?> 
    <div class="col-lg-3 col-md-6"> <a href="index.php?I=<?php echo pg_encrypt("WORKORDER-create",$pg_encrypt_key,"encode"); ?>">
      <div class="panel panel-green">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3"> <i class="fa fa-edit fa-4x"></i> </div>
            <div class="col-xs-9 text-right">
              <div style="font-size:24px;">NEW</div>
              <div>Create a Workorder</div>
            </div>
          </div>
        </div>
        <div class="panel-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
      </div>
      </a> </div>
      
    <div class="col-lg-3 col-md-6"> <a href="index.php?I=<?php echo pg_encrypt("APPROVAL-pending_approval",$pg_encrypt_key,"encode"); ?>">
      <div class="panel panel-yellow">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3"> <i class="fa fa-eye fa-4x"></i> </div>
            <div class="col-xs-9 text-right">
              <div style="font-size:24px;">PENDING</div>
              <div>View In-Progress</div>
            </div>
          </div>
        </div>
        <div class="panel-footer"> <span class="pull-left">View Details</span> <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
      </div>
      </a> </div>
      
  </div>
  
  <hr>
  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"> My Workorders </h1>
      <ol class="breadcrumb">
        <li class="active"> <i class="fa fa-dashboard"></i> Workorder Listing </li>
      </ol>
    </div>
  </div>
  <!-- /.row --> 
  
  <?php
  //testing deleting this PHP section
$where_perams = '{"createdBy|=": "'.$currentUserEmail.'"}';
$where_object = json_decode($where_perams);
$workorders = $woDbAdapter->SelectWhereJSON($where_object);
?>
  
  <!-- Page row -->
  <div class="row">
    <div class="col-lg-12">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Created</th>
            <th>VIEW</th>
            <th>Type</th>
            <th>Current Approver</th>
            <th>Status</th>
            <th>Id</th>
          </tr>
        </thead>
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
								echo "<tr>";
                                    echo "<td>" . $value->createdAt . "</td>";
                                    echo "<td><a href='./?I=" . pg_encrypt('WORKORDER-work|'.$value->id."|".$value->viewOnlyKey,$pg_encrypt_key,'encode') . "' class=\"btn btn-primary\">VIEW</a></td>";
                                    echo "<td>" . $value->formName . "</td>";
                                    echo "<td>" . $value->currentApprover . "</td>";
                                    echo "<td style=\"color:$color\">".$approveState_val."</td>";
                                    echo "<td>" . $value->id . "</td>";
                                echo "</tr>";
                            }
                        ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- /.row --> 
  
</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper --> 
