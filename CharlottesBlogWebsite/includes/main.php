<?php
// The main file contains the database connection, session initializing, and functions, other PHP files will depend on this file.
// Include the configuration file
include_once 'config.php';

// We need to use sessions, so you should always start sessions using the below function
session_start();

// Connect to the MySQL database using MySQLi
$con = mysqli_connect(db_host, db_user, db_pass, db_name);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Update the charset
mysqli_set_charset($con, db_charset);

// The below function will check if the user is logged-in and also check the remember me cookie
function check_loggedin($con, $redirect_file = 'login.php') {
    // Check for remember me cookie variable and loggedin session variable
    if (isset($_COOKIE['remember_me']) && !empty($_COOKIE['remember_me']) && !isset($_SESSION['loggedin'])) {
        // If the remember me cookie matches one in the database then we can update the session variables.
        $stmt = $con->prepare('SELECT id, username, role FROM user WHERE remember_me = ?');
        $stmt->bind_param('s', $_COOKIE['remember_me']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Found a match, update the session variables and keep the user logged-in
            $stmt->bind_result($id, $username, $role);
            $stmt-fetch();
            $stmt->close();
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;
        } else {
            // If the user is not remembered redirect to the login page.
            header('Location: ' . $redirect_file);
            exit;
        }
    } else if (!isset($_SESSION['loggedin'])) {
        // If the user is not logged in redirect to the login page.
        header('Location: ' . $redirect_file);
        exit;
    }
}

// Send activation email function
function send_activation_email($email, $code) {
    $subject = 'Account Activation Required';
    $headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    $activate_link = activation_link . '?email=' . $email . '&code=' . $code;
    $email_template = str_replace('%link%', $activate_link, file_get_contents('activation-email-template.php'));
    mail($email, $subject, $email_template, $headers, '-f ' . mail_from);
}

// Brute force protection
function loginAttempts($con, $update = true) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $now = date('Y-m-d H:i:s');
    if ($update) {
        $stmt = $con->prepare('INSERT INTO login_attempts (ip_address, `date`) VALUES (?,?) ON DUPLICATE KEY UPDATE attempts_left = attempts_left - 1, `date` = VALUES(`date`)');
        $stmt->bind_param('ss', $ip, $now);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $con->prepare('SELECT * FROM login_attempts WHERE ip_address = ?');
    $stmt->bind_param('s', $ip);
    $stmt->execute();
    $result = $stmt->get_result();
    $login_attempts = $result->fetch_array(MYSQLI_ASSOC);
    $stmt->close();
    if ($login_attempts) {
        // The user can try to login after 1 day, change the "+1 day" if you want to increase/decrease this date
        $expire = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($login_attempts['date'])));
        if ($now > $expire) {
            $stmt = $con->prepare('DELETE FROM login_attempts WHERE ip_address = ?');
            $stmt->bind_param('s', $ip);
            $stmt->execute();
            $stmt->close();
            $login_attempts = array();
        }
    }
    return $login_attempts;
}
?>