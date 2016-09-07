ALTER TABLE `workorders` ADD `collaborators` TEXT COMMENT 'Current collaborators as JSON array of email addresses' AFTER `comments`;

ALTER TABLE `users` ADD `collaborator` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'User is a collaborator when set to non zero value' AFTER `user_perms`;

ALTER TABLE `users` DROP COLUMN `form_manager`;