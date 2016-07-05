<?php

/**
 * Configuration for: Database Connection
 *
 * For more information about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 *
 * DB_HOST: database host, usually it's "127.0.0.1" or "localhost", some servers also need port info
 * DB_NAME: name of the database. please note: database and database table are not the same thing
 * DB_USER: user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
 * DB_PASS: the password of the above user
 */
  $database_name = "DB_NAME";
  $dsn = 'mysql:host=localhost;dbname='.$database_name;
  $user_name = 'DB_USERNAME_HERE'; //DEFUAULT DB_USERNAME_HERE
  $pass_word = 'DB_PASSWORD_HERE';

  if($user_name == 'DB_USERNAME_HERE'){
	echo "<h1 style=\"color:red\">Woah!!! Hold on there Sparky, you need to update the util/forms_db_info.php befor running this program!</h1>";  
  }

define("DB_HOST", "127.0.0.1");
define("DB_NAME", $database_name);
define("DB_USER", $user_name);
define("DB_PASS", $pass_word);

define("DB_DSN", $dsn );
