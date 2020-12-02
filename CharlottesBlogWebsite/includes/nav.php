                <?php if (!isset($_SESSION['loggedin'])): ?>
                    <li class="last"><a href="signup.php">Sign up</a></li>
                    <li class="last"><a href="login.php">Login</a></li>
                    <li class="last"><input type="text" placeholder="Search..."></li>
                <?php elseif ($_SESSION['loggedin']): ?>
                    <li class="last"><a href="logout.php">Logout</a></li>
                    <?php if ($_SESSION['role'] == 'Admin'): ?>
                        <li class="last"><a href="admin/index.php" target="_blank">Admin</a></li> 
                    <?php endif; ?>
                    <li class="last"><a href="account.php">Account</a></li>
                    <li class="last"><a href="profile.php"><?=$_SESSION['name']?></a></li>
                    <li class="last"><input type="text" placeholder="Search..."></li>
                <?php endif; ?>
 