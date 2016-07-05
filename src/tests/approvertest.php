<?php
require('../resources/library/approver.php');        

/**
* NOTE:
* This test class is dependant on a local MySql developer database named formsdb
* The dev database must exists and the MySql PDO driver must be installed.
* Required tables and test data will be created as needed in the dev database
*/

class ApproverTest extends PHPUnit_Framework_TestCase
{
    /** Generates a approver key */
    public function testApproverKeyGen()
    {
        $this->assertTrue(strlen(generateApproverKey()) == 32);
    }

    /** Generates a GUID without braces */
    public function testGuidGen()
    {
        $this->assertTrue(strlen(guid(false)) == 36);
    }

    /** Generates a GUID with braces */
    public function testGuidGenBraces()
    {
        $this->assertTrue(strlen(guid(true)) == 38);
    }
    
    /** Create Approver test public properties */
    public function testApproverClassCreate()
    {
        $myApprover = new Approver('Raymond Brady', 'raymond.brady@webextant.com', true);
        $this->assertTrue($myApprover->name == 'Raymond Brady'); 
        $this->assertTrue($myApprover->email == 'raymond.brady@webextant.com'); 
        $this->assertTrue($myApprover->current); 
    }
    
    /** Test create Approver from email array */
    public function testApproverHelperClassCreateFromEmailArray()
    {
        $emailArray = array('test@example.com','another@example.com','third@example.com');
        $approvers = ApproverHelper::NewApproverArrayFromEmailArray($emailArray);
        $this->assertTrue($approvers[0]->email == 'test@example.com');
        $this->assertTrue($approvers[0]->name == 'test@example.com');
        $this->assertTrue($approvers[0]->current == false);
        $this->assertTrue($approvers[1]->email == 'another@example.com');
        $this->assertTrue($approvers[1]->name == 'another@example.com');
        $this->assertTrue($approvers[1]->current == false);
        $this->assertTrue($approvers[2]->email == 'third@example.com');
        $this->assertTrue($approvers[2]->name == 'third@example.com');
        $this->assertTrue($approvers[2]->current == false);

        // test setting current approver
        $approvers = ApproverHelper::NewApproverArrayFromEmailArray($emailArray, 'another@example.com');
        $this->assertTrue($approvers[0]->email == 'test@example.com');
        $this->assertTrue($approvers[0]->name == 'test@example.com');
        $this->assertTrue($approvers[0]->current == false);
        $this->assertTrue($approvers[1]->email == 'another@example.com');
        $this->assertTrue($approvers[1]->name == 'another@example.com');
        $this->assertTrue($approvers[1]->current == true);
        $this->assertTrue($approvers[2]->email == 'third@example.com');
        $this->assertTrue($approvers[2]->name == 'third@example.com');
        $this->assertTrue($approvers[2]->current == false);

        // test exception when setting current approver
        $this->expectException('Exception');
        $approvers = ApproverHelper::NewApproverArrayFromEmailArray($emailArray, 'notanapprover@example.com');
    }

    /** Approver Helper class tests */
    public function testApproverHelperClass()
    {
        $firstApprover = new Approver('Raymond Brady', 'raymond.brady@webextant.com', false);
        $secondApprover = new Approver('Jane', 'jane@example.com', true);
        $thirdApprover = new Approver('John', 'john@example.com', false);
        $fourthApprover = new Approver('Mark', 'mark@example.com', false);
        $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);

        // Test helper functions
        $first = ApproverHelper::getFirst($approvers);
        $next = ApproverHelper::getNext($approvers);
        $previous = ApproverHelper::getPrevious($approvers);
        $final = ApproverHelper::getFinal($approvers);
        $current = ApproverHelper::getCurrent($approvers);
        
        $this->assertTrue($first->name == 'Raymond Brady'); 
        $this->assertTrue($next->name == 'John'); 
        $this->assertTrue($previous->name == 'Raymond Brady'); 
        $this->assertTrue($final->name == 'Mark'); 
        $this->assertTrue($current->name == 'Jane'); 
    }
    
    /** Approver Helper class tests. When the current user who is submitting is also in the approver list helper should skip to the next approver after user. */
    public function testApproverHelperSettingFirstApprover()
    {
        $firstApprover = new Approver('Raymond Brady', 'raymond.brady@webextant.com', false);
        $secondApprover = new Approver('Jane', 'jane@example.com', false);
        $thirdApprover = new Approver('John', 'john@example.com', false);
        $fourthApprover = new Approver('Mark', 'mark@example.com', false);
        $approvers = array($firstApprover, $secondApprover, $thirdApprover, $fourthApprover);

        // Test first approver within the context of the current user.
        $first = ApproverHelper::setNextOrFirstCurrent($approvers, 'jane@example.com');
        $this->assertTrue($first->name == 'John');
        $this->assertTrue($first->email == 'john@example.com');
        $this->assertTrue($first->current);

        $this->assertTrue(ApproverHelper::onlyOneApproveIsCurrent($approvers));

        $first = ApproverHelper::setNextOrFirstCurrent($approvers, 'john@example.com');
        $this->assertTrue($first->name == 'Mark'); 
        $this->assertTrue($first->email == 'mark@example.com');
        $this->assertTrue($first->current);

        $this->assertTrue(ApproverHelper::onlyOneApproveIsCurrent($approvers));

        $first = ApproverHelper::setNextOrFirstCurrent($approvers, 'doesnotexist@example.com');
        $this->assertTrue($first->name == 'Raymond Brady'); 
        $this->assertTrue($first->email == 'raymond.brady@webextant.com');
        $this->assertTrue($first->current);

        $this->assertTrue(ApproverHelper::onlyOneApproveIsCurrent($approvers));

        $first = ApproverHelper::setNextOrFirstCurrent($approvers, 'raymond.brady@webextant.com');
        $this->assertTrue($first->name == 'Jane'); 
        $this->assertTrue($first->email == 'jane@example.com');
        $this->assertTrue($first->current);

        $this->assertTrue(ApproverHelper::onlyOneApproveIsCurrent($approvers));

        $approvers[2]->current = true;
        $this->assertFalse(ApproverHelper::onlyOneApproveIsCurrent($approvers));

    }

    /** Approval state for workorders */
    public function testApproveState()
    {
        $this->assertTrue(ApproveState::PendingApproval == 'PendingApproval');
        $this->assertTrue(ApproveState::ApproveInProgress == 'ApproveInProgress');
        $this->assertTrue(ApproveState::ApproveClosed == 'ApproveClosed');
        $this->assertTrue(ApproveState::RejectClosed == 'RejectClosed');
    }
    
    /** Test Approvers table exists in DB */
    public function testApproversTableExists()
    {
        $dsn = 'mysql:host=localhost;dbname=disd';
        $user_name = 'dev_user';
        $pass_word = 'devpass';

        try {
            $dbAdapter = new ApproverDataAdapter($dsn, $user_name, $pass_word);
            $result = $dbAdapter->CreateTableIfNotExist();
            $this->assertTrue($result);            
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }
    
    /** Test inserting Approver into the dev DB */
    public function testInsertApproverInDatabase()
    {
        $dsn = 'mysql:host=localhost;dbname=disd';
        $user_name = 'dev_user';
        $pass_word = 'devpass';

        $testApprover = new Approver('Raymond Brady', 'raymond.brady@webextant.com', false);

        try {
            $dbAdapter = new ApproverDataAdapter($dsn, $user_name, $pass_word);
            $result = $dbAdapter->Insert($testApprover);
            $this->assertTrue($result);
            // read back the data and test
            $approverFromAdapter = $dbAdapter->Select($testApprover->email);
            $this->assertTrue($approverFromAdapter->name == "Raymond Brady");

        }
        catch(Exception $e)
        {
            echo $e;
        }
    }

    /** Test inserting existing Approver into the dev DB */
    public function testInsertExistingApproverInDatabase()
    {
        $dsn = 'mysql:host=localhost;dbname=disd';
        $user_name = 'dev_user';
        $pass_word = 'devpass';

        $testApprover = new Approver('Raymond Brady', 'raymond.brady@webextant.com', false);
        $this->expectException('PDOException');
        $dbAdapter = new ApproverDataAdapter($dsn, $user_name, $pass_word);
        $result = $dbAdapter->Insert($testApprover);
    }

    /** Test reading all Approvers in the dev DB */
    public function testReadingAllApproversInDatabase()
    {
        $dsn = 'mysql:host=localhost;dbname=disd';
        $user_name = 'dev_user';
        $pass_word = 'devpass';

        try {
            $dbAdapter = new ApproverDataAdapter($dsn, $user_name, $pass_word);
            
            $approversFromAdapter = $dbAdapter->SelectAll();
            $this->assertTrue($approversFromAdapter[0]->name == "Raymond Brady");

        }
        catch(Exception $e)
        {
            echo $e;
        }
    }

    /** Test deleting Approver in the dev DB. Approver record was created in the testInsertApproverInDatabase function */
    public function testDeleteApproverInDatabase()
    {
        $dsn = 'mysql:host=localhost;dbname=disd';
        $user_name = 'dev_user';
        $pass_word = 'devpass';

        $testApprover = new Approver('Raymond Brady', 'raymond.brady@webextant.com', false);

        try {
            $dbAdapter = new ApproverDataAdapter($dsn, $user_name, $pass_word);
            $result = $dbAdapter->Delete($testApprover->email);
            $this->assertTrue($result);
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }

}
?>