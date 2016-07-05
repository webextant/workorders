<?php
    require('../resources/library/workorder.php');
    require('../resources/library/workflow.php');
    require('../resources/library/approver.php');
    
    class WorkorderTest extends PHPUnit_Framework_TestCase
    {
        public function testCreateTableIfNotExtists()
        {
            $dsn = 'mysql:host=localhost;dbname=disd';
            $user_name = 'dev_user';
            $pass_word = 'devpass';
            $currentUserEmail = 'testuser@example.com';
            
            try {
                $dbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
                $result = $dbAdapter->CreateTableIfNotExist();
                $this->assertTrue($result);
            }
            catch(Exception $e)
            {
                echo $e;
            }
        }
        
        public function testInsertWorkorder()
        {
            $dsn = 'mysql:host=localhost;dbname=disd';
            $user_name = 'dev_user';
            $pass_word = 'devpass';
            $currentUserEmail = 'testuser@example.com';

            $testWorkorder = new Workorder();

            $firstApprover = new Approver('Raymond Brady', 'test.approver@webextant.com', false);
            $secondApprover = new Approver('Jane', 'jane@example.com', false);
            $thirdApprover = new Approver('John', 'john@example.com', false);
            $fourthApprover = new Approver('Mark', 'mark@example.com', false);
            $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);
            $workFlow = new Workflow('Maintenance Workorder Workflow', $approvers, true);

            $testWorkorder->formName = "Test Workorder";
            $testWorkorder->description = "Workorder for unit test";
            $testWorkorder->formId = 123;
            $testWorkorder->formXml = "";
            $testWorkorder->formData = "";
            $testWorkorder->currentApprover = ApproverHelper::getCurrent($approvers)->email;
            $testWorkorder->workflow = $workFlow->asJSON();
            $testWorkorder->approveState = ApproveState::PendingApproval;
            $testWorkorder->approverKey = generateApproverKey();
            $testWorkorder->viewOnlyKey = generateApproverKey();

            try {
                $dbAdapter = new WorkOrderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
                $result = $dbAdapter->Insert($testWorkorder);
                $this->assertTrue($result);
                // Cleanup the DB
                $dbAdapter->Delete($dbAdapter->lastInsertId);
            }
            catch(Exception $e) {
                echo $e;
            }
        }
        
        public function testSelectWorkorder()
        {
            $dsn = 'mysql:host=localhost;dbname=disd';
            $user_name = 'dev_user';
            $pass_word = 'devpass';
            $currentUserEmail = 'testuser@example.com';

            $testWorkorder = new Workorder();

            $approverKey = generateApproverKey();
            $viewOnlyKey = generateApproverKey();

            $firstApprover = new Approver('Raymond Brady', 'test.approver@webextant.com', false);
            $secondApprover = new Approver('Jane', 'jane@example.com', false);
            $thirdApprover = new Approver('John', 'john@example.com', false);
            $fourthApprover = new Approver('Mark', 'mark@example.com', false);
            $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);
            $workFlow = new Workflow('Maintenance Workorder Workflow', $approvers, true);

            $testWorkorder->formName = "Test Workorder";
            $testWorkorder->description = "Workorder for unit test";
            $testWorkorder->formId = 123;
            $testWorkorder->formXml = "";
            $testWorkorder->formData = "";
            $testWorkorder->currentApprover = ApproverHelper::getCurrent($approvers)->email;
            $testWorkorder->workflow = $workFlow->asJSON();
            $testWorkorder->approveState = ApproveState::PendingApproval;
            $testWorkorder->approverKey = $approverKey;
            $testWorkorder->viewOnlyKey = $viewOnlyKey;
            $testWorkorder->notifyOnFinalApproval = "final@example.com";

            $dbAdapter = null;
            
            try {
                // Insert a new workorder to use for Select test
                $dbAdapter = new WorkOrderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
                $result = $dbAdapter->Insert($testWorkorder);
                $this->assertTrue($result);
                // Select the workorder from the DB and test the data
                $result = $dbAdapter->select($dbAdapter->lastInsertId);
                $this->assertTrue($result->formName == "Test Workorder");
                $this->assertTrue($result->description == "Workorder for unit test");
                $this->assertTrue($result->formId == 123);
                $this->assertTrue($result->formXml == "");
                $this->assertTrue($result->formData == "");
                $this->assertTrue($result->currentApprover == "test.approver@webextant.com");
                $this->assertTrue($result->workflow == $workFlow->asJSON());
                $this->assertTrue($result->approveState == ApproveState::PendingApproval);
                $this->assertTrue($result->approverKey == $approverKey);
                $this->assertTrue($result->viewOnlyKey == $viewOnlyKey);
                $this->assertTrue($result->notifyOnFinalApproval == "final@example.com");
                // Cleanup the DB
                $dbAdapter->Delete($dbAdapter->lastInsertId);
            }
            catch(Exception $e) {
                echo $e;
                // Attempt cleanup the DB if possible
                if($dbAdapter != null && $dbAdapter->lastInsertId != null) {
                    $dbAdapter->Delete($dbAdapter->lastInsertId);
                }
            }
        }

        public function testUpdateWorkorder()
        {
            $dsn = 'mysql:host=localhost;dbname=disd';
            $user_name = 'dev_user';
            $pass_word = 'devpass';
            $currentUserEmail = 'testuser@example.com';

            $dbAdapter = null;
            $testWorkorder = new Workorder();

            $approverKey = generateApproverKey();
            $viewOnlyKey = generateApproverKey();

            $firstApprover = new Approver('Raymond Brady', 'test.approver@webextant.com', false);
            $secondApprover = new Approver('Jane', 'jane@example.com', false);
            $thirdApprover = new Approver('John', 'john@example.com', false);
            $fourthApprover = new Approver('Mark', 'mark@example.com', false);
            $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);
            $workFlow = new Workflow('Maintenance Workorder Workflow', $approvers, true);

            $testWorkorder->formName = "Test Workorder";
            $testWorkorder->description = "Workorder for unit test";
            $testWorkorder->formId = 123;
            $testWorkorder->formXml = "";
            $testWorkorder->formData = "";
            $testWorkorder->currentApprover = ApproverHelper::getCurrent($approvers)->email;
            $testWorkorder->workflow = $workFlow->asJSON();
            $testWorkorder->approveState = ApproveState::PendingApproval;
            $testWorkorder->approverKey = $approverKey;
            $testWorkorder->viewOnlyKey = $viewOnlyKey;
            $testWorkorder->notifyOnFinalApproval = "final@example.com";

            try {
                // Insert a new workorder to use for Update test
                $dbAdapter = new WorkOrderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
                $result = $dbAdapter->Insert($testWorkorder);
                $this->assertTrue($result);
                $workorderID = $dbAdapter->lastInsertId;
                // Change the orig workorder data
                $approverKeyChanged = generateApproverKey();
                $viewOnlyKeyChanged = generateApproverKey();
                $testWorkorder->formName = "Test Workorder CHANGED";
                $testWorkorder->description = "Workorder for unit test CHANGED";
                $testWorkorder->formId = 456;
                $testWorkorder->formXml = "CHANGED";
                $testWorkorder->formData = "CHANGED";
                $testWorkorder->currentApprover = ApproverHelper::getFinal($approvers)->email;
                $testWorkorder->workflow = "CHANGED";
                $testWorkorder->approveState = ApproveState::RejectClosed;
                $testWorkorder->approverKey = $approverKeyChanged;
                $testWorkorder->viewOnlyKey = $viewOnlyKeyChanged;
                $testWorkorder->notifyOnFinalApproval = "changed@example.com";
                $result = $dbAdapter->Update($workorderID, $testWorkorder);
                // Select the workorder from the DB and test the data
                $result = $dbAdapter->select($workorderID);
                $this->assertTrue($result->formName == "Test Workorder CHANGED");
                $this->assertTrue($result->description == "Workorder for unit test CHANGED");
                $this->assertTrue($result->formId == 456);
                $this->assertTrue($result->formXml == "CHANGED");
                $this->assertTrue($result->formData == "CHANGED");
                $this->assertTrue($result->currentApprover == "mark@example.com");
                $this->assertTrue($result->workflow == "CHANGED");
                $this->assertTrue($result->approveState == ApproveState::RejectClosed);
                $this->assertTrue($result->approverKey == $approverKeyChanged);
                $this->assertTrue($result->viewOnlyKey == $viewOnlyKeyChanged);
                $this->assertTrue($result->notifyOnFinalApproval == "changed@example.com");
                // Cleanup the DB
                $dbAdapter->Delete($dbAdapter->lastInsertId);
            }
            catch(Exception $e) {
                echo $e;
                // Attempt cleanup the DB if possible
                if($dbAdapter != null && $dbAdapter->lastInsertId != null) {
                    $dbAdapter->Delete($dbAdapter->lastInsertId);
                }
            }
        }
        
        /**
        * Send test emails via a local test server. This test only fails when an Exception is thrown.
        * Test email content should be verified outside of this test function.
        */
        public function testSendEmail()
        {
            $emailAdapter = null;
            $testWorkorder = new Workorder();
            $approverKey = generateApproverKey();
            $viewOnlyKey = generateApproverKey();

            $firstApprover = new Approver('Raymond Brady', 'raymond.approver@webextant.com', false);
            $secondApprover = new Approver('Jane', 'jane.approver@webextant.com', false);
            $thirdApprover = new Approver('John', 'john.approver@webextant.com', false);
            $fourthApprover = new Approver('Mark', 'mark.approver@webextant.com', false);
            $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);
            $workFlow = new Workflow('Maintenance Workorder Workflow', $approvers, true);

            $testWorkorder->id = 123;
            $testWorkorder->formName = "Test Workorder";
            $testWorkorder->description = "Workorder for unit test";
            $testWorkorder->formId = 456;
            $testWorkorder->formXml = "";
            $testWorkorder->formData = "";
            $testWorkorder->currentApprover = ApproverHelper::getCurrent($approvers)->email;
            $testWorkorder->workflow = $workFlow->asJSON();
            $testWorkorder->approveState = ApproveState::PendingApproval;
            $testWorkorder->approverKey = $approverKey;
            $testWorkorder->viewOnlyKey = $viewOnlyKey;
            $testWorkorder->updatedAt = date("Y-m-d H:i:s");
            $testWorkorder->updatedBy = "test@webextant.com";
            $testWorkorder->createdAt = date("Y-m-d H:i:s");
            $testWorkorder->createdBy = "test@webextant.com";
            $testWorkorder->notifyOnFinalApproval = "a@example.com, b@example.com, c@example.com";

            try {
                $emailAdapter = new WorkorderEmailAdapter("notification.test@webextant.com");
                $emailAdapter->SendViewOnlyCreatedToCreator($testWorkorder);
                $emailAdapter->SendNeedsApprovalToCurrentApprover($testWorkorder);
                $emailAdapter->SendViewOnlyFinalApprovalNotifications($testWorkorder);
            }
            catch(Exception $e) {
                echo $e;
                $this->assertTrue(false); // Fail test on exception
            }
        }
        
        public function testCreateViewModel()
        {
            $testWorkorder = new Workorder();
            $approverKey = generateApproverKey();
            $viewOnlyKey = generateApproverKey();
            $xml = '<?xml version="1.0"?>
                    <form-template>
                        <fields>
                            <field class="header" label="Example Header" type="header" subtype="h1"/>
                            <field class="paragraph" label="This is a paragraph field. It can be used to show information of instructions about the form. It also has several types of formatting." type="paragraph" subtype="blockquote"/>
                            <field class="form-control text-input" description="Your classroom number" label="Classroom" name="text-1460388671656" placeholder="Classroom Number" required="true" type="text" subtype="text"/>
                            <field class="form-control calendar" label="Date Field" name="date-1460388734688" type="date"/>
                            <field class="form-control select" label="Select" name="select-1460389051438" type="select">
                                <option value="option-1">Option 1</option>
                                <option value="option-2">Option 2</option>
                            </field>
                            <field class="form-control text-area" label="Describe your problem." name="textarea-1460388738229" required="true" type="textarea"/>
                        </fields>
                    </form-template>';

            $data = '{"text-1460388671656":"Test Room","date-1460388734688":"2016-04-13","select-1460389051438":"option-1","textarea-1460388738229":"testing"}';

            $firstApprover = new Approver('Raymond Brady', 'raymond.approver@webextant.com', false);
            $secondApprover = new Approver('Jane', 'jane.approver@webextant.com', false);
            $thirdApprover = new Approver('John', 'john.approver@webextant.com', false);
            $fourthApprover = new Approver('Mark', 'mark.approver@webextant.com', false);
            $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);
            $workFlow = new Workflow('Maintenance Workorder Workflow', $approvers, true);

            $testWorkorder->id = 123;
            $testWorkorder->formName = "Test Workorder";
            $testWorkorder->description = "Workorder for unit test";
            $testWorkorder->formId = 456;
            $testWorkorder->formXml = $xml;
            $testWorkorder->formData = $data;
            $testWorkorder->currentApprover = ApproverHelper::getCurrent($approvers)->email;
            $testWorkorder->workflow = $workFlow->asJSON();
            $testWorkorder->approveState = ApproveState::PendingApproval;
            $testWorkorder->approverKey = $approverKey;
            $testWorkorder->viewOnlyKey = $viewOnlyKey;
            $testWorkorder->updatedAt = date("Y-m-d H:i:s");
            $testWorkorder->updatedBy = "test@webextant.com";
            $testWorkorder->createdAt = date("Y-m-d H:i:s");
            $testWorkorder->createdBy = "test@webextant.com";
            $testWorkorder->notifyOnFinalApproval = "a@example.com, b@example.com, c@example.com";

            $viewModel = new WorkorderViewModel($testWorkorder, $approverKey);
            $this->assertTrue($viewModel->fieldCount === 4);
            $this->assertTrue(count($viewModel->fieldData) == 4);
        }
        
        public function testCreateViewModelFromDB()
        {
            $dsn = 'mysql:host=localhost;dbname=disd';
            $user_name = 'dev_user';
            $pass_word = 'devpass';
            $currentUserEmail = 'testuser@example.com';
            $xml = '<?xml version="1.0"?>
                    <form-template>
                        <fields>
                            <field class="header" label="Example Header" type="header" subtype="h1"/>
                            <field class="paragraph" label="This is a paragraph field. It can be used to show information of instructions about the form. It also has several types of formatting." type="paragraph" subtype="blockquote"/>
                            <field class="form-control text-input" description="Your classroom number" label="Classroom" name="text-1460388671656" placeholder="Classroom Number" required="true" type="text" subtype="text"/>
                            <field class="form-control calendar" label="Date Field" name="date-1460388734688" type="date"/>
                            <field class="form-control select" label="Select" name="select-1460389051438" type="select">
                                <option value="option-1">Option 1</option>
                                <option value="option-2">Option 2</option>
                            </field>
                            <field class="form-control text-area" label="Describe your problem." name="textarea-1460388738229" required="true" type="textarea"/>
                        </fields>
                    </form-template>';

            $data = '{"text-1460388671656":"Test Room","date-1460388734688":"2016-04-13","select-1460389051438":"option-1","textarea-1460388738229":"testing"}';

            $testWorkorder = new Workorder();

            $firstApprover = new Approver('Raymond Brady', 'test.approver@webextant.com', false);
            $secondApprover = new Approver('Jane', 'jane@example.com', false);
            $thirdApprover = new Approver('John', 'john@example.com', false);
            $fourthApprover = new Approver('Mark', 'mark@example.com', false);
            $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);
            $workFlow = new Workflow('Maintenance Workorder Workflow', $approvers, true);

            $testWorkorder->formName = "Test Workorder";
            $testWorkorder->description = "Workorder for unit test";
            $testWorkorder->formId = 123;
            $testWorkorder->formXml = $xml;
            $testWorkorder->formData = $data;
            $testWorkorder->currentApprover = ApproverHelper::getCurrent($approvers)->email;
            $testWorkorder->workflow = $workFlow->asJSON();
            $testWorkorder->approveState = ApproveState::PendingApproval;
            $testWorkorder->approverKey = generateApproverKey();
            $testWorkorder->viewOnlyKey = generateApproverKey();

            try {
                $dbAdapter = new WorkOrderDataAdapter($dsn, $user_name, $pass_word, $currentUserEmail);
                $result = $dbAdapter->Insert($testWorkorder);
                $this->assertTrue($result);
                // Select the workorder from the DB and test the data
                $dbWo = $dbAdapter->Select($dbAdapter->lastInsertId);
                $viewModel = new WorkorderViewModel($dbWo, $testWorkorder->approverKey);
                $this->assertTrue($viewModel->fieldCount === 4);
                $this->assertTrue(count($viewModel->fieldData) == 4);
                // Cleanup the DB
                $dbAdapter->Delete($dbAdapter->lastInsertId);
            }
            catch(Exception $e) {
                echo $e;
            }

        } 

    }

?>