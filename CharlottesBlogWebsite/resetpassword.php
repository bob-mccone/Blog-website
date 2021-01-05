<?php
    define("TITLE", "Reset Password | Charlotte's Blog");
    include 'includes/header.php';
    include 'includes/nav.php';
    include 'includes/main.php';

    // Output message
    $msg = '';

    // Now we check if the data from the login form was submitted, isset() checks if the data exists
    if (isset($_GET['email'], $_GET['code']) && !empty($_GET['code'])) {
        //Prepare the SQL, this prevents SQL injection
        $stmt = $con->prepare('SELECT * FROM user WHERE email = ? AND reset = ?');
        $stmt->bind_param('ss', $_GET['email'], $_GET['code']);
        $stmt->execute();
        $result = $stmt->get_result();
        $account = $result->fetch_array(MYSQLI_ASSOC);
        $stmt->close();
        // If the account exists with the email and code
        if ($account) {
            if (isset($_POST['newpassword'], $_POST['confirmpassword'])) {
                if (strlen($_POST['newpassword']) > 20 || strlen($_POST['newpassword']) < 5) {
                    $msg = 'Password must be between 5 and 20 characters long!';
                } else if ($_POST['newpassword'] != $_POST['confirmpassword']) {
                    $msg = 'Passwords must match, please try again';
                } else {
                    $stmt = $con->prepare('UPDATE user SET password = ?, reset = "" WHERE email = ?');
                    $password = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                    $stmt->bind_param('ss', $password, $_GET['email']);
                    $stmt->execute();
                    $stmt->close();
                    $msg = 'Your password has been reset, You can now <a href="index.php">Login</a>';
                }
            }
        } else {
            exit('Incorrect email and/or code');
        }
    } else {
        exit('Please provide the email and code');
    }
    
?>

        </ul>
    </div><!-- nav -->
    <div id="content">
        <div id="login-form">
            <h1>Reset Password</h1>
            <form action="resetpassword.php?email=<?=$_GET['email']?>&code=<?=$_GET['code']?>" method="post">
                <label for="newpassword"><b>New password</b></label>
                <input type="password" name="newpassword" placeholder="Enter new password" id="newpassword" required>
                <label for="confirmpassword"><b>Confirm password</b></label>
                <input type="password" name="confirmpassword" placeholder="Confirm new password" id="confirmpassword" required>
                <div class="msg"><?=$msg?></div>
                <input class="general-btn" type="submit" value="Submit">
            </form>
        </div><!-- login-form -->
    </div><!-- content -->

<?php
    include 'includes/footer/php';
?>