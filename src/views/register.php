<?php
/**************************************************
*** Author: Michael keough
*** Function: HTML and CSS login theme
*** Date modified 4-29-2016
***
***************************************************/
?>
<?php 
	require_once('config/appconfig.php');
    require_once "./resources/library/user.php";

    $userAdapter = new UserDataAdapter($dsn, $user_name, $pass_word);
    $userGroups = $userAdapter->SelectUniqueGroupNames();

?>
<!DOCTYPE html>
<html >
<head>
<meta charset="UTF-8">
<title><?php echo Config::SiteTitleLong; ?></title>
<link rel="stylesheet" href="./css/loginStyle.css">
</head>

<body>

<div class="login">
  <div class="login-screen">
    <div class="app-title">
      <h1>REGISTER</h1>
      <!-- Page Heading -->
      <div class="row">
        <div class="col-lg-12">
          <?php
			// show potential errors / feedback (from registration object)
			if (isset($registration)) {
				if ($registration->errors) {
					foreach ($registration->errors as $error) {
						echo "<div class='alert alert-danger'><i class='fa fa-info-circle'></i> " . $error . "</div>";
					}
				}
				if ($registration->messages) {
					foreach ($registration->messages as $message) {
						echo "<div class='alert alert-info'><i class='fa fa-info-circle'></i> " . $message . "</div>";
					}
				}
			}
			?>
        </div>
      </div>
    </div>
    <form method="post" action="">
    <div class="login-form">
    
    <!--  <a class="login-link" href="#">Lost your password?</a> -->
    
    <div class="col-lg-4">
    <!-- register form -->
    <form method="post" action="register.php" name="registerform">
      <div class="form-group"> 
        <!-- the user name input field uses a HTML5 pattern check -->
        <div class="control-group">
          <input id="login_input_username" class="form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Username" required />
          <br>
          <sub>
          <label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label>
          </sub> </div>
        
        <div class="control-group">
          <input id="login_first_name" class="form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_first_name" placeholder="First Name" required />
          <br>
          <sub>
          </sub> </div>
          
          <div class="control-group">
          <input id="login_last_name" class="form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_last_name" placeholder="Last Name" required />
          <br>
          <sub>
          </sub> </div>
        
        <!-- the email input field uses a HTML5 email type check -->
        <div class="control-group">
          <input id="login_input_email" class="form-control" type="email" name="user_email" placeholder="Email Address" required />
          <label class="login-field-icon fui-user" for="login-name"></label>
        </div>
        <div class="control-group">
          <input id="login_input_password_new" class="login-field form-control" type="password" placeholder="Password" name="user_password_new" pattern=".{6,}" required autocomplete="off" /><br>
<sub>(min. 6 characters)</sub>
          <label class="login-field-icon fui-user" for="login-name"></label>
        </div>
        <div class="control-group">
          <input id="login_input_password_repeat" class="form-control" type="password" placeholder="Repeat Password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
        </div>
        
      
               <div class="control-group">
<hr>
          <label class="login-field-icon fui-user" for="login-name"><strong>Select Your Department</strong></label>

        <select id="register_group_list" class="form-control" name="user_group_list">
                        <?php
                            // Build the group tabs
                            foreach ($userGroups as $key => $group) {
                                echo '<option value="'.$group->name . '">' . $group->name . '</option>';
                            }
                        ?>
         </select>
         </div>
        <input class="btn btn-success" type="submit"  name="register" value="Register" />
        <br>
      </div>
    </form>
    
    <!-- backlink --> 
    
    <a  style="background:#C58033" class="btn btn-green btn-large " href="index.php" />BACK</a> </div>
</div>
<div class="col-lg-4"></div>
<?php require_once('./includes/jsbs.php'); ?>

</body>
</html>
