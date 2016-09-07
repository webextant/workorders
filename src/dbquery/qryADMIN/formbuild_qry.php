

<?php
$element = "Form";
$element_function = "Updated";

          require_once "./resources/library/forms_db_controller.php";
          // If post check post data and save/update form to db.
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAvailable = 0;
            if (isset($_POST["formAvailable"])) { $formAvailable = 1; }
            if (isset($_POST["updateform"])){
              $fdc = new FormsDataController($dsn, $user_name, $pass_word);
              $QUERY_PROCESS = $fdc->updateForm($_POST["id"], $_POST["formname"], $_POST["formdesc"], $_POST["xmldata"], $_POST["workflow"], $_POST["notifyOnFinalApproval"], $formAvailable, $_POST["groupWorkflows"]);
            } elseif (isset($_POST["deleteform"])) {
              $fdc = new FormsDataController($dsn, $user_name, $pass_word);
              $QUERY_PROCESS = $fdc->deleteForm($_POST["id"]);
            } elseif (isset($_POST["addform"])) {
              $fdc = new FormsDataController($dsn, $user_name, $pass_word);
              $QUERY_PROCESS = $fdc->addForm($_POST["formname"], $_POST["formdesc"], $_POST["xmldata"], $_POST["workflow"], $_POST["notifyOnFinalApproval"], $formAvailable, $_POST["groupWorkflows"]);
              if (!$QUERY_PROCESS){
                echo "<div class='alert alert-danger'>All fields are required. Keep calm and try again...</div>";
              }
            }
          }
          
        ?>