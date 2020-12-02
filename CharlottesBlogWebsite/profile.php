<?php
    define("TITLE", "Profile | Charlotte's blog");
    include 'includes/header.php';
    include 'includes/main.php';
    include 'includes/nav.php';
    check_loggedin($con);
?>
            
           
        </ul>
    </div><!-- nav -->
    <div id="content">
        <div id="profile-content">
            <h1>Profile page</h1>
        </div><!-- profile-content -->
        <div id="profile-details">
            <p>Welcome back, <?=$_SESSION['name']?></p>
        </div><!-- profile-details -->
    </div><!-- content -->

<?php
    include 'includes/footer.php';
?>