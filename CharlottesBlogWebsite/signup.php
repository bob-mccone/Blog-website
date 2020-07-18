<?php
    define("TITLE", "Sign up | Charlotte's Blog");
    include('includes/header.php');
?>
            <li class="last"><a href="login.php">Login</a></li>
            <li class="last"><input type="text" placeholder="Search..."></li>
        </ul>
    </div><!-- nav -->
    <div id="content">
        <div id="register-form">
            <h1>Please register to continue</h1>
            <form action="register.php" method="post" autocomplete="off">
                <label for="username"><b>Username</b></label>
                <input type="text" name="username" placeholder="Enter your username" id="username" required>
                <label for="password"><b>Password</b></label>
                <input type="password" name="password" placeholder="Enter your password" id="password" required>
                <label for="email"><b>Email</b></label>
                <input type="email" name="email" placeholder="Enter your email" id="email" required>
                <input type="submit" value="Register">
            </form>
        </div><!-- register_form -->
    </div><!-- content -->

<?php
    include('includes/footer.php');
?>