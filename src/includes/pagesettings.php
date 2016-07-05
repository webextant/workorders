<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Settings
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-wrench"></i> Some items on this page are only temporary and will be removed after alpha stage.
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <form class="form-horizontal" method="post">
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <button id="show_formdefinitions_data" name="show_formdefinitions_data" class="btn btn-primary">Show Form Definitions Data</button><hr/>
                        <button id="create_formdata_table" name="create_formdata_table" class="btn btn-warning">Create Form Data Table</button>
                        <button id="create_formdefinitions_table" name="create_formdefinitions_table" class="btn btn-warning">Create Form Definitions Table</button><hr/>
                        <button id="drop_formdefinitions_table" name="drop_formdefinitions_table" class="btn btn-danger">Drop Form Definitions Table</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST["create_formdefinitions_table"])) {
			echo "<h4>Create FormDefinitions Table Command - Results</h4>";
			require "./resources/library/forms_db_controller.php";
			$fdc = new FormsDataController();
			$fdc->createFormDefinitionsTable();
		}
		if (isset($_POST["create_formdata_table"])) {
			echo "<h4>Create FormData Table Command - Results</h4>";
			require "./resources/library/forms_db_controller.php";
			$fdc = new FormsDataController();
			$fdc->createFormDataTable();
		}
		if (isset($_POST["show_formdefinitions_data"])) {
			echo "<h4>Show FormDefinitions Data - Results</h4>";
			require "./resources/library/forms_db_controller.php";
			$fdc = new FormsDataController();
			$fdc->renderFormDefinitionsData();
		}
		if (isset($_POST["save_form_xml"])) {
			echo "<h4>Save Form XML Data - Results</h4>";
			require "./resources/library/forms_db_controller.php";
			$fdc = new FormsDataController();
			$fdc->addForm($_POST["formname"], $_POST["description"], $_POST["formxml"]);
		}
		if (isset($_POST["drop_formdefinitions_table"])) {
			echo "<h4>Drop Forms table - Results</h4>";
			require "./resources/library/forms_db_controller.php";
			$fdc = new FormsDataController();
			echo "Table was not dropped. Some code must be un-commented first...";
			//$fdc->dropFormsTable();
		}
	} else {
		echo "<h4>Choose a command above to do something</h4>";
	}
	////$fdc->dropFormsTable();
	////$fdc->addForm($base64xml, "Sample Form #3 base64 encoded");
	///$fdc->renderDbInfo();
 ?>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
