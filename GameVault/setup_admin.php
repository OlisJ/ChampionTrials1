<?php
/**
 * Admin Setup Script
 * Run this ONCE to create admin table and default admin account
 * DELETE THIS FILE AFTER RUNNING FOR SECURITY!
 */

require('config.php');

echo "<h2>Admin Setup Script</h2>";

// Create admin table
$create_table = "CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";

if (mysqli_query($con, $create_table)) {
    echo "<p style='color: green;'>✓ Admin table created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating table: " . mysqli_error($con) . "</p>";
}

// Check if admin exists
$check = mysqli_query($con, "SELECT * FROM admin WHERE username = 'admin'");
if (mysqli_num_rows($check) == 0) {
    // Create default admin
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $insert = "INSERT INTO admin (username, email, password) VALUES ('admin', 'admin@gamevault.com', '$hash')";
    
    if (mysqli_query($con, $insert)) {
        echo "<p style='color: green;'>✓ Default admin account created!</p>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><strong>Hash:</strong> " . htmlspecialchars($hash) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating admin: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠ Admin account already exists!</p>";
}

echo "<hr>";
echo "<p><a href='admin_login.php'>Go to Admin Login</a></p>";
echo "<p style='color: red;'><strong>⚠️ DELETE THIS FILE NOW FOR SECURITY!</strong></p>";
?>

