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
    
	class INFO
    {
        public $INFO_value;
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
        
		
		/***********************
		SelectByPeram
		Allows custom perameters based on
		(Userid, currentApprover etc ... json format)
		"FIELD|OPERATOR":VALUE, NEXT...
		createdBy|=me@domain.com
		***********************/
		function SelectWhereJSON($whereArray, $limit = 5000)
        {
            $requirements = '';
			if($whereArray <> ''){
				$requirements = 'WHERE ';
				foreach($whereArray as $var => $value){
					$var_exp = explode("|",$var);
					//echo $var_exp[0]." ". $var_exp[1]." ".$value."<br />";	
					$requirements .= "AND ".$var_exp[0].$var_exp[1]."'".$value."' ";
				}
			}
				$sql = "SELECT * FROM users  ".$requirements." ORDER BY user_name DESC";
					$sql = str_replace('WHERE AND','WHERE',$sql);
					//echo $sql."<br />";
				$stmt = $this->conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_CLASS, "User");
				$stmt->execute();
				$workorders = $stmt->fetchAll(PDO::FETCH_CLASS);
				return $workorders;
			
        }
		/***********************/
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
	
		function AdminUpdate($user_id, $user_email, $user_fname,$user_lname,$user_group,$user_perms, $user_password)
        {
            $domain_eplode = explode('@', $user_email);
			$check_domain = "select * from appinfo where INFO_request = 'RegDomain' and INFO_value like '%@".$domain_eplode[1]."%' OR INFO_request = 'RegDomain' and INFO_value = ''";
           // echo $check_domain;
			
			$result_domain = $this->conn->prepare($check_domain);
			$result_domain->setFetchMode(PDO::FETCH_CLASS, "INFO");

			$result_domain->execute();
			            $INFO = $result_domain->fetch(PDO::FETCH_CLASS);
			$password = '';
			if($user_password <> ''){
				$password = ", user_password_hash = '".password_hash($user_password, PASSWORD_DEFAULT)."'";
			}
			if($INFO->INFO_request){
				$sql = "UPDATE users set
				 user_email = '".$user_email."', 
				 user_fname = '".$user_fname."', 
				 user_lname = '".$user_lname."',
				 user_group = '".$user_group."',
				 user_perms = ".$user_perms.$password."
				 where user_id = ".$user_id."";
				 
				 echo $sql;
				$result = $this->conn->prepare($sql);
				$status = $result->execute();
				return $status; 
				
			}else{
				return "ERROR|The email domain is not allowed";	
			}
				/*
				$sql = "UPDATE groups set GRP_name = '".$GRP_name."'where GRP_id = ".$GRP_id."";
				$result = $this->conn->prepare($sql);
				$status = $result->execute();
				return $status; 
			*/
			
        }
        
    }
?>