<?PHP
    /**
    * Classes for working with appinfo
    * @author michael@keoflex.com
    * @license MIT
    */
    
    /**
     * A workorder descibes work to be performed, tracks approval state, defines workflow
     */
    
class AppInfo
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

  function Set($key, $value)
  {
      // use upsert method. Same function handles insert and update. INFO_request field in DB would need to be unique key 
      $sql = "INSERT INTO appinfo (INFO_request, INFO_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE INFO_value = :upvalue"; 
      $sth = $this->conn->prepare($sql);
      $status = $sth->execute(array(':key' => $key, ':value' => $value, ':upvalue' => $value));
      return $status;
  }

  function Get($key)
  {
      $sql = "SELECT * FROM appinfo WHERE INFO_request = :key";
      $sth = $this->conn->prepare($sql);
      $sth->execute(array(':key' => $key));
      $result = $sth->fetch(PDO::FETCH_ASSOC);
      return $result;
  }

}
?>