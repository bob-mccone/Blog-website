<?php
    include 'main.php';
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // Now we check if the data was submitted, isset() function will check if the data exists
    if (!isset($_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['email'])) {
        // Could not get the data that should have been sent
        exit('Please complete the reistration form and try again');
    }

    // Make sure the submitted registration values are not empty
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
        // One or more values are empty
        exit('Please complete the registration form and try again');
    }

    // Check to see if the email is valid
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        exit('Email is not valid, please try again');
    }

    // Username must contain only characters and numbers
    if (!preg_match('/^[A-Za-z0-9]+$/', $_POST['username'])) {
        exit('Username is not valid, can only contain letters and numbers, please try again');
    }

    // Password must be between 5 and 20 characters long
    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        exit('Password must be between 5 and 20 characters long, please try again');
    }

    // Check if both the password and confirm password fields match
    if ($_POST['confirm_password'] != $_POST['password']) {
        exit('Passwords do not match, please try again');
    }

    // We need to check if the account with that username exists
    $stmt = $con->prepare('SELECT id, password FROM user WHERE username = ? OR email = ?');
    // Bind parameters (s = string, i = int, b = blob etc), hash the password using the PHP password-hash function
    $stmt->bind_param('ss', $_POST['username'], $_POST['email']);
    $stmt->execute();
    $stmt->store_result();
    // Store the result so we can check if the account exists in teh database
    if ($stmt->num_rows > 0) {
        // Username already exists
        echo 'Username and/or email exists, please try again';
    } else {
        $stmt->close();
        // Username doesn't exists, insert new account
        $stmt = $con->prepare('INSERT INTO user (username, password, email, activation_code) VALUES (?, ?, ?, ?)');
        // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
        $stmt->execute();
        $stmt->close();
        if (account_activation) {
            // Account activation required, send the user the activation email with the "send_activation_email" function from the "main.php" file
            send_activation_email($_POST['email'], $uniqid);
            echo 'Please check your email to activate your account';
        } else {
            echo 'You have successfully registered, you can now login';
        }
    }
?>