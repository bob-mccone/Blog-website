<?php
    session_start();
    session_destroy();
    // Redirect user to login screen
    header('Location: login.php');
?>