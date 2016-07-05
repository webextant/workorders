<?php
/**************************************************
*** Author: Michael keough
*** Function: HTML and CSS login theme
*** Date modified 4-29-2016
***
***************************************************/

// show potential errors / feedback (from login object)
$message_show = "";
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            $message_show = $error;
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            $message_show = $message;
        }
    }
}
?>
<?php require_once('./resources/appconfig.php') ?>

<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    
    
    
    
        <link rel="stylesheet" href="./css/loginStyle.css">

    
    
        <?php require_once('./includes/headlinks.php'); ?>

  </head>

  <body>

    <body>
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1>Login</h1>
                <h3><?php echo $message_show; ?></h3>
			</div>
                        <form method="post" action="index.php" name="loginform">
                <div class="login-form">
                    <div class="control-group">
                        <input id="login_input_username" class="form-control" type="text" name="user_name" placeholder="Username" required />
                        <label class="login-field-icon fui-user" for="login-name"></label>
                    </div>
        
                    <div class="control-group">
                        <input id="login_input_password" class="form-control" type="password" name="user_password" placeholder="Password" autocomplete="off" required />
                        <label class="login-field-icon fui-lock" for="login-pass"></label>
                       
                    </div>
    
                    <input type="submit" name="login" class="btn btn-primary btn-large btn-block" value="login" /><br>

                    <a  style="background:#4D9017" class="btn btn-green btn-large " href="register.php" />REGISTER</a>
                  <!--  <a class="login-link" href="#">Lost your password?</a> -->
                </div> 
            </form
		</div>
	</div>
</body>
    
    
    
    
    
  </body>
</html>





<?php
/*
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo $error;
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo $message;
        }
    }
}
?>

<?php require_once('./resources/appconfig.php') ?>
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


        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-info-circle"></i> Please log in to continue
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <!-- Login Row -->
                <div class="row">
                    <div class="col-lg-5"></div>
                    <div class="col-lg-2">
                        <!-- login form box -->
                        <form method="post" action="index.php" name="loginform">

                            <div class="form-group">
                                <label for="login_input_username">Username</label>
                                <input id="login_input_username" class="form-control" type="text" name="user_name" required />
                            </div>

                            <div class="form-group">
                                <label for="login_input_password">Password</label>
                                <input id="login_input_password" class="form-control" type="password" name="user_password" autocomplete="off" required />
                            </div>
                            <input class="btn btn-success" type="submit" name="login" value="Log in" />

                        </form>

                        <hr/>
                        <a class="btn btn-primary" href="register.php">Register</a>
                    </div>
                    <div class="col-lg-5"></div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->



    </div>
    <!-- /#wrapper -->

    <?php require_once('./includes/jsbs.php'); ?>
</body>

</html>
*/