<?php require_once('./resources/appconfig.php') ?>
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
        <a class="navbar-brand" href="index.php"><img src="./IMG/workorder.png" width="30px" style="float:left; margin-right:20px;"> <?php echo Config::SiteTitleShort; ?></a>
    </div>
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
                <a href="?I=<?php echo pg_encrypt("WORKORDER-create",$pg_encrypt_key,"encode"); ?>"><i class="fa fa-fw fa-file"></i> New Workorder</a>
            </li>
            <!-- Approver Center -->    
            <li  > <a style="color:#0DE447" tabindex="-1" href="javascript:;" data-toggle="collapse" data-target="#APPROVERS"><i class="fa fa-fw fa-thumbs-up"></i>Approver Center<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="APPROVERS" class="<?php if($active_nav_panel == "Approval_Center") echo "show"; else echo "collapse"; ?>">
                    <li> <a href="#"><i class="fa fa-fw fa-thumbs-o-up"></i>Needs Approved</a> </li>
                    <li> <a href="#"><i class="fa fa-fw fa-arrow-right"></i>Approvals In Progress</a> </li>
                    <li> <a href="#"><i class="fa fa-fw fa-check"></i>Approved / Closed</a> </li>
                    
                </ul>
            </li> 
            
            <!-- Admin Center -->    
            <li  > <a style="color:#F0FF00" tabindex="-1" href="javascript:;" data-toggle="collapse" data-target="#ADMIN"><i class="fa fa-fw fa-cog"></i>Admin Center<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="ADMIN" class="<?php if($active_nav_panel == "Admin_Center") echo "show"; else echo "collapse"; ?>">
                    <li> <a href="forms.php"><i class="fa fa-fw fa-edit"></i>Form Builder</a> </li>
                    <li> <!-- <a  tabindex="-1" href="#">User List </a> --></li>
                    <li id="settingsNavbarItem">
                    <!-- <a href="settings.php"><i class="fa fa-fw fa-wrench"></i> Settings</a> -->
                    </li> 
                </ul>
            </li>    
        </ul>
    </div>
   
    <!-- /.navbar-collapse  $_SESSION['user_perms']-->
</nav>
