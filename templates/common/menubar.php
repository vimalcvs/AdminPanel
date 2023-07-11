<?php include 'includes/config.php' ?>
<?php require_once 'roles.php'; ?>
<?php include_once('header.php'); ?>


<!-- START MAIN -->
<div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">

        <!-- START LEFT SIDEBAR NAV-->
        <aside id="left-sidebar-nav">
            <ul id="slide-out" class="side-nav fixed leftside-navigation">
                <li class="user-details deep-orange darken-2" style="padding-left:0px;">
                    <div>
                        <center>
                            <img src="assets/images/ic_launcher.png" class="z-depth-1" width="100px" height="100px">
                        </center>
                    </div>
                </li>
                <li class="bold <?php echo $selectedMenu == "dashboard" ? 'active' : "";?>">
                    <a href="dashboard.php" class="waves-effect waves">
                        <i class="mdi-action-dashboard" dir="rtl"></i>Dashboard
                    </a>
                </li>
                
                <li class="bold <?php echo $selectedMenu == "category" ? 'active' : "";?>">
                    <a href="category.php" class="waves-effect waves">
                        <i class="mdi-action-dns"></i>Category
                    </a>
                </li>

                <li class="bold <?php echo $selectedMenu == "channel" ? 'active' : "";?>">
                    <a href="channel.php" class="waves-effect waves">
                        <i class="mdi-hardware-desktop-mac"></i>Chapter
                    </a>
                </li>

                <li class="bold <?php echo $selectedMenu == "notification" ? 'active' : "";?>">
                    <a href="notification.php" class="waves-effect waves">
                        <i class="mdi-social-notifications"></i>Push Notification
                    </a>
                </li>
				
                <li class="bold <?php echo $selectedMenu == "users" ? 'active' : "";?>">
                    <a href="users.php" class="waves-effect waves">
                        <i class="mdi-social-people"></i>Users
                    </a>
                </li>

                <li class="bold <?php echo $selectedMenu == "setting" ? 'active' : "";?>">
                    <a href="settings.php" class="waves-effect waves">
                        <i class="mdi-action-settings"></i>Settings
                    </a>
                </li>

                <li class="bold">
                    <a href="logout.php" class="waves-effect waves">
                        <i class="mdi-action-exit-to-app"></i>Logout
                    </a>
                </li>

            </ul>
            
            <a href="#" data-activates="slide-out"
               class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only darken-2"><i
                    class="mdi-navigation-menu"></i></a>
        </aside>
        <!-- END LEFT SIDEBAR NAV-->

        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <div id="api-key" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>Where I have to put my API Key?</h5>
                <hr>
                <ol>
                    <li>for security needed, Update <b>API_KEY</b> String value.</li>
                    <li>Open Android Studio Project.</li>
                    <li>Click <b>CHANGE API KEY</b> to generate new API Key.</li>
                    <li>go to app > java > yourpackage name > <b>Config.java</b>, and update with your own API Key. <img src="assets/images/api_key.jpg" width="640px" height="342px"></li>
                </ol>
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>

        <div id="server-key" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>Obtaining your Firebase Server API Key</h5>
                <hr>
                <p>Firebase provides Server API Key to identify your firebase app. To obtain your Server API Key, go to firebase console, select the project and go to settings, select Cloud Messaging tab and copy your Server key.</p>
                <img src="assets/images/fcm-server-key.jpg" >
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>


<script>
// Add active class to the current button (highlight it)
var header = document.getElementById("slide-out");
var btns = header.getElementsByClassName("bold");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
	//alert('Clicked: ' + current);
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>