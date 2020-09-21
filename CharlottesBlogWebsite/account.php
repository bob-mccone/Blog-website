<?php
    // We need to use sessions, so you should always start sessions 
    // using the below code
    session_start();

    // If the user is not logged in redirect to the login page
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit;
    }

    $DB_host = 'localhost';
    $DB_user = 'root';
    $DB_pass = 'mysql';
    $DB_name = 'charlottesblog';

    $con = mysqli_connect($DB_host, $DB_user, $DB_pass, $DB_name);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // We don't have the password or email info stored in sessions so 
    // instead we can get the results from the database
    $stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
    // In this case we can use the account ID to get the account info
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($password, $email);
    $stmt->fetch();
    $stmt->close();

    define("TITLE", "Account | Charlotte's blog");
    include("includes/header.php");
?>

            <li class="last"><a href="logout.php">Logout</a></li>
            <li class="last"><a href="profile.php">Profile</a></li>
            <li class="last"><input type="text" placeholder="Search..."></li>
        </ul>
    </div><!-- nav -->
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
                    <td class="bold">Password:</td>
                    <td class="gap"><?=$password?></td>
                </tr>
                <tr>
                    <td class="bold">Email:</td>
                    <td class="gap"><?=$email?></td>
                </tr>
            </table>
        </div><!-- account-details -->
    </div><!-- content -->