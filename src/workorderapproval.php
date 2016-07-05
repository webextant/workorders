<!DOCTYPE html>

<html lang="en">



<head>



    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">

    <meta name="author" content="">



    <title>DISD - Workorder Approval</title>



    <?php require_once('./includes/headlinks.php'); ?>

</head>



<body>



    <div id="wrapper">


<?php
/*
should we put a check if $_SESSION['user_email'] == ''

if so the page either redirects to index.php or simply an if statement so 
navbar and pageindex don't show?
*/

?>
        <!-- Navigation -->

        <?php require_once('./includes/navbar.php') ?>

        <!-- Page Content -->

        <?php require_once('./includes/pageindex.php') ?>



    </div>

    <!-- /#wrapper -->



    <?php require_once('./includes/jsbs.php'); ?>

</body>



</html>

