<?php
require('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['game_name'])) {
    $user_id = (int)$_SESSION['user_id'];
    $game_name = mysqli_real_escape_string($con, trim($_POST['game_name']));

    if (!empty($game_name)) {
        // Check if game_requests table exists, if not create it
        $table_check = mysqli_query($con, "SHOW TABLES LIKE 'game_requests'");
        if (mysqli_num_rows($table_check) == 0) {
            // Create table if it doesn't exist
            $create_table = "CREATE TABLE IF NOT EXISTS `game_requests` (
              `request_id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL,
              `game_name` varchar(100) NOT NULL,
              `status` varchar(20) NOT NULL DEFAULT 'pending',
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`request_id`),
              KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
            mysqli_query($con, $create_table);
        }

        // Insert game request
        $insert = "INSERT INTO game_requests (user_id, game_name, status) VALUES ($user_id, '$game_name', 'pending')";
        
        if (mysqli_query($con, $insert)) {
            header("Location: library.php?request_success=1");
            exit();
        } else {
            header("Location: library.php?request_error=1");
            exit();
        }
    } else {
        header("Location: library.php?request_error=1");
        exit();
    }
} else {
    header("Location: library.php");
    exit();
}
?>

