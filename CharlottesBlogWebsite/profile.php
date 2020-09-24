<?php
    define("TITLE", "Profile | Charlotte's blog");
    include 'includes/header.php';
    include 'includes/main.php';
    check_loggedin($con);
?>
            <li class="last"><a href="logout.php">Logout</a></li>
            <?php if ($_SESSION['role'] == 'Admin'): ?>
            <li class="last"><a href="admin/index.php" target="_blank">Admin</a></li>
            <?php endif; ?>
            <li class="last"><a href="account.php">Account</a></li>
            <li class="last"><input type="text" placeholder="Search..."></li>
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
    include("includes/footer.php");
?>