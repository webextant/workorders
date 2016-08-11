<?php
/*
	Author: Raymond Brady (@thewizster)
	Created: Thur, 11 Aug 2016
	Description: Collaborators can be added to a work item and can view, comment, and get notifications.
*/

class Collaborator
{
    public $user_id;
    public $user_email;
    public $user_name;
}

/**
 * 
 */
class CollaboratorViewModel
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
        // Load all users into $collabUsers who are flagged as a collab and are available (not forwarded)

    }
    function __destruct()
    {
        // tear down the db connection, no longer needed
        $this->conn = null;
    }

    protected $collabUsers;

    public function allAvailable() {
        // return $collabUsers as array of Collaborator
    }
    public function allAvailableAsJson() {
        // encode $collabUsers as JSON and return
    }
}

?>