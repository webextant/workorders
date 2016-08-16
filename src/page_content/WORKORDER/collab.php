<?php
/************************************************************************************************
Work with collaborators on a work item
Author: Raymond Brady
Date Created: 8/16/2016
************************************************************************************************/
    require_once('./resources/library/workorder.php');

    $woId = $header_GET_array[0];
    $approveKey = $header_GET_array[1];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word);
    $wo = $woDbAdapter->Select($woId);
    $woViewModel = new WorkorderViewModel($wo, $approveKey);

?>
<h4>Collaborate</h4>