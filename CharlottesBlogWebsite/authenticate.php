<?php
    session_start();

    $DB_host = 'localhost';
    $DB_user = 'root';
    $DB_pass = 'mysql';
    $DB_name = 'charlottesblog';

    // Try and connect
    $con = mysqli_connect($DB_host, $DB_user, $DB_pass, $DB_name);
    if (mysqli_connect_errno()) {
        // If connection failed, stop script and display error
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // Now we check if the data from the login form was submitted, isset() will check if the data exists.
    if (!isset($_POST['username'], $_POST['password'])) {
        // Could not get the data that should have been sent.
        exit('Please fill both the username and password fields!');
    }

    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $password);
            $stmt->fetch();
            // Account exists, verifying the password
            if (password_verify($_POST['password'], $password)) {
            // If we don't want to use any password encryption method, we use this
            // if ($_POST['password'] === $password) {
                // Verification successful, user has logged in
                // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['id'] = $id;
                header('Location: profile.php');
            } else {
                echo 'Incorrect username and/or password combination';
            }
        } else {
            echo 'Incorrect username and/or password combination';
        }

        $stmt->close();
    }
?>