  <?php
/************************************************************************************************
HEADER FILE FOR current_collab.php
Author: Raymond Brady
Date Modified: 9/6/2016

If a page needs special headers index.php needs to load header_XXXXX.php  XXXXX=file.php you need
to have custom headers for.
************************************************************************************************/

  ?>
  	<link rel="stylesheet" type="text/css" href="./css/dataTables/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="./css/dataTables/demo.css">


	<script type="text/javascript" language="javascript" src="js/dataTables/jquery.dataTables.js">
	</script>
	
	<script type="text/javascript" language="javascript" class="init">
	

$(document).ready(function() {
	$('#matrixDT').DataTable();
} );

	</script>  
