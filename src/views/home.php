<?php require_once('config/appconfig.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo Config::SiteTitleLong; ?></title>

    <?php require_once('./includes/headlinks.php'); ?>
</head>

<body>
    <div id="wrapper">

        <!-- Navigation -->
        <?php require_once('./includes/navbar.php') ?>
        <!-- Page Content -->
        <?php require_once('./includes/pageindex.php') ?>
<?php //echo "permissions are ".$_SESSION['user_perms']; ?>
    </div>
    <!-- /#wrapper -->

    <?php require_once('./includes/jsbs.php'); ?>
</body>

</html>
