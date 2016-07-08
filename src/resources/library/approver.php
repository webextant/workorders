<?PHP
    /**
    * Classes for working with Approvers
    * @author raymond.brady@webextant.com
    * @license MIT
    */
    
    /**
     * An approver is a person with ability to approve an item
     * @param {$name} string - full name
     * @param {$email} string - email address
     * @param {$current} boolean - is current approver in a workflow
     */
    class Approver
    {
        function __construct($name, $email, $current)
        {
            $this->name = $name;
            $this->email = $email;
            $this->current = $current;
        }
        
        public $name;
        public $email;
        public $current;
    }
    
    /**
     * An approver helper utility
     */
    class ApproverHelper
    {
        /** Get the first Approver in the array */
        public static function getFirst($approverArray)
        {
            return $approverArray[0];
        }
        /** Get the Next Approver in the array */
        public static function getNext($approverArray)
        {
            $currentKey;
            foreach ($approverArray as $key => $value) {
                if($value->current == true) {
                    $currentKey = $key;
                    break; 
                }
            }
            return $approverArray[$currentKey + 1];
        }
        /** Get the Previous Approver in the array */
        public static function getPrevious($approverArray)
        {
            $currentKey;
            foreach ($approverArray as $key => $value) {
                if($value->current == true) {
                    $currentKey = $key;
                    break; 
                }
            }
            return $approverArray[$currentKey - 1];
        }
        /** Get the Final Approver in the array */
        public static function getFinal($approverArray)
        {
            return end($approverArray);
        }
        /** Get the Current Approver in the array */
        public static function getCurrent($approverArray)
        {
            $currentKey;
            foreach ($approverArray as $key => $value) {
                if($value->current == true) {
                    $currentKey = $key;
                    break;
                }
            }
            return $approverArray[$currentKey];
        }
        public static function toApproverArray($assocArray)
        {
            $newApproverArray = array();
            foreach ($assocArray['approvers'] as $key => $value) {
                $approver = new Approver($value['name'], $value['email'], $value['current']);
                array_push($newApproverArray, $approver);
            }
            return $newApproverArray;
        }
        /** Set matching approver as current. Reset all others to false. */
        public static function setCurrent($approverArray, $approverToSet)
        {
            $newCurrentKey = null;
            foreach ($approverArray as $key => $value) {
                $approverArray[$key]->current = false;
                if ($value->email == $approverToSet->email) {
                    $approverArray[$key]->current = true;
                    $newCurrentKey = $key;                     
                }
            }
            return $approverArray[$newCurrentKey];
        }
        /** Set approver with matching email as current. If no match the first is set as current */
        public static function setMatchingOrFirstCurrent($approverArray, $emailToMatch)
        {
            $newCurrentKey = 0;
            foreach ($approverArray as $key => $value) {
                $approverArray[$key]->current = false;
                if ($value->email == $emailToMatch) {
                    $approverArray[$key]->current = true;
                    $newCurrentKey = $key;                     
                }
            }
            return $approverArray[$newCurrentKey];
        }
        /** Find approver with matching email, set the next approver as current. If no match the first is set as current */
        public static function setNextOrFirstCurrent($approverArray, $emailToMatch)
        {
            $newCurrentKey = 0;
            $flag = false;
            foreach ($approverArray as $key => $value) {
                $approverArray[$key]->current = false;
                if ($flag) {
                    $newCurrentKey = $key;                     
                }
                if ($value->email == $emailToMatch) {
                    $flag = true;
                } else {
                    $flag = false;
                }
            }
            $result = $approverArray[$newCurrentKey];
            $result->current = true;
            return $result;
        }
        /** Verify only one approve in the array is set as current.
        * @return {boolean}
        */
        public static function onlyOneApproveIsCurrent($approverArray)
        {
            $count = 0;
            foreach ($approverArray as $key => $value) {
                if ($value->current) {
                    $count++;
                }
            }
            if ($count == 0 || $count > 1) {
                return false;
            } else {
                return true;
            }
        }
        /** Creates an array of Approver class objects from an array of email addresses. Name set as email and all current set to false unless param matches */
        public static function NewApproverArrayFromEmailArray($emailArray, $current = "")
        {
            $resultArray = array();
            $wasCurrentSet = false;
            foreach ($emailArray as $key => $value) {
                if ($current == $value) {
                    $setCurrent = true;
                    $wasCurrentSet = true;
                } else {
                    $setCurrent = false;
                }
                $approver = new Approver($value, $value, $setCurrent);
                array_push($resultArray, $approver);
            }
            // If current approver did not get set when it should have been.
            if ($current != "" && $wasCurrentSet == false) {
                throw new Exception("Error setting current approver in array", 1);
            }
            return $resultArray;
        }
        /** verify first groupApprovers item is not empty and merge as needed. */
        public static function MergeApproverArrays($groupApprovers, $approvers)
        {
            if (strlen($groupApprovers[0]->email) != 0) {
                return array_merge($groupApprovers, $approvers);
            } else {
                return $approvers;
            }
        }
    }
    
    /** Approve State for Workorders */
    abstract class ApproveState
    {
        const PendingApproval = "PendingApproval"; // Waiting for an approver to approve. In this state until final approval.
        const ApproveInProgress = "ApproveInProgress"; // Has been approved and work is in progress.
        const ApproveClosed = "ApproveClosed"; // Workorder is closed and was approved. Work completed.
        const RejectClosed = "RejectClosed"; // Workorder is closed and was not approved
    }
    
    
    /** Generate an approver key */
    function generateApproverKey(){
        return guid(false, false);
    }
    
    /** Taken from the PHP documentation website. CREDIT: Kristof_Polleunis at yahoo dot com.
    * A guid function that works in all php versions:
    * MEM 3/30/2015 : Modified the function to allow someone to specify whether or not they want the curly braces on the GUID.
    * 4/8/2016: Modified the function to allow someone to specify whether or not they want hyphens. 
    */
    function guid( $opt = true, $hyp = true ){       //  Set to true/false as your default way to do this.

        if( function_exists('com_create_guid') ){
            if( $opt ){ 
                    if( $hyp ) { return com_create_guid(); }
                    else { return str_replace("-", "", com_create_guid()); }
                }
                else {
                    if( $hyp ) { return trim( com_create_guid(), '{}' ); }
                    else { return str_replace("-", "", trim( com_create_guid(), '{}' )); }
                }
            }
            else {
                mt_srand( (double)microtime() * 10000 );    // optional for php 4.2.0 and up.
                $charid = strtoupper( md5(uniqid(rand(), true)) );
                $hyphen = chr( 45 );    // "-"
                $left_curly = $opt ? chr(123) : "";     //  "{"
                $right_curly = $opt ? chr(125) : "";    //  "}"
                $uuid = $left_curly
                    . substr( $charid, 0, 8 ) . $hyphen
                    . substr( $charid, 8, 4 ) . $hyphen
                    . substr( $charid, 12, 4 ) . $hyphen
                    . substr( $charid, 16, 4 ) . $hyphen
                    . substr( $charid, 20, 12 )
                    . $right_curly;
                if( $hyp ) { return $uuid; }
                    else { return str_replace("-", "", $uuid); }
                }
    }

    /**
     * Class for reading and persisting Approver objects in the DB.
     */
    class ApproverDataAdapter
    {
        private $conn = null;
        
        function __construct($dsn, $user_name, $pass_word)
        {
            // setup the db connection for this adapter
            $this->conn = new PDO($dsn, $user_name, $pass_word);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
                $sql = "CREATE TABLE IF NOT EXISTS Approvers (email VARCHAR( 320 ) PRIMARY KEY, name VARCHAR( 100 ))";
                $this->conn->exec($sql);
            }
            catch(PDOException $e)
            {
                $result = FALSE;
            }
            return $result;
        }
        
        /**
        * Create a new record in the Approvers DB table
        * @param {$approver} Approver - Approver class object.
        * @return {$status} bool - TRUE on success or FALSE on failure
        */
        public function Insert($approver)
        {
            $sql = "INSERT INTO Approvers (Name, Email) VALUES (:Name, :Email)";
            $result = $this->conn->prepare($sql);
            $status = $result->execute(array('Name' => $approver->name, 'Email' => $approver->email));
            $this->lastInsertId = $this->conn->lastInsertId();
            return $status;
        }
        
        /**
        * Read one record from the Approvers DB table by email
        * @param {$email} string - email address
        * @return {$approver} Approver - Approver object based on DB result or FALSE
        */
        public function Select($email)
        {
            $sql = "SELECT * FROM Approvers WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array(':email' => $email));
            $approver = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($approver != FALSE) {
                // create new Approver object for the db result. DB structure does not map 1-to-1 to the Approver class.
                $approver = new Approver($approver['name'], $approver['email'], false);
            }
            return $approver;
        }

        /**
        * Read all records from the Approvers DB table.
        * @return {$allApprovers} Array - Array of Approver objects based on DB results or FALSE
        */
        public function SelectAll()
        {
            $allApprovers = array();
            $sql = "SELECT * FROM Approvers ORDER BY email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result != FALSE) {
                foreach ($result as $key => $value) {
                    // create new Approver for each db record
                    $approver = new Approver($value['name'], $value['email'], false);
                    array_push($allApprovers, $approver);
                }
            } else {
                return FALSE;
            }
            return $allApprovers;
        }

        /**
        * An approver is a person with ability to approve an item
        * @param {$email} string - email address
        * @result {$result} bool - TRUE on success or FALSE on failure
        */
        public function Delete($email)
        {
            $sql = "DELETE FROM Approvers WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute(array(':email' => $email));
            return $result;
        }
        
    }
?>