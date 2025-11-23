<?php
require('config.php');

// Destroy admin session
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_email']);

session_destroy();
session_start();

header("Location: admin_login.php");
exit();
?>

