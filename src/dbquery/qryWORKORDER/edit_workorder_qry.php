<?php
require_once('./resources/library/workorder.php');
require_once('./resources/library/pacman.php');

$element = "Workorder";
$element_function = "Updated";

//Define Variables for the form
$formPostHandler = new Pacman($_POST);

// Do stuff then update the data
$QUERY_PROCESS = ''//$appInfoDbAdapter->Set("RegDomain", $domain_list);

?>

<div class="hidden">Workorder Data Was Valid: <?=$formPostHandler->InputIsValid()?></div>
<div class="hidden">Form ID: <?=$formPostHandler->formId?></div>
<div class="hidden">Form Name: <?=$formPostHandler->formName?></div>
<div class="hidden">Form Description: <?=$formPostHandler->formDescription?></div>