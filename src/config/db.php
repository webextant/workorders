<?php

/**
 * Configuration for: Database Connection
 *
 * NOTE: There are two ways to configure the DB connection.
 * 1) Environment variables
 *    WO_ENV_ENABLED=1
 *    WO_DB_NAME=yourDBname
 *    WO_DB_USERNAME=yourDBusername
 *    WO_DB_PASSWORD=yourDBpassword
 * 2) Modify values in this config file below
 *    Change DB_NAME, DB_USERNAME_HERE, and DB_PASSWORD_HERE
 * 
 * For more information about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 *
 * DB_HOST: database host, usually it's "127.0.0.1" or "localhost", some servers also need port info
 * DB_NAME: name of the database. please note: database and database table are not the same thing
 * DB_USER: user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
 * DB_PASS: the password of the above user
 */
  if(getenv("WO_ENV_ENABLED") == 1)
  {
    // Set these in your local environment
    $database_name = getenv("WO_DB_NAME"); // DO NOT MODIFY HERE
    $dsn = 'mysql:host=localhost;dbname='.$database_name;
    $user_name = getenv('WO_DB_USERNAME'); // DO NOT MODIFY HERE
    $pass_word = getenv('WO_DB_PASSWORD'); // DO NOT MODIFY HERE
  } else {
    // Modify the values below IF you are NOT using environment variables for config.
    $database_name = "DB_NAME"; // SET DB NAME HERE
    $dsn = 'mysql:host=localhost;dbname='.$database_name;
    $user_name = 'DB_USERNAME_HERE'; // SET DB USERNAME HERE
    $pass_word = 'DB_PASSWORD_HERE'; // SET DB PASSWORD HERE

    if($user_name == 'DB_USERNAME_HERE'){
      echo "<h1 style=\"color:red\">Woah!!! Hold on there Sparky, you need to update the config/db.php before running this program!</h1>";
      die();  
    }
  }

define("DB_HOST", "127.0.0.1");
define("DB_NAME", $database_name);
define("DB_USER", $user_name);
define("DB_PASS", $pass_word);

define("DB_DSN", $dsn );
