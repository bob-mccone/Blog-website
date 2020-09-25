<?php
    define("TITLE", "Account | Charlotte's blog");
    include 'includes/header.php';
    include 'includes/main.php';
    check_loggedin($con);

    // Output messages (errors, etc)
    $msg = '';

    // We don't have the password or email info stored in sessions so instead we can get the results from the database
    $stmt = $con->prepare('SELECT password, email, activation_code, role FROM user WHERE id = ?');
    // In this case we can use the account ID to get the account info
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($password, $email, $activation_code, $role);
    $stmt->fetch();
    $stmt->close();

    // Handle edit profile post data
    if (isset($_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['email'])) {
        // Make sure the submitted registration values are not empty.
        if (empty($_POST['username']) || empty($_POST['email'])) {
            $msg = 'The input fields must not be empty';
        } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $msg = 'Please provide a valid email address';
        } else if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
            $msg = 'Username must contain only letters and numbers';
        } else if (!empty($_POST['password']) && (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5)) {
            $msg = 'Password must be between 5 and 20 characters long';
        } else if ($_POST['confirm_password'] != $_POST['password']) {
            $msg = 'Passwords do not match';
        }
        if (empty($msg)) {
            // Check if new username or email already exists in database
            $stmt = $con->prepare('SELECT * FROM accounts WHERE (username = ? OR email = ?) AND username != ? AND email != ?');
            $stmt->bind_param('ssss', $_POST['username'], $_POST['email'], $_SESSION['name'], $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $msg = 'Account already exists with that username and/or email';
            } else {
                // No errors occured, update the account...
                $stmt->close();
                $uniqid = account_activation && $email != $_POST['email'] ? uniqid() : $activation_code;
                $stmt = $con->prepare('UPDATE accounts SET username = ?, password = ?, email = ?, activation_code = ? WHERE id = ?');
                // WE do not want to expose passwords in our database. so hash the password and use password_verify when a user logs in
                $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $password;
                $stmt->bind_param('ssssi', $_POST['username'], $password, $_POST['email'], $uniqid, $_SESSION['id']);
                $stmt->execute();
                $stmt->close();
                // Update the session variables
                $_SESSION['name'] = $_POST['username'];
                if (account_activation && $email != $_POST['email']) {
                    // Account activation required, send the user the activation email with the "send_activation_email" function from the "main.php" file
                    send_activation_email($Post['email'], $uniqid);
                    // Log the user out
                    unset($_SESSION['loggedin']);
                    $msg = 'You have changed your email address, you need to re-activate your account';
                } else {
                    // Profile update redirect the user back to the account page
                    header('Location: account.php');
                    exit;
                }
            }
        }
    }
?>

            <li class="last"><a href="logout.php">Logout</a></li>
            <?php if ($_SESSION['role'] == 'Admin'): ?>
            <li class="last"><a href="admin/index.php" target="_blank">Admin</a>
            <?php endif; ?>
            <li class="last"><a href="profile.php">Profile</a></li>
            <li class="last"><input type="text" placeholder="Search..."></li>
        </ul>
    </div><!-- nav -->
    <?php if (!isset($_GET['action'])): ?>
    <div id="content">
        <div id="account-content">
            <h1>Account page</h1>
        </div><!-- account-content -->
        <div id="account-details">
            <p>Your account details are below:</p>
            <table>
                <tr>
                    <td class="bold">Username:</td>
                    <td class="gap"><?=$_SESSION['name']?></td>
                </tr>
                <tr>
                    <td class="bold">Email:</td>
                    <td class="gap"><?=$email?></td>
                </tr>
                <tr>
                    <td class="bold">Role:</td>
                    <td class="gap"><?=$role?></td>
                </tr>
            </table>
            <a class="general-btn" href="account.php?action=edit">Edit Details</a>
        </div><!-- account-details -->
    </div><!-- content -->
    <?php elseif ($_GET['action'] == 'edit'): ?>
    <div class="edit-content">
        <h1>Edit profile details</h1>
        <div id="edit-details">
            <form action="account.php?action=edit" method="post">
                <label for="username">Username:</label>
                <label for="username_value"><?=$_SESSION['name']?></label>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                <label for="email">Email:</label>
                <input type="email" value="<?=$email?>" name="email" id="email" placeholder="Email">
                <br>
                <input class="general-btn" type="submit" value="Save">
                <p><?=$msg?></p>
            </form>
        </div><!-- edit-details -->
    </div><!-- edit-content -->
    <?php endif; ?>

<?php include 'includes/footer.php'; ?>