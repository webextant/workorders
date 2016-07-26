# CHANGE LOG
**16 July 2016**

* Fix: Fixed the query section of the update user page.  Now you can update user information
	* Need to check on injection security here
* Fix: Added functionality in $QUERY_PROCESS_SUB
	* NOW if you: return "ERROR|$MESSAGE" Query process will display an error and the custom message you want to send
	* IF you do not include the |$messasge it will just display a generic error
	* Pages updated users.php groups.php (other classes may still need to be updated to show errors correctly)

**15 July 2016**

* Improvement: Added Edit button to workorder submit page
* Improvement: Created a way to delete Groups (DELETE updates users in that group to another group as selected)
* Improvement: Added User Group to the edit user page.  Submit is still broke
* Improvement: Added User Group section to the User List under Admin
* Fix: Fixed the Edit group section

**11 July 2016**

* Update login script to restrict non-authorized domains
* Update menu so it does not show if not logged in
* Created 404.php error page.
* Created Groups list where existing user groups can be viewed
* Created New Group Page where admins can add new groups to the system (need to adjust security policy in Class groups.php)

**7 July 2016**

* Fix: Fixed menu issue when not on index.php
* Improvement: Added a domain limiter section for admins that will be used to prevent non-authorized email addresses from registering for the system. (the later part has not been implemented but the databse and update form has been completed)
* Improvement: Updated appinfo table in DB.  Set INFO_request to a unique field
* Improvement: Created AppInfo class to handle database gets and sets to that table
* set up sections allowing and Admin or Super user to see and approve items  that are still waiting (PendingApproval)

**6 July 2016**

* Fix: Fixed form.php post location error on form bulder
* Fix: Updated links to approver page
* Improvement: Incorporated post_type hidden input on form builder so POSTs are routed  through dbquery and provide success / failure feedback on submits
* Improvement: incorporated RegDomin item into appinfo (INFO_request) field allowing admin to limit registration from specific domains
* Improvement: updated navbar  to incorporate access permissions 
* Improvement: Added First and Last Name fields to user table

**29 June 2016**

* Improvement: Added DataTables JS and CSS to corresponding folders (js,css,img)
* Improvement: created a User List section under admin
	* Generated user list on screen
* Improvement: Updated UserDataAdaptor to include dynamic jquery based function
	* Modified function to allow with or without WHERE statement
* Improvement: Created form Builder inside of page_content/ADMIN/forms_admin.php
	* depreciated old page
* Improvement: Created APPINFO table in databse for app specific information
	* inserted a field with ACTION of Version and Value of 1.0.0
* Improvement: Moved jquery.js reference to header in order to accomodate datatables functions for header_xxx.php files

**25 June 2016**

* Fix: Fixed error where I was “echo $sql” login.php
* Fix: Removed echo “permissions are “.$_SESSION[‘user_perms’]” Was testing to make sure user permissions were being stored home.php
* Improvement: Adjusting links to large buttons pageindex.php 
* Improvement: Removed listing of all work orders for every user pageindex.php
* Security: Applied user permissions pageindex.php
* Improvement: Added functions to WorkorderDataAdapter Class to handle custom queries.
 * This is for user-based / permission-based queries of workorders
 * Users can see their own work orders and status
 * Approvers can see items that need approval with links
* Improvement: Began adding new encryption script. navbar.php
* Improvement: index.php - Instead of including includes/home.php I now just incluce _page_processor.php
 * This file includes several references including the new 
   * dbquery
     * dbquery/QUERY_PROCESS
     * dbquery/QUERY_PROCESS_SUB
   * /page_content/index.php
   * /page_content/header.php
* Security: appconfig.php - add the encrypting includes here including
 * A variable named $pg_encrypt_key
 * And an includes /page_encryption.php
 * These had to be added here because of the changes I am making to the navbar.php.  Pages that did not have the new includes would break because the navbar ware looking for a function that did not exist.  By adding it here it is now being referenced on every page
* Improvement: createworkorder.php - This page is still functional but is no longer needed.  I have recreated it using the page encryption method
 * NOTE:  the page at /page_contents/WORKORDER/create.php is basically just including elements from createworkorder.php including 
  * Resources/librarty/dot.php
  * Includes/pageworkordersnew.php

**11 June 2016**

* Updated htaccess files to make php libraries inaccessible from browser.

* Added a Readme.md file

* Final approver now completes the work order and status is set to ApproveClosed.

**5 June 2016**

* Add support for user groups. Each user group can have a workflow defined within a form definition. When a new work order is created from the form definition the current users group workflow is merged with the main (district) workflow (main work flow is appended to the group workflow). This provides a way for each user group to have a group workflow that is processed before the main workflow. Since the main workflow is appended it is a global workflow (within the spcific form definition) and will be processed for all work order submissions based on the form definition.

* There are database updates that need to be applied if you have a previous install. See _update folder for details.

**16 May 2016**

* User in the approvers list who also submits work order will cause current approver to be the next approver after them in the approver list. Previous approvers are skipped since current user has approver status above previous approvers.

* The approvers update link that is generated can be viewed and allows updates without logging in. The approver key rotates for each new approver ensuring only one update per approver using the approver link.