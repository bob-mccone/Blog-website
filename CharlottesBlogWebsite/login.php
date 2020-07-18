<?php
    define("TITLE", "Login | Charlotte's Blog");
    include('includes/header.php');
?>
            <li class="last"><a href="signup.php">Sign up</a></li>
            <li class="last"><input type="text" placeholder="Search..."></li>
        </ul>
    </div><!-- nav -->
    <div id="content">
        <div id="login-form">
            <h1>Please login to continue</h1>
            <form action="authenticate.php" method="post">
                <label for="username"><b>Username</b></label>
                <input type="text" placeholder="Enter your username" name="username" id="username" required>

                <label for="password"><b>Password</b></label>
                <input type="password" placeholder="Enter your password" name="password" id="username" required>

                <button type="submit">Login</button>
            </form><!-- form -->
        </div><!-- login-form -->
    </div><!-- content -->
<?php
    include('includes/footer.php');
?>