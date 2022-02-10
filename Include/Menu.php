
<div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->
<div class="sidebar-wrapper">
            <div class="logo" style="text-align: center;"><script type='text/javascript'>document.ondragstart = function () { return false; }; </script>
                <img src="images/icons/K-Group3.png" style='width: 95%; object-fit: contain'/>
            </div>
            <ul class="nav">
                <li>
                    <a href="Management.php">
                        <i><img src="images/icons/Restaurant2.png" width="95%"></i>
                        <p>Manage Restaurant</p>
                    </a>
                </li>
                <li><?php if($_SESSION["Userlevel"]=="Admin"){?>
                    <a href="ManageAdmin.php">
                        <i><img src="images/icons/Admin.png" width="95%"></i>
                        <p>Manage Admin</p>
                    </a>
                    <?php } ?>
                </li>
                <li>
                    <a href="GuestList.php">
                        <i><img src="images/icons/Guest.png" width="100%"></i>
                        <p>Guest List</p>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="ti-back-right"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
    	</div>
 </div>
    <div class="main-panel">
		<nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">KGROUP RESTAURANT</a>
                </div>
                <div class="collapse navbar-collapse">
                </div>
            </div>
        </nav>