<?php
    define("TITLE", "Forgot Password | Charlotte's Blog");
    include 'includes/header.php';
    include 'includes/nav.php';
    include 'includes/main.php';
    // Output message
    $msg = '';

    // Now we check if the data from the login from was submitted, isset() will check if the data exists.
    if (isset($_POST['email'])) {
        // Prepare our SQL, this will prevent SQL injection
        $stmt = $con->prepare('SELECT * FROM user WHERE email = ?');
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        $account = $result->fetch_array(MYSQLI_ASSOC);
        $stmt->close();
        // If the account exists with the email
        if ($account) {
            // Account exist, the $msg variable will show the output message
            // Update the reset code in the database
            $uniqid = uniqid();
            $stmt = $con->prepare('UPDATE user SET reset = ? WHERE email = ?');
            $stmt->bind_param('ss', $uniqid, $_POST['email']);
            $stmt->execute();
            $stmt->close();
            // Email to send below
            // Change "Your Company Name" and "yourdomain.com", do not remove the <> 
            $from = 'Your Company Name <noreply@yourdomain.com>';
            $subject = 'Password Reset';
            $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'Return-Path: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            // Change the link below from "yourdomain.com" to your own domain name where the php login system is hosted
            $reset_link = 'http://yourdomain.com/phplogin/resetpassword.php?email=' . $_POST['email'] . '&code=' . $uniqid;
            // Email message, customize as you see fit
            $message = '<p>Please click the following link to reset your password: <a href="' . $reset_link . '">' . $reset_link . '</a></p>';
            // Send email to the user
            mail($_POST['email'], $subject, $message, $headers);
            $msg = 'Reset password link has been sent to your email';
        } else {
            $msg = 'We donot have an account with that email';
        }
    }
?>

        </ul>
    </div><!-- nav -->
    <div id="content">
        <div id="login-form">
            <h1>Forgot Password</h1>
            <form action="forgotpassword.php" method="post">
                <label for="email"><b>Email</b></label>
                <input type="email" name="email" placeholder="Enter your email" id="email" required>
                <div class="msg"><?=$msg?></div>
                <input class="general-btn" type="submit" value="Submit">
            </form>
        </div><!-- login-form -->
    </div><!-- content -->
    
    <?php
        include 'includes/footer.php';
    ?>