<?php
    require('../resources/library/email.php');
    class MockOrder
    {
        public $id = 123;
        public $approverKey = "ABC123";
        public $viewOnlyKey = "ABC123";
    }
    
    class EmailTest extends PHPUnit_Framework_TestCase
    {
        public function testLinkHelper()
        {
            $mockWorkorder = new MockOrder();
            
            $this->assertEquals(LinkHelper::getApproverLink($mockWorkorder), 'http://www.webextant.com/workorderview.php?id=123&key=ABC123');
            $this->assertEquals(LinkHelper::getViewOnlyLink($mockWorkorder), 'http://www.webextant.com/workorderview.php?id=123&key=ABC123');            
        }
    }

?>