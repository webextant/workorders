<?php
    // Need to setup dependancy items for navbar.php since this is not using _page_processor yet. 
	$folder = null; // set default value or error in navbar. XDebug
    require_once "./resources/library/appinfo.php";
    $appInfoDbAdapter = new AppInfo($dsn, $user_name, $pass_word);
    $system_version =$appInfoDbAdapter->Get('System Version');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DISD - Workorders</title>

    <?php require_once('./includes/headlinks.php'); ?>
    <?php require_once('./includes/headlinksfb.php'); ?>
    <?php require_once('./includes/headlinkstagit.php'); ?>
</head>

<body>
  <?php require_once('./includes/jsbs.php'); ?>
  <?php require_once('./includes/jsfb.php'); ?>
  <?php require_once('./includes/jstagit.php'); ?>
  <?php require_once('./includes/jsjqvalidation.php'); ?>

    <div id="wrapper">

        <!-- Navigation -->
        <?php require_once('./includes/navbar.php') ?>
        <!-- Page Content -->
        <?php require_once('./includes/pageformsbuild.php') ?>

    </div>
    <!-- /#wrapper -->

</body>

</html>
