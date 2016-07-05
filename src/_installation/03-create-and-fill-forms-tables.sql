CREATE TABLE IF NOT EXISTS `formsdb`.`FormDefinitions` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'auto incrementing id for each form definition. Unique index',
    FormName VARCHAR( 30 ) COMMENT 'Friendly name of the form',
    Description VARCHAR( 150 ) COMMENT 'Friendly description of the form',
    FormXml TEXT COMMENT 'Base64 encoded XML data from formbuilder.online',
    Workflow TEXT COMMENT 'System wide workflow. CSV email addresses',
    GroupWorkflows TEXT COMMENT 'Workflow definitions for groups in serialized JSON format',
    notifyOnFinalApproval TEXT COMMENT 'CSV list of emails to notify on final approval',
    Available BOOLEAN DEFAULT 0 COMMENT 'Form available for publishing'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='form definition data';
    
CREATE TABLE IF NOT EXISTS `formsdb`.`Approvers` (
    email VARCHAR( 320 ) PRIMARY KEY COMMENT 'Approvers email address',
    name VARCHAR( 100 ) COMMENT 'Approvers name'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores contact info for approvers';

CREATE TABLE IF NOT EXISTS `formsdb`.`Workorders` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'auto incrementing id for each work order. Unique index',
    formId INT(6) UNSIGNED COMMENT 'id of the form definition used to create the workorder',
    formName VARCHAR( 60 ) COMMENT 'Fiendly name from the the form definition',
    description VARCHAR( 150 ) COMMENT 'Friendly description from the form definition',
    formXml TEXT COMMENT 'XML data from the form definition used to create this work order',
    formData TEXT COMMENT 'Data submitted from the form',
    currentApprover VARCHAR( 320 ) COMMENT 'email address of the current approver',
    workflow TEXT COMMENT 'Group workflow + system workflow of approvers.',
    approveState VARCHAR( 25 ) COMMENT 'Current approve state',
    approverKey VARCHAR( 32 ) COMMENT 'Current approver key',
    viewOnlyKey VARCHAR( 32 ) COMMENT 'Current view only key',
    createdAt DATETIME COMMENT 'DateTime work order was created',
    updatedAt DATETIME COMMENT 'DateTime of last update',
    createdBy VARCHAR( 320 ) COMMENT 'user which created the work order',
    updatedBy VARCHAR( 320 ) COMMENT 'Last user which updated the work order',
    notifyOnFinalApproval TEXT COMMENT 'CSV list of email addresses to notify on final approval',
    comments TEXT ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Workorder data generated from form definitions';