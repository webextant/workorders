<?php require_once('config/appconfig.php') ?>
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php"><img src="./IMG/workorder.png" width="30px" style="float:left; margin-right:20px;"> <?php echo Config::SiteTitleShort." - version: "; ?><span style="color:#F98D04;"><?php echo $system_version['INFO_value']; ?></span></a>
    </div>
    
    <?php
	if ($login->isUserLoggedIn() == true) {
	?>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <?php if($_SESSION['user_name'] == "") { $navDisplayName = ""; } else { $navDisplayName = $_SESSION['user_name'] . " (" . $_SESSION['user_email'] . ")"; } ?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $navDisplayName; ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#"><i class="fa fa-fw fa-group"></i> <?php echo $_SESSION['user_group']; ?></a>
                </li>
                <li>
                    <a href="?logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
   
    
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li id="homeNavbarItem">
                <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Home</a>
            </li>
            <li id="newWorkorderNavbarItem">
                <a href="index.php?I=<?php echo pg_encrypt("WORKORDER-create",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-file"></i>&nbsp;New Workorder</a>
            </li>
            <li>
                <a href="index.php?I=<?php echo pg_encrypt("COLLAB-current_collab",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-users"></i>&nbsp;Needs Your Help</a>
            </li>
            <!-- Approver Center -->    
            <li><a style="color:#0DE447" tabindex="-1" href="javascript:;" data-toggle="collapse" data-target="#APPROVAL"><i class="fa fa-fw fa-thumbs-up"></i>&nbsp;Approval Center<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="APPROVAL" class="<?php if($folder == "APPROVAL") echo "show"; else echo "collapse"; ?>">
                    <?php
					   if($_SESSION['user_perms'] <=2){
					?>
                    <li><a href="index.php?I=<?php echo pg_encrypt("APPROVAL-needs_approval",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-thumbs-o-up"></i>&nbsp;Needs Approved</a> </li>
                    <?php
					   }
					?>
                    
                    <li><a href="index.php?I=<?php echo pg_encrypt("APPROVAL-pending_approval",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-arrow-right"></i>&nbsp;Approvals In Progress</a> </li>
                    <li><a href="index.php?I=<?php echo pg_encrypt("APPROVAL-closed_approval",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-check"></i>&nbsp;Closed / Workorders</a> </li>
                    
                </ul>
            </li> 
            <?php
			   if($_SESSION['user_perms'] == 1){
			?>
            <!-- Admin Center -->    
            <li><a style="color:#F0FF00" tabindex="-1" href="javascript:;" data-toggle="collapse" data-target="#ADMIN"><i class="fa fa-fw fa-cog"></i>&nbsp;Admin Center<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="ADMIN" class="<?php if($folder == "ADMIN") echo "show"; else echo "collapse"; ?>">
                    <li> <a href="index.php?I=<?php echo pg_encrypt("ADMIN-forms_admin",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-edit"></i>&nbsp;Form Builder </a> </li>
                    <li> <a  tabindex="-1"href="index.php?I=<?php echo pg_encrypt("ADMIN-list_user",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-user"></i>&nbsp;Users </a> </li>
                    <li> <a  tabindex="-1"href="index.php?I=<?php echo pg_encrypt("ADMIN-list_groups",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-user"></i>&nbsp;Groups </a> </li>
                    <li id="settingsNavbarItem">
                    <!-- <a href="settings.php"><i class="fa fa-fw fa-wrench"></i> Settings</a> -->
                    </li> 
                </ul>
            </li> 
            
            <li><a style="color:#F0FF00" tabindex="-1" href="javascript:;" data-toggle="collapse" data-target="#SETTINGS"><i class="fa fa-fw fa-cog"></i>&nbsp;Settings Center<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="SETTINGS" class="<?php if($folder == "SETTINGS") echo "show"; else echo "collapse"; ?>">
                    <li> <a href="index.php?I=<?php echo pg_encrypt("SETTINGS-registration_settings",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-cog"></i>&nbsp;Registration Settings</a> </li>
                </ul>
            </li>  
            
            <?php
			   }
			?>  
        </ul>
    </div>
 <?php
	}else{
	?>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>&nbsp;ACCOUNT<b class="caret"></b></a>
            <ul class="dropdown-menu">
               
                <li>
                    <a href="index.php"><i class="fa fa-fw fa-power-off"></i>&nbsp;Log In</a>
                </li>
            </ul>
        </li>
    </ul>
    <?php	
	}
	?>   
    <!-- /.navbar-collapse  $_SESSION['user_perms']-->
</nav>
