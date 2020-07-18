<?php
    // We need to use sessions, so you should always start sessions using 
    // the below code
    session_start();

    // If the user is not logged in, redirect them to the login page
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit;
    }
    define("TITLE", "Profile | Charlotte's blog");
    include("includes/header.php");
?>
            <li class="last"><a href="logout.php">Logout</a></li>
            <li class="last"><a href="account.php">Account</a></li>
            <li class="last"><input type="text" placeholder="Search..."></li>
        </ul>
    </div><!-- nav -->
    <div id="content">
        <div class="profile-content">
            <h1>Profile page</h1>
            <p>Welcome back, <?=$_SESSION['name']?></p>
        </div><!-- profile-content -->
    </div><!-- content -->

<?php
    include("includes/footer.php");
?>