<?php
  class FormsDataController
  {
    public function createFormDefinitionsTable(){
      require "./config/db.php";
      $conn = new PDO($dsn, $user_name, $pass_word);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
        $sql = "CREATE TABLE FormDefinitions (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, FormName VARCHAR( 30 ), Description VARCHAR( 150 ), FormXml TEXT, Workflow TEXT, notifyOnFinalApproval TEXT, Available BOOLEAN DEFAULT 0)";
        $conn->exec($sql);
        echo "Form definitions table created...";
      }
      catch(PDOException $e)
      {
        echo $sql . "<br>" . $e->getMessage();
      }
      $conn = null;
    }
    
    public function createFormDataTable(){
      // DEPRECATED: FormData table will be replaced by Workorder table. Handled in workorder.php in the resources/library folder.
      require "./config/db.php";
      $conn = new PDO($dsn, $user_name, $pass_word);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
        $sql = "CREATE TABLE FormData (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, FormName VARCHAR( 30 ), Description VARCHAR( 150 ), CurrentApproverEmail VARCHAR( 80 ), FormXml TEXT, FormData TEXT, Workflow TEXT)";
        $conn->exec($sql);
        echo "Form data table created...";
      }
      catch(PDOException $e)
      {
        echo $sql . "<br>" . $e->getMessage();
      }
      $conn = null;
    }

    public function dropFormDefinitionsTable()
    {
      require "./config/db.php";
      $conn = new PDO($dsn, $user_name, $pass_word);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
        $sql = "DROP TABLE FormDefinitions";
        $conn->exec($sql);
        echo "Form definitions table DROPPED...";
      }
      catch(PDOException $e)
      {
        echo $sql . "<br>" . $e->getMessage();
      }
      $conn = null;
    }

    public function verifyAddFormFields($name, $desc, $xml, $workflow, $notifyOnFinalApproval)
    {
      $valid = True;
      if ($name == ""){$valid = False;}
      if ($desc == ""){$valid = False;}
      if ($xml !== ""){
        // TODO: Check for valid XML
      }
      return $valid;
    }

    /** Saves a form definition to the database */
    public function addForm($name, $desc, $xml, $workflow, $notifyOnFinalApproval, $available, $groupWorkflows)
    {
      $name = trim($name);
      $desc = trim($desc);
      $xml = trim($xml);
      $valid = $this->verifyAddFormFields($name, $desc, $xml, $workflow, $notifyOnFinalApproval, $groupWorkflows);
      if($valid){
        require "./config/db.php";
        $conn = new PDO($dsn, $user_name, $pass_word);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO FormDefinitions (FormName, Description, FormXml, Workflow, notifyOnFinalApproval, Available, GroupWorkflows) VALUES (:FormName, :Description, :FormXml, :Workflow, :notifyOnFinalApproval, :Available, :GroupWorkflows)";
        $result = $conn->prepare($sql);
        $status = $result->execute(array('FormName' => $name, 'Description' => $desc, 'FormXml' => base64_encode($xml), 'Workflow' => $workflow, 'notifyOnFinalApproval' => $notifyOnFinalApproval, 'Available' => $available, 'GroupWorkflows' => $groupWorkflows));
        $conn = null;
        return true;
      } else {
        return false;
      }
    }
    
    public function addSubmittedFormData($data)
    {
      // DEPRECATED: FormData table will be replaced by Workorder table. Handled in workorder.php in the resources/library folder.
      if($data->InputIsValid()){
        require "./config/db.php";
        $conn = new PDO($dsn, $user_name, $pass_word);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO FormData (FormName, Description, FormXml, FormData) VALUES (:FormName, :Description, :FormXml, :FormData)";
        $result = $conn->prepare($sql);
        $status = $result->execute(array('FormName' => $data->formName, 'Description' => $data->formDescription, 'FormXml' => base64_encode($data->asFormXML()), 'FormData' => $data->asJSON()));
        $conn = null;
        return true;        
      } else {
        return false;
      }      
    }
	
    public function updateForm($id, $name, $desc, $xml, $workflow, $notifyOnFinalApproval, $available, $groupWorkflows)
    {
      $name = trim($name);
      $desc = trim($desc);
      $xml = trim($xml);
      $valid = $this->verifyAddFormFields($name, $desc, $xml, $workflow, $notifyOnFinalApproval, $groupWorkflows);
      if($valid){
        require "./config/db.php";
        $conn = new PDO($dsn, $user_name, $pass_word);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE FormDefinitions SET FormName = :FormName, Description = :Description, FormXml = :FormXml, Workflow = :Workflow, notifyOnFinalApproval = :notifyOnFinalApproval, Available = :Available, GroupWorkflows = :GroupWorkflows WHERE id = :id";
        $result = $conn->prepare($sql);
        $status = $result->execute(array('FormName' => $name, 'Description' => $desc, 'FormXml' => base64_encode($xml), 'Workflow' => $workflow, 'notifyOnFinalApproval' => $notifyOnFinalApproval, 'Available' => $available, 'GroupWorkflows' => $groupWorkflows, 'id' => $id));
        $conn = null;
        return true;        
      } else {
        return false;
      }
    }
    
    public function deleteForm($id)
    {
        require "./config/db.php";
        $conn = new PDO($dsn, $user_name, $pass_word);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM FormDefinitions WHERE id = :id";
        $result = $conn->prepare($sql);
        $status = $result->execute(array('id' => $id));
        $conn = null;
    }
    
    public function getFormById($id){
      // returns saved form data
      require "./config/db.php";
      $conn = new PDO($dsn, $user_name, $pass_word);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $formdata = null;
      try {
        $sql = "SELECT * FROM FormDefinitions WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $formdata = $stmt->fetch();
      } catch (PDOException $e) {
        echo "Exception occured while fetching form data.";
      }
      return $formdata;
    }
    
    public function renderFormsList()
    {
      // renders all saved forms in a boostrap table
      require "./config/db.php";
	  //include_once "./resources/page_encryption.php";

      $conn = new PDO($dsn, $user_name, $pass_word);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
        $sql = "SELECT * FROM FormDefinitions";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $form = $stmt->fetchAll();

        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th></th>";
        echo "<th>Name</th>";
        echo "<th>Description</th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($form as $row) {
          echo "<tr>";
          if (empty($row["FormXml"])){
            echo "<td></td>";
          } else {
            echo "<td>" . "<a href='#' onclick='onPreviewClick(\"" . $row["FormName"] . "\",\"" . $row["Description"] . "\",\"" . $row["FormXml"] . "\")' class='btn btn-primary'>Preview</a></td>";            
          }
          echo "<td>" . $row["FormName"] . "</td>";
          echo "<td>" . $row["Description"] . "</td>";
          $formid= $row["id"];
          $buildformid = "buildform" . $formid;
          echo "<td><form id='$buildformid' method='post' action='formsbuild.php'><input name='id' type='hidden' value='$formid'><a href='' onclick=\"document.getElementById('$buildformid').submit();return false;\" class='btn btn-success'>Edit</a></form></td>";
          $delformid = "delform" . $formid;            

		  echo "<td><form id='$delformid' method='post'><input name='id' type='hidden' value='$formid'>                
<input name='deleteform' type='hidden'><a href='' title='Delete Form' onclick=\"document.getElementById('$delformid').submit();return false;\" class='btn btn-danger'>X</a></form></td>";
          echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
      }
      catch(PDOException $e)
      {
        echo $sql . "<br>" . $e->getMessage();
      }
      $conn = null;
    }
    public function renderFormDefinitionsData()
    {
      require "./config/db.php";
      $conn = new PDO($dsn, $user_name, $pass_word);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
        $sql = "SELECT * FROM FormDefinitions";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $form = $stmt->fetchAll();

        foreach ($form as $row) {
          $validxml = True;
          $xml = null;
          $xmlstring = base64_decode($row["FormXml"]);
          try {
            $xml = new SimpleXMLElement($xmlstring);            
          } catch (Exception $e) {
            $validxml = False;
          }
          echo "<hr/>";
          echo "ID: " . $row["id"] . "<br>";
          echo "FormName: " . $row["FormName"] . "<br>";
          echo "Description: " . $row["Description"] . "<br>";
          echo "FormXml: " . $row["FormXml"] . "<br>";
          echo "Workflow: " . $row["Workflow"] . "<br>";
          echo "<h5>Base64 Decoded Form XML Data (" . $row["FormName"] . ")</h5>";
          echo "<pre>" . htmlspecialchars(base64_decode($row["FormXml"])) . "</pre>";
          
          if ($validxml){
            echo "<h5>XML Form Fields (" . $row["FormName"] . ")</h5>";
            // Show XML form fields in a table
            echo "<table border='1' style='width:100%;'>";
            echo "<tr><th>Field Name</th><th>Field Label</th><th>Description</th></tr>";
            foreach ($xml->children()->children() as $item) {
              echo "<tr>";
              printf("<td>%s</td><td>%s</td><td>%s</td>", $item['name'], $item['label'], $item['description']);
              echo "</tr>";
            }
            echo "</table>";            
          } else {
            echo "<p>NOTE: The forms XML data is not valid XML.</p>";
          }
          echo "<hr/>";
        }
      }
      catch(PDOException $e)
      {
        echo $sql . "<br>" . $e->getMessage();
      }
      $conn = null;
    }

    public function renderDbInfo(){
      /**
      * PDO MySQL initial code
      *
      * User permissions of database
      * Create, Alter and Index table, Create view, and Select, Insert, Update, Delete table data
      *
      * @package			PhpFiddle
      * @link			http://phpfiddle.org
      * @since			2012
      */
      require_once "dBug!.php";
      require "./config/db.php";
      $connect = new PDO($dsn, $user_name, $pass_word);
      $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "SELECT * FROM FormDefinitions";
      $result = $connect->prepare($sql);
      $status = $result->execute();
      if (($status) && ($result->rowCount() > 0))
      {
        $results = array();
        //convert query result into an associative array
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
          $results[] = $row;
        }
        //dump all data from associative array converted from query result
        new dBug($results);
      }
      $connect = null;
    }
  }
?>
