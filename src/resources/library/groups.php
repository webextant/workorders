<?PHP
    /**
    * Classes for working with appinfo
    * @author michael@keoflex.com
    * @license MIT
    */
    
    /**
     * A workorder descibes work to be performed, tracks approval state, defines workflow
     */
class Group
    {
        public $name;
    } 
    
class groupDataAdapter
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

 /*
  function insert($value)
  {
      // use upsert method. Same function handles insert and update. INFO_request field in DB would need to be unique key 
      $sql = "INSERT INTO groups (GRP_name) VALUES (:value)"; 
      $sth = $this->conn->prepare($sql);
      $status = $sth->execute(array(':value' => $value));
      return $status;
  }
  */
  
  function Insert($GRP_name)
        {
            if ($GRP_name == null) {
                throw new Exception("Operation requires Group Name.", 1);
            }
            $sql = "INSERT ignore INTO groups (GRP_name) VALUES ('".$GRP_name."')";
		    $result = $this->conn->prepare($sql);
            $status = $result->execute();
            $this->lastInsertId = $this->conn->lastInsertId();
            return $status; 
        }
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
				$sql = "SELECT * FROM groups  ".$requirements." ORDER BY GRP_name asc";
					$sql = str_replace('WHERE AND','WHERE',$sql);
					//echo $sql."<br />";
				$stmt = $this->conn->prepare($sql);
				$stmt->setFetchMode(PDO::FETCH_CLASS, "Group");
				$stmt->execute();
				$groups = $stmt->fetchAll(PDO::FETCH_CLASS);
				return $groups;
			
        }
		
  function SelectAll($limit = 100)
        {
            $sql = "SELECT GRP_name, GRP_id FROM groups ORDER BY GRP_name asc LIMIT 100";
            $stmt = $this->conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
            $stmt->execute(array());
            $groups = $stmt->fetchAll(PDO::FETCH_CLASS);
			//echo $sql;
            return $groups;
        }

}
?>