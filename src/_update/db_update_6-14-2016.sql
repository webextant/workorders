CREATE TABLE IF NOT EXISTS `groups` ( `GRP_id` INT NOT NULL AUTO_INCREMENT , `GRP_name` VARCHAR(50) NOT NULL COMMENT 'Currently GRP_id is not saved in users table GRP_name is' , PRIMARY KEY (`GRP_id`)) ENGINE = MyISAM;
/*
Add a permissions section to user table
*/
ALTER TABLE `users` ADD `user_perms` TINYINT NOT NULL DEFAULT '3' COMMENT 'smaller=higher perms' AFTER `form_manager`;