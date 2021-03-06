<?PHP
    /**
    * Classes for working with Workorders
    * @author raymond.brady@webextant.com
    * @license MIT
    *
    * Updates:
    *   RB 7/12/2016 - Now supports updating form data for a previously saved workorder.
    */
    
    /**
     * A workorder descibes work to be performed, tracks approval state, defines workflow
     */
    class Workorder
    {
        public $id; // DB Index PK
        public $formId; // reference to form db record
        public $formName;
        public $description;
        public $formXml;
        public $formData;
        public $currentApprover;
        public $workflow;
        public $approveState;
        public $approverKey;
        public $viewOnlyKey;
        public $createdAt;
        public $updatedAt;
        public $createdBy;
        public $updatedBy;
        public $notifyOnFinalApproval;
        public $comments;
        public $collaborators;
    }
    
    /**  */
    class WorkorderComment
    {
        public $commentData;
        public $createdAt;
        public $createdBy;
    }

    /** Used when working with collaborators on a workorder */
    class WorkorderCollaborator
    {
        public $user_id;
        public $user_fname;
        public $user_lname;
        public $user_email;
    }
    
    /** Defines workorder data in context of a view/page */
    class WorkorderViewModel
    {
        // array key is form field label value. array value is form field value. 
        public $fieldData = array();
        public $fieldCount = 0;
        public $workorderIdText;
        public $valid = true;
        public $approverKeyValid = false;
        public $viewOnlyKeyValid = false;
        public $comments;
        public $approveState;
        public $approveStateValue;
        public $stateColorCode;
        public $stateColorClass;
        public $isFinalApproval;
        public $finalApproverEmail;
        public $formData;
        public $hasCollaborator;
        public $currentCollaborators;
        public $collaboratorStateColorCode;
        public $collaboratorStateClass;
        public $isClosed;
        public $userIsCurrentApprover;
        public $userIsCollaborator;

        private $formXmlData;
        
        function __construct($workorder, $key, $user_email = "")
        {
            try {
                $this->formXmlData = new SimpleXMLElement($workorder->formXml);
                $this->formData = json_decode($workorder->formData);
                $this->DecodeCommentData($workorder->comments);
                $this->VerifyKey($workorder, $key);
                $this->CompileViewData();
                $this->workorderIdText = "Workorder #" . $workorder->id;
                $this->SetApproveState($workorder->approveState);
                $this->SetCollaboratorState($workorder->collaborators, $user_email);
                $this->SetFinalApproval($workorder->workflow);
                $this->VerifyCurrentUser($workorder, $user_email);
            } catch (Exception $e) {
                $this->valid = false;
                $this->workorderIdText = "Workorder not found or invalid data.";
            }
        }

        private function VerifyCurrentUser($wo, $user_email)
        {
            if($user_email == ""){
                // no user provided. Allow valid if key is current apporver key. Used when accessing without logging in.
                if($this->approverKeyValid):
                    $this->userIsCurrentApprover = true;
                else:
                    $this->userIsCurrentApprover = $wo->currentApprover == $user_email;
                endif;
            } else {
                $this->userIsCurrentApprover = $wo->currentApprover == $user_email;
            }
        }

        private function SetFinalApproval($workflow)
        {
            $wf = json_decode($workflow);
            if (is_array($wf->approvers)) {
                $this->isFinalApproval = end($wf->approvers)->current;
                $this->finalApproverEmail = end($wf->approvers)->email;
            } else {
                $this->isFinalApproval = "";
            }
        }

        private function SetCollaboratorState($state, $user_email)
        {
            if ($state == null)
            {
                $this->currentCollaborators = null;
                $this->userIsCollaborator = false;
                $this->hasCollaborator = false;
                $this->collaboratorStateColorCode = "#dff0d8";
                $this->collaboratorStateClass = "alert alert-success";
            } else {
                $this->hasCollaborator = true;
                $this->currentCollaborators = json_decode($state, true);
                if ($user_email == ""){
                    $this->userIsCollaborator = false;
                } else {
                    $uIsInCollabs = false; // default
                    foreach ($this->currentCollaborators as $key => $value) {
                        if ($value['user_email'] == $user_email):
                            $uIsInCollabs = true;
                        endif;
                    }
                    $this->userIsCollaborator = $uIsInCollabs;
                }
                $this->collaboratorStateColorCode = "#fcf8e3";
                $this->collaboratorStateClass = "alert alert-warning";
            }
        }

        private function SetApproveState($state)
        {
            $this->approveState = "";
            $this->approveStateValue = "";
            $this->stateColorCode = "#fff";
            $this->stateColorClass = "";
            switch ($state) {
                case "PendingApproval":
                    $this->approveState = "Pending Approval";
                    $this->stateColorCode = "#fcf8e3";
                    $this->stateColorClass = "alert alert-warning";
                    $this->approveStateValue = $state;
                    $this->isClosed = false;
                    break;
                case "ApproveInProgress":
                    $this->approveState = "Item In Progress";
                    $this->stateColorCode = "#d9edf7";
                    $this->stateColorClass = "alert alert-info";
                    $this->approveStateValue = $state;
                    $this->isClosed = false;
                    break;
                case "ApproveClosed":
                    $this->approveState = "Closed (Approved)";
                    $this->stateColorCode = "#dff0d8";
                    $this->stateColorClass = "alert alert-success";
                    $this->approveStateValue = $state;
                    $this->isClosed = true;
                    break;
                case "RejectClosed":
                    $this->approveState = "Closed (Rejected)";
                    $this->stateColorCode = "#f2dede";
                    $this->stateColorClass = "alert alert-danger";
                    $this->approveStateValue = $state;
                    $this->isClosed = true;
                    break;
            }
            
        }
        
        private function DecodeCommentData($comments)
        {
            if ($comments == null) {
                $this->comments = array();                
            } else {
                $this->comments = json_decode($comments, true);
            }
               
        }
             
        private function VerifyKey($workorder, $key)
        {
            if ($workorder->approverKey == $key)
            {
                $this->approverKeyValid = true;
                $this->viewOnlyKeyValid = false;
            }
            if ($workorder->viewOnlyKey == $key)
            {
                $this->viewOnlyKeyValid = true;
                $this->approverKeyValid = false;
            }
            if ($this->approverKeyValid == false && $this->viewOnlyKeyValid == false)
            {
                $this->valid = false;
            }
        }
        
        private function CompileViewData()
        {
            foreach ($this->formData as $key => $value) {
                $this->fieldCount ++;
                $label = $this->GetFormXmlFieldLabelValue($key);
                if ($label != null)
                {
                    $data["Label"] = (string)$label;
                    $data["Data"] = $value; 
                    $this->fieldData[(string)$label . $this->fieldCount] = $data;
                }
            }
        }
        
        public function GetFormXmlFieldLabelValue($fieldName)
        {
            $result = null;
            foreach ($this->formXmlData->fields[0]->field as $field) {
                if ($field['name'] == $fieldName)
                {
                   $result = $field['label'];
                }
            }
            return $result;
        }

        public function GetFormXmlFieldInfo($fieldName, $fieldValue)
        {
            $result = null;
            foreach ($this->formXmlData->fields[0]->field as $field) {
                if ($field['name'] == $fieldName)
                {
                    $type = $field['type'];
                    $result['name'] = $field['name'];
                    $result['label'] = $field['label'];
                    $result['type'] = $type;
                    $result['required'] = $field['required'];
                    // html
                    $result['form_html'] = '<div>Unsupported element: '.$type.'</div>'; // Default. Unknown types

                    if ($type == 'text' || $type == 'date' || $type == 'email' || $type == 'datetime')
                    {
                        $result['form_html'] = '<input id="'.$field['name'].'" name="'.$field['name'].'" type="'.$type.'" class="form-control" value="'.$fieldValue.'" placeholder="'.$field['label'].'">';
                    }
                    if ($type == 'textarea')
                    {
                        $result['form_html'] = '<textarea id="'.$field['name'].'" name="'.$field['name'].'" class="form-control" rows="3">'.$fieldValue.'</textarea>';
                    }
                    if ($type == 'FUTURE-checkbox-group')
                    {
                        $result['form_html'] = null; // clear default value first
                        foreach ($field->option as $key => $value) {
                            $optionValues[$key] = $value;
                            if ($fieldValue == $value)
                            {
                                $selectedValue = $value;
                                $checkedValue = 'checked';
                            } else {
                                $selectedValue = '';
                                $checkedValue = '';
                            }
                            $result['form_html'] .= '<div class="checkbox"><label>';
                            $result['form_html'] .= '<input id="'.$field['name'].'" name="'.$field['name'].'" type="checkbox" value="" '.$checkedValue.'>'.$value;
                            $result['form_html'] .= '</label></div>';
                        }
                        $result['option-values'] = $optionValues;
                    }
                    if ($type == 'select')
                    {
                        $result['form_html'] = '<select id="'.$field['name'].'" name="'.$field['name'].'" class="form-control">';
                        foreach ($field->option as $key => $value) {
                            $optionValues[$key] = $value;
                            if ($fieldValue == $value)
                            {
                                $selectedValue = $value;
                                $checkedValue = 'selected';
                            } else {
                                $selectedValue = '';
                                $checkedValue = '';
                            }
                            $result['form_html'] .= '<option value="'.$value.'" '.$checkedValue.'>'.$value.'</option>';
                        }
                        $result['form_html'] .= '</select>';
                        $result['option-values'] = $optionValues;
                    }

                }
            }
            return $result;
        }
    }
    
    /**
    * A workorder helper utility
    */
    class WorkorderHelper
    {
        // TODO: Impliment Helper Class
    }
    
    /**
     * Class for sending email related to Workorder objects.
     */
    class WorkorderEmailAdapter
    {
        function __construct($fromaddress)
        {
           $this->fromaddress = $fromaddress;
        }
        
        private function SendEmail($to, $from, $subject, $body)
        {
            // Send as HTML
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            // Additional headers
            //$headers .= 'To: ' . $to . "\r\n";
            $headers .= 'From: ' . $from . "\r\n";
            //$headers .= 'Cc: cc@example.com' . "\r\n";
            //$headers .= 'Bcc: bcc@example.com' . "\r\n";

            // Mail it
            mail($to, $subject, $body, $headers);
        }
        
        private function RenderWorkorderDetailsStringAsHtml($wo, $woViewModel = null)
        {
            
            $created = date_create($wo->createdAt);
            $woBody = "<hr/>";            
            $woBody .= "<h2>" . $wo->formName . " " . $wo->id . "</h2>";
            $woBody .= "<p><b>Created:</b> " . date_format($created, 'm/d/Y g:i A') . "</p>";
            $woBody .= "<p><b>Requested By:</b> " . $wo->createdBy . "</p>";
            if ($wo->approveState == "PendingApproval") {
                $woBody .= "<p><b>Current Approver:</b> " . $wo->currentApprover . "</p>";            
            }

            // Use the viewmodel if available
            if ($woViewModel != null){
                if ($woViewModel->valid) {
                    $woBody .= "<div class='jumbotron well'>";
                    $woBody .= "<div class='" . $woViewModel->stateColorClass . "'>" . $woViewModel->approveState . " (" . $wo->currentApprover . ")" . "</div>";
                    $woBody .= "</div>";
                    foreach ($woViewModel->fieldData as $fieldkey => $value) {
                        $woBody .= "<h4>" . $value["Label"] . "</h4>";
                        $woBody .= "<P>" . $value["Data"] . "</p>";
                    }
                    $woBody .= "<h3>Comments</h3>";
                    if (count($woViewModel->comments) == 0) {
                        $woBody .= "<span>No comments posted.</span>";
                    } else {
                        $woBody .= "<ul style='list-style-type:none'>";
                        foreach ($woViewModel->comments as $commentkey => $value) {
                            $woBody .= "<li class='message-preview'>";
                            $woBody .= "<div class='media'>";
                            $woBody .= "<div class='media-body'>";
                            $woBody .= "<h5 class='media-heading'><b>" . $value['createdBy'] . "</b></h4>";
                            $woBody .= "<p class='small text-muted'><i class='fa fa-clock-o'></i> " . $value['createdAt'] . "</p>";
                            $woBody .= "<p>" . $value['commentData'] . "</p>";
                            $woBody .= "</div>";
                            $woBody .= "</div>";
                            $woBody .= "</li>";
                        }                                
                        $woBody .= "</ul>";
                    }
                } else {
                    $woBody .= '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Unable to view the workorder details. You may not be authorized...</div>';
                }
            }

            return $woBody;
        }
        
        // Sends detail view of workorder to creator, current approver, and current collaborator
        public function SendUpdatedDetailsToAll($workorder, $woViewModel = null){
            $toAddresses = [];
            $collabs = json_decode($workorder->collaborators, true);
            foreach ($collabs as $key => $value) {
                if ($value['user_email'] != null){
                    $toAddresses[] = $value['user_email'];
                }
            }
            $toAddresses[] = $workorder->createdBy;
            $toAddresses[] = $workorder->currentApprover;
            $from = $this->fromaddress;
            $to = $toAddresses;
            $subject = $workorder->formName . " " . $workorder->id . " updated.";
            $body = "<p>Greetings!</p><p>This item has been updated.</p>";
            $body .= "<p>I'm just keeping you in the loop. You will receive another email when something changes.</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);
        }

        // Sends detail view of workorder to creator, current approver, and current collaborator
        public function SendUpdatedDetailsToCreator($workorder, $woViewModel = null){
            $from = $this->fromaddress;
            $to = $workorder->createdBy;
            $subject = $workorder->formName . " " . $workorder->id . " updated.";
            $body = "<p>Greetings!</p><p>This item has been updated.</p>";
            $body .= "<p>I'm just keeping you in the loop. You will receive another email when something changes.</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);
        }

        // Sends detail view of workorder to collaborators and approver
        public function SendAddCollab($workorder, $woViewModel = null){
            $toAddresses = [];
            $collabList = [];
            $collabs = json_decode($workorder->collaborators, true);
            foreach ($collabs as $key => $value) {
                if ($value['user_email'] != null){
                    $toAddresses[] = $value['user_email'];
                    $collabList[] = $value['user_email'];
                }
            }
            $toAddresses[] = $workorder->currentApprover;
            $from = $this->fromaddress;
            $to = implode(",", $toAddresses);
            $subject = "Collaboration request for " . $workorder->formName . " " . $workorder->id;
            $body = "<p>Greetings!</p><p>The following people have been invited to collaborate on this item.</p>";
            $body .= "<p>" . implode(",", $collabList) . "</p>";
            $body .= "<p>I'm just keeping you in the loop. You will receive another email when something changes.</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);
        }

        // Sends detail view of workorder to collaborators and approver
        public function SendEndCollab($workorder, $woViewModel = null){
            $toAddresses = [];
            $collabList = [];
            if ($workorder->collaborators != null){
                $collabs = json_decode($workorder->collaborators, true);
                foreach ($collabs as $key => $value) {
                    if ($value['user_email'] != null){
                        $toAddresses[] = $value['user_email'];
                        $collabList[] = $value['user_email'];
                    }
                }
            }
            $toAddresses[] = $workorder->currentApprover;
            $from = $this->fromaddress;
            $to = implode(",", $toAddresses);
            $subject = "Collaboration ended for " . $workorder->formName . " " . $workorder->id;
            $body = "<p>Greetings!</p><p>Thanks for the assist! Collaboration has ended for the following people.</p>";
            $body .= "<p>" . implode(",", $collabList) . "</p>";
            $body .= "<p>I'm just keeping you in the loop. If further assistance is needed you will receive another email.</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);
        }

        public function SendNeedsApprovalToCurrentApprover($workorder, $woViewModel = null)
        {
            require_once "email.php"; // LinkHelper
            $approverLink = LinkHelper::getApproverLink($workorder);
            $from = $this->fromaddress;
            $to = $workorder->currentApprover;
            $subject = $workorder->formName . " " . $workorder->id . " needs your approval.";
            $body = "<p>Greetings " . $to . ",</p><p>You are the current approver for " . $workorder->formName . " " . $workorder->id . ".</p>";
            $body .= "<p>Since you are the current approver you will be able to approve or reject the item. ";
            $body .= "Approval will send the item to the next listed approver. ";
            $body .= "If you are the final approver, the submitter will be notified of pending completion status. ";
            $body .= "Rejecting the item will notify the submitter and all previous approvers.</p>";
            $body .= "<p>Click the link below to view " . $workorder->formName . " " . $workorder->id . "</p>";
            $body .= "<p>" . $approverLink . "</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);
        }
        
        public function SendViewOnlyCreatedToCreator($workorder, $woViewModel = null)
        {
            require_once "email.php"; // LinkHelper
            $viewonlyLink = LinkHelper::getViewOnlyLink($workorder);
            $from = $this->fromaddress;
            $to = $workorder->createdBy;
            $subject = $workorder->formName . " " . $workorder->id . " created.";
            $body = "<p>Greetings " . $to . ",</p><p>Your request has been submitted.</p>";
            $body .= "<p>You created this item and will be able to view status and changes anytime. ";
            $body .= "You will receive another email when something changes. ";
            $body .= "Click the link below to view " . $workorder->formName . " " . $workorder->id . "</p>";
            $body .= "<p>" . $viewonlyLink . "</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);
        }

        public function SendViewOnlyRejectedToCreator($workorder, $woViewModel = null)
        {
            require_once "email.php"; // LinkHelper
            $viewonlyLink = LinkHelper::getViewOnlyLink($workorder);
            $from = $this->fromaddress;
            $to = $workorder->createdBy;
            $subject = $workorder->formName . " " . $workorder->id . " has been rejected.";
            $body = "<p>Greetings " . $to . ",</p><p>" . $workorder->formName . " " . $workorder->id . " has been rejected.</p>";
            $body .= "<p>Click the link below to view " . $workorder->formName . " " . $workorder->id . "</p>";
            $body .= "<p>" . $viewonlyLink . "</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);
        }
        
        public function SendViewOnlyFinalApprovalNotifications($workorder, $woViewModel = null)
        {
            require_once "email.php"; // LinkHelper
            $viewonlyLink = LinkHelper::getViewOnlyLink($workorder);
            $from = $this->fromaddress;
            $to = $workorder->createdBy;
            if ($workorder->notifyOnFinalApproval != null) {
                $to = $workorder->createdBy . ", " . $workorder->notifyOnFinalApproval;
            }
            $subject = $workorder->formName . " " . $workorder->id . " Approved and completed";
            $body = "<p>Greetings " . $to . ",</p>";
            $body = "<p>" . $workorder->formName . " " . $workorder->id . " has been completed.</p>";
            $body .= "<p>Click the link below to view " . $workorder->formName . " " . $workorder->id . "</p>";
            $body .= "<p>" . $viewonlyLink . "</p>";
            $body .= "Regards,<br/>" . $from;
            $body .= "<p>* This is an automated email. Please do not reply. Our robots are not trained to respond yet!</p>";
            $body .= $this->RenderWorkorderDetailsStringAsHtml($workorder, $woViewModel);
            $this->SendEmail($to, $from, $subject, $body);            
        }

    }
    
    
    /**
     * Class for reading and persisting Workorder objects in the DB.
     */
    class WorkorderDataAdapter
    {
        private $conn = null;
        public $currentUserEmail = null;
        
        function __construct($dsn, $user_name, $pass_word, $currentUserEmail = "")
        {
            // setup the db connection for this adapter
            $this->conn = new PDO($dsn, $user_name, $pass_word);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($currentUserEmail != "") {
                $this->currentUserEmail = $currentUserEmail;                
            }
        }
        function __destruct()
        {
            // tear down the db connection, no longer needed
            $this->conn = null;
        }

        // public properties
        public $lastInsertId;
        
        /**
        * Create a the Approvers table on the DB
        * @return {$status} bool - TRUE on success or FALSE on exception
        */
        public function CreateTableIfNotExist()
        {
            $result = TRUE;
            try {
                $sql = "CREATE TABLE IF NOT EXISTS Workorders (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, formId INT(6) UNSIGNED, formName VARCHAR( 60 ), description VARCHAR( 150 ), formXml TEXT, formData TEXT, currentApprover VARCHAR( 320 ), workflow TEXT, approveState VARCHAR( 25 ), approverKey VARCHAR( 32 ),viewOnlyKey VARCHAR( 32 ), createdAt DATETIME, updatedAt DATETIME, createdBy VARCHAR( 320 ), updatedBy VARCHAR( 320 ), notifyOnFinalApproval TEXT, comments TEXT )";
                $this->conn->exec($sql);
            }
            catch(PDOException $e)
            {
                $result = FALSE;
            }
            return $result;
        }

        function Select($id)
        {
            $sql = "SELECT * FROM Workorders WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Workorder");
            $stmt->execute(array(':id' => $id));
            $workorder = $stmt->fetch(PDO::FETCH_CLASS);
            return $workorder;
        }
		
		/***********************
		SelectByPeram
		Allows custom perameters based on
		(Userid, currentApprover etc ... json format)
		"FIELD|OPERATOR":VALUE, NEXT...
		createdBy|=me@domain.com
		***********************/
		function SelectWhereJSON($whereArray, $limit = 50)
        {
            $requirements = '';
			foreach($whereArray as $var => $value){
				$var_exp = explode("|",$var);
				//echo $var_exp[0]." ". $var_exp[1]." ".$value."<br />";	
				$requirements .= "AND ".$var_exp[0].$var_exp[1]."'".$value."' ";
			}
			
			if($requirements !== ''){
				$sql = "SELECT * FROM Workorders WHERE ".$requirements." ORDER BY createdAt DESC";
					$sql = str_replace('WHERE AND','WHERE',$sql);
				//echo $sql."<br />";
				$stmt = $this->conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_CLASS, "Workorder");
				$stmt->execute();
				$workorders = $stmt->fetchAll(PDO::FETCH_CLASS);
				return $workorders;
			}
			
        }
		/***********************/
        function SelectAll($limit = 100)
        {
            $sql = "SELECT * FROM Workorders ORDER BY createdAt DESC LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Workorder");
            $stmt->execute(array(':limit' => $limit));
            $workorders = $stmt->fetchAll(PDO::FETCH_CLASS);
            return $workorders;
        }
		/***********************/
        function SelectAllWhereCollaborator($limit = 100)
        {
            if ($this->currentUserEmail == null) {
                throw new Exception("Operation requires user.", 1);
            }
            $sql = "SELECT * FROM Workorders WHERE collaborators like :collabEmail ORDER BY createdAt DESC LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Workorder");
            $stmt->execute(array(':collabEmail' => '%'.$this->currentUserEmail.'%', ':limit' => $limit));
            $workorders = $stmt->fetchAll(PDO::FETCH_CLASS);
            return $workorders;
        }
        function Insert($workorder)
        {
            if ($this->currentUserEmail == null) {
                throw new Exception("Operation requires user.", 1);
            }
            $sql = "INSERT INTO Workorders (formName, description, formXml, formData, currentApprover, workflow, approveState, approverKey, viewOnlyKey, createdAt, updatedAt, createdBy, updatedBy, formId, notifyOnFinalApproval) VALUES (:Formname, :Description, :Formxml, :Formdata, :Currentapprover, :Workflow, :Approvestate, :Approverkey, :Viewonlykey, now(), now(), :createdBy, :updatedBy, :formId, :notifyOnFinalApproval )";
            $result = $this->conn->prepare($sql);
            $status = $result->execute(array('Formname' => $workorder->formName, 'Description' => $workorder->description, 'Formxml' => $workorder->formXml, 'Formdata' => $workorder->formData, 'Currentapprover' => $workorder->currentApprover, 'Workflow' => $workorder->workflow, 'Approvestate' => $workorder->approveState, 'Approverkey' => $workorder->approverKey, 'Viewonlykey' => $workorder->viewOnlyKey, 'createdBy' => $this->currentUserEmail, 'updatedBy' => $this->currentUserEmail, 'formId' => $workorder->formId, 'notifyOnFinalApproval' => $workorder->notifyOnFinalApproval ));
            $this->lastInsertId = $this->conn->lastInsertId();
            return $status; 
        }
        function Update($workorderId, $workorder)
        {
            if ($this->currentUserEmail == null) {
                throw new Exception("Operation requires user.", 1);
            }
            $sql = "UPDATE Workorders SET formName = :formName, description = :description, formXml = :formXml, formData = :formData, currentApprover = :currentApprover, workflow = :workflow, approveState = :approveState, approverKey = :approverKey, viewOnlyKey = :viewOnlyKey, updatedAt = now(), updatedBy = :updatedBy, formId = :formId, notifyOnFinalApproval = :notifyOnFinalApproval, comments = :comments, collaborators = :collaborators WHERE id = :id";
            $result = $this->conn->prepare($sql);
            $status = $result->execute(array('formName' => $workorder->formName, 'description' => $workorder->description, 'formXml' => $workorder->formXml, 'formData' => $workorder->formData, 'currentApprover' => $workorder->currentApprover, 'workflow' => $workorder->workflow, 'approveState' => $workorder->approveState, 'approverKey' => $workorder->approverKey, 'viewOnlyKey' => $workorder->viewOnlyKey, 'updatedBy' => $this->currentUserEmail, 'formId' => $workorder->formId, 'notifyOnFinalApproval' => $workorder->notifyOnFinalApproval, 'comments' => $workorder->comments, 'collaborators' => $workorder->collaborators, 'id' => $workorderId ));
            return $status;
        }
        function UpdateFormData($workorderId, $formData)
        {
            if ($this->currentUserEmail == null) {
                throw new Exception("Operation requires user.", 1);
            }
            $sql = "UPDATE Workorders SET formData = :formData, updatedAt = now(), updatedBy = :updatedBy WHERE id = :id";
            $result = $this->conn->prepare($sql);
            $status = $result->execute(array(':formData' => $formData, ':updatedBy' => $this->currentUserEmail, ':id' => $workorderId ));
            return $status;
        }
        function AddComment($workorderId, $commentText)
        {
            // Loads the workorder from db and updates the comment data.
            if ($this->currentUserEmail == null || $workorderId == null || $commentText == null){
                throw new Exception("Operation requires user.");
            }
            $wo = $this->Select($workorderId);
            $woViewModel = new WorkorderViewModel($wo, "", $this->currentUserEmail);
            if (!$woViewModel->userIsCollaborator && !$woViewModel->userIsCurrentApprover){
                throw new Exception("User cannot add comments at this time.");
            }
            // Update the comments
            $comments = json_decode($wo->comments, true);
            if ($comments == null) {
                $comments = array();
            }
            $woComment = new WorkorderComment();
            $woComment->commentData = $commentText;
            $woComment->createdAt = date('Y-m-d H:i:s');
            $woComment->createdBy = $this->currentUserEmail;
            array_push($comments, $woComment);
            $wo->comments = json_encode($comments);
            
            return $this->Update($workorderId, $wo);

        }
        function AddCollaborator($workorderId, $commentText, $collabId)
        {
            if ($this->currentUserEmail == null){
                throw new Exception("Operation requires user.");
            }
            if ($workorderId == null){
                throw new Exception("Operation requires workorder id.");
            }
            if ($commentText == null){
                throw new Exception("Operation requires comment text.");
            }
            // Loads the workorder from db.
            $wo = $this->Select($workorderId);

            // Load collaborator data and add collab
            $sql = "SELECT user_id, user_fname, user_lname, user_email FROM users WHERE user_id = :id AND collaborator > 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "WorkorderCollaborator");
            $stmt->execute(array(':id' => $collabId));
            $woCollab = $stmt->fetch(PDO::FETCH_CLASS);
            if($woCollab == null){
                throw new Exception("Collaborator not found.");
            }
            $collabs = json_decode($wo->collaborators, true);
            if ($collabs == null){
                $collabs = array();
            }
            $collabExist = array_search($collabId, array_column($collabs, 'user_id'));
            if ($collabExist == false){
                array_push($collabs, $woCollab);
                $wo->collaborators = json_encode($collabs);
            } else {
                throw new exception("user already assigned as a collaborator.");
            }
            // Update the comments
            $comments = json_decode($wo->comments, true);
            if ($comments == null) {
                $comments = array();
            }
            $woComment = new WorkorderComment();
            $woComment->commentData = $commentText;
            $woComment->createdAt = date('Y-m-d H:i:s');
            $woComment->createdBy = $this->currentUserEmail;
            array_push($comments, $woComment);
            $wo->comments = json_encode($comments);
            
            return $this->Update($workorderId, $wo);

        }
        function EndCollaboration($workorderId, $commentText)
        {
            if ($this->currentUserEmail == null){
                throw new Exception("Operation requires user");
            }
            if ($workorderId == null){
                throw new Exception("Operation requires workorder Id");
            }
            if ($commentText == null){
                throw new Exception("Operation requires comment text.");
            }
            // Loads the workorder from db.
            $wo = $this->Select($workorderId);
            // Set collaborators field to null
            $wo->collaborators = null;
            // Update the comments
            $comments = json_decode($wo->comments, true);
            if ($comments == null) {
                $comments = array();
            }
            $woComment = new WorkorderComment();
            $woComment->commentData = $commentText;
            $woComment->createdAt = date('Y-m-d H:i:s');
            $woComment->createdBy = $this->currentUserEmail;
            array_push($comments, $woComment);
            $wo->comments = json_encode($comments);
            
            return $this->Update($workorderId, $wo);

        }
        function Delete($workorderId)
        {
            $sql = "DELETE FROM Workorders WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute(array(':id' => $workorderId));
            return $result;
        }
    }
?>