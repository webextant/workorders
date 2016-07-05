<?php 
require('../resources/library/workflow.php');
require('../resources/library/approver.php');

    class WorkflowTest extends PHPUnit_Framework_TestCase
    {
        public function testWorkflowClass()
        {
            $firstApprover = new Approver('Raymond Brady', 'raymond.brady@webextant.com', false);
            $secondApprover = new Approver('Jane', 'jane@example.com', true);
            $thirdApprover = new Approver('John', 'john@example.com', false);
            $fourthApprover = new Approver('Mark', 'mark@example.com', false);
            $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);

            $w = new Workflow('Maintenance Workorder Workflow', $approvers);
            
            $this->assertTrue($w->name == 'Maintenance Workorder Workflow');
            $this->assertTrue(count($w->approvers) == 4);
            $this->assertTrue($w->approvers[0]->name == 'Raymond Brady');            
            $this->assertTrue($w->approvers[0]->email == 'raymond.brady@webextant.com');            
            $this->assertFalse($w->approvers[0]->current);            
        }
        
        public function testWorkflowClassJson()
        {
            $firstApprover = new Approver('John', 'john@example.com', false);
            $secondApprover = new Approver('John', 'john@example.com', false);
            $approvers = array($firstApprover, $secondApprover);
            $w = new Workflow('Maintenance Workorder Workflow', $approvers);
            
            // To JSON
            $this->assertJsonStringEqualsJsonString($w->asJSON(), '{"name": "Maintenance Workorder Workflow", "approvers": [{"name":"John","email":"john@example.com","current": false},{"name":"John","email":"john@example.com","current": false}]}');
            
            // From JSON
            $woFromJson = json_decode('{"name": "Maintenance Workorder Workflow", "approvers": [{"name":"John","email":"john@example.com","current": false},{"name":"John","email":"john@example.com","current": false}]}');
            $this->assertTrue($woFromJson->name == 'Maintenance Workorder Workflow');
            $this->assertTrue(count($woFromJson->approvers) == 2);
            $this->assertTrue($woFromJson->approvers[0]->name == 'John');            
            $this->assertTrue($woFromJson->approvers[0]->email == 'john@example.com');            
            $this->assertFalse($woFromJson->approvers[0]->current);            
        }
    }

?>