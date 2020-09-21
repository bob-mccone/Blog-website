<?php
// Database details
// Database hostname, usually you don't need to change this
define('db_host', 'localhost');
// Database username
define('db_user', 'root');
// Database password
define('db_pass', 'mysql');
// Database name
define('db_name', 'phplogin');
// Database charset, change this only if utf8 is not supported by your language
define('db-charset', 'utf8');

// Email activation variables
// Account activation required?
define('account_activation', false);
// Change "Your Company Name" and "yourdomain.com", do not remove the < and >
define('mail_from', 'Your Company Name <noreply@yourdomain.com>');
// Link to activation file, update this
define('activation_link', 'http://yourdomain.com/phplogin/activate.php');
?>