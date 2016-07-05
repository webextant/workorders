<?PHP
    /**
    * Classes for working with Users
    * @author raymond.brady@webextant.com
    * @license MIT
    */
    
    /**
     * Defines a user of the system. Simple class for working with user data.
     */
    class User
    {
        public $user_name;
        public $user_email;
        public $user_group;
    }
    
    /**
     * Simple class for working with group names in an array.
     */
    class Group
    {
        public $name;
    }

    /**
     * Class for reading user data in the DB. Creating user DB records is accomplished via ../classes/Registration.php
     */
    class UserDataAdapter
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
        function Select($id)
        {
            $sql = "SELECT user_name, user_email, user_group FROM users WHERE user_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
            $stmt->execute(array(':id' => $id));
            $user = $stmt->fetch(PDO::FETCH_CLASS);
            return $user;
        }
        function SelectAll($limit = 100)
        {
            $sql = "SELECT user_name, user_email, user_group FROM users ORDER BY user_name DESC LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
            $stmt->execute(array(':limit' => $limit));
            $users = $stmt->fetchAll(PDO::FETCH_CLASS);
            return $users;
        }
        function SelectAllInGroup($groupName)
        {
            $sql = "SELECT user_name, user_email, user_group FROM users WHERE user_group = :group";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
            $stmt->execute(array(':group' => $groupName));
            $users = $stmt->fetch(PDO::FETCH_CLASS);
            return $users;
        }
        function SelectUniqueGroupNames($limit = 100)
        {
           // $sql = "SELECT user_group as name FROM users GROUP BY user_group ORDER BY user_group LIMIT :limit";
            $sql = "SELECT GRP_name as name FROM groups GROUP BY GRP_name ORDER BY GRP_name LIMIT :limit";
			$stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Group");
            $stmt->execute(array(':limit' => $limit));
            $groups = $stmt->fetchAll(PDO::FETCH_CLASS);
            return $groups;
        }
        
    }
?>