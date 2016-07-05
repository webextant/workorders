<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DISD - New Workorder</title>

    <?php require_once('./includes/headlinks.php'); ?>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php require_once('./includes/navbar.php') ?>
        <!-- Workorder Forms Related Classes -->
        <?php require_once('./resources/library/dot.php') ?>
        <!-- Show available workorder forms for creating new workorders -->
        <?php require_once('./includes/pageworkordersnew.php') ?>

    </div>
    <!-- /#wrapper -->

    <?php require_once('./includes/jsbs.php'); ?>
</body>

</html>
