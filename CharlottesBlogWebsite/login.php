<?php
    // Note: This needs to be in ()
    define("TITLE", "Login | Charlotte's Blog");
    // Note: Doesn't need () as it is not a function
    include 'includes/header.php';
    include 'includes/main.php';
    include 'includes/nav.php';

    // No need for the user to see the login form if they are logged in so redirect them to the profile page
    if (isset($_SESSION['loggedin'])) {
        // If the user is logged in redirect them to the profile page
        header('Location: profile.php');
        exit;
    }

    // Also check if they are "remembered"
    if (isset($_COOKIE['remember_me']) && !empty($_COOKIE['remember_me'])) {
        // If the remember me cookie matches one in the database then we can update the session variables
        $stmt = $con->prepare('SELECT id, username, role FROM user WHERE remember_me = ?');
        $stmt->bind_param('s', $_COOKIE['remember_me']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Found a match
            $stmt->bind_result($id, $username, $role);
            $stmt->fetch();
            $stmt->close();
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;
            header('Location: profile.php');
            exit;
        } else {
            echo 'Incorrect username and/or password combination';
        }
    }
    // Creates a random token, prevents cross-site request forgery attacks
    // $_SESSION['token'] = md5(uniqid(rand(), true));
?>
            
        </ul>
    </div><!-- nav -->
    <div id="content">
        <div id="login-form">
            <h1>Please login to continue</h1>
            <form action="includes/authenticate.php" method="post">
                <!-- Username label -->
                <label for="username"><b>Username</b></label>
                <!-- Username textbox -->
                <input type="text" placeholder="Enter your username" name="username" id="username" required>
                <!-- Password label -->
                <label for="password"><b>Password</b></label>
                <!-- Password textbox -->
                <input type="password" placeholder="Enter your password" name="password" id="password" required>
                <!-- Remember me checkbox -->
                <input type="checkbox" name="rememberme" id="rememberme">
                <!-- Remember me label -->
                <label for="rememberme">Remember Me</label>
                <!-- Remember me -->
                <a href="forgotpassword.php">Forgot Password?</a>
                <!-- Submit button -->
                <button type="submit">Login</button>
            </form><!-- form -->
        </div><!-- login-form -->
        <!-- Inputs a hidden token for cross-site request forgery -->
        <!-- <input type="hidden" name="token" value="<?#=$_SESSION['token']?>"> -->
        <!-- Message -->
        <div class="msg"></div>
    </div><!-- content -->
    <script>
        document.querySelector("#login\-form form").onsubmit = function(event) {
            event.preventDefault();
            var form_data = new FormData(document.querySelector("#login\-form form"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", document.querySelector("#login\-form form").action, true);
            xhr.onload = function () {
                if (this.responseText.toLowerCase().indexOf("success") !== -1) {
                    window.location.href = "profile.php";
                } else {
                    document.querySelector(".msg").innerHTML = this.responseText;
                }
            };
            xhr.send(form_data);
        };
    </script>
<?php
    include 'includes/footer.php';
?>