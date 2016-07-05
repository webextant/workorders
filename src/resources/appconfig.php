<?php
//I had to put this here since I am adjusting the Navbar
//if the function does not exist it breaks the pages I have not updated
include "includes/page_encryption.php";
$pg_encrypt_key = "MY_ENCRYPTION_KEY";


//do we need to adjust any of this.  Specifically workorderview.php and the database info? 
abstract class Config {
    const BaseUrl = "http://www.example.com/";
    const SiteTitleShort = "FLOW";
    const SiteTitleLong = "Work Flow";
    const DbDsn = "mysql:host=localhost;dbname=formsdb";
    const DbUsername = "";
    const DbPassword = "";
    const WorkorderApproverScript = "workorderview.php";
    const WorkorderViewOnlyScript = "workorderview.php";
}

?>