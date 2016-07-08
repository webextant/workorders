<?php
/************************************************************************************************
Sub file for QUERY_PROCESS.php
Author: Michael Keough
Date Modified: 12/5/2015

When a query is processed it runs QUERY_PROCESS.php. This file is included in the dashboard.php
above the page porcessor.  The Post Processor is in the header file and displays the notification 
above the container div.  This way the notification can be put within the container and display correctly
************************************************************************************************/
  if(isset($QUERY_PROCESS)){;
	if($QUERY_PROCESS){;
	?>
     <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <strong>SUCCESS:</strong> The <?php echo $element; ?> was <?php echo $element_function; ?>!
    
    
    </div>
	<?php	
	}else{
		?>
			 <div class="alert alert-danger" >
		  <button type="button" class="close" data-dismiss="alert">×</button>
		  <strong>ERROR!!: </strong> There was a problem and <?php echo $element; ?> was not <?php echo $element_function; ?>!  A system admin has been notified. <?php echo $mysql_error; ?>
		  
	   
		</div>
		<?php	

	}
}else if(isset($errorMSG)){
	echo $errorMSG;
}
  ?>