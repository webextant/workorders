<?php
/*
	Author: Raymond Brady (@thewizster)
	Created: Thur, 11 Aug 2016
	Description: Collaborators can be added to a work item and can view, comment, and get notifications.
*/

class Collaborator
{
    public $user_id;
    public $user_fname;
    public $user_lname;
    public $user_fullname;
    public $user_email;
}

/**
 * 
 */
class CollaboratorViewModel
{
    private $conn = null;
    public $collabUsers = null;
    
    function __construct($dsn, $user_name, $pass_word)
    {
        // setup the db connection for this adapter
        $this->conn = new PDO($dsn, $user_name, $pass_word);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Load all users into $collabUsers who are flagged as a collab and are available (not forwarded)
        $this->allAvailable();
    }
    function __destruct()
    {
        // tear down the db connection, no longer needed
        $this->conn = null;
    }

    public function allAvailable() {
        // return $collabUsers as array of Collaborator
        $sql = "SELECT user_id, user_fname, user_lname, concat(user_fname, ' ', user_lname) AS user_fullname, user_email FROM users WHERE collaborator > 0 ORDER BY user_lname DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Collaborator");
        $stmt->execute();
        $this->collabUsers = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $this->collabUsers;
    }
}

?>