/**
* May 25, 2016
* Required DB changes for new group workflow feature
*/

ALTER TABLE `formsdb`.`FormDefinitions`
ADD GroupWorkflows TEXT COLLATE utf8_unicode_ci default '{}' COMMENT 'Group workflow definitions in serialized JSON format';

ALTER TABLE `formsdb`.`users`
ADD user_group varchar(64) COLLATE utf8_unicode_ci NOT NULL default 'New Users' COMMENT "group name user belongs to";
ADD form_manager boolean COLLATE utf8_unicode_ci NOT NULL default 0 COMMENT "user can manage forms";