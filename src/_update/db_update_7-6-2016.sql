/*

Add domain restrictions to appinfo  If value is blank then no restrictions else only listed domains (JSON format) are allowed

ie:

{domain=@example.org,domain=@test.com}

*/



INSERT INTO  `appinfo` (

`INFO_id` ,

`INFO_request` ,

`INFO_value`

)

VALUES (

NULL ,  'RegDomain',  ''

);



/*

Update version number with X.0.0 since this is a databse change with many added features

*/



UPDATE  `appinfo` SET  `INFO_value` =  '2.0.0' WHERE  `appinfo`.`INFO_id` =1;



/*

Insert First Name and Last Name fields in users table

*/

ALTER TABLE  `users` ADD  `user_fname` VARCHAR( 25 ) NOT NULL COMMENT  'First Name' AFTER  `user_name` ;

ALTER TABLE  `users` ADD  `user_lname` VARCHAR( 25 ) NOT NULL COMMENT  'Last Name' AFTER  `user_fname` ;

