<?php
require('config.php');

$error = "";

// If already logged in as admin, redirect
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0) {
    header("Location: admin_dashboard.php");
    exit();
}

// Clear any user session
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields!";
    } else {
        // Check if admin table exists
        $table_check = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
        if (mysqli_num_rows($table_check) == 0) {
            $error = "Admin table does not exist! Please run admin.sql first.";
        } else {
            // Query admin
            $query = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
            $result = mysqli_query($con, $query);
            
            if (!$result) {
                $error = "Database error: " . mysqli_error($con);
            } elseif (mysqli_num_rows($result) == 0) {
                $error = "Invalid username or password!";
            } else {
                $admin = mysqli_fetch_assoc($result);
                
                // Verify password
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = (int)$admin['admin_id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_email'] = $admin['email'];
                    
                    session_regenerate_id(true);
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error = "Invalid username or password!";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Login - The GameVault</title>
</head>
<body>
    <section class="contact-section" id="contact">
        <div class="section-header">
            <h2 class="section-title">Admin Login</h2>
            <p class="section-subtitle">Administrator access only</p>
        </div>

        <?php if ($error): ?>
        <div style="text-align:center;color:var(--accent-red);font-size:18px;margin-bottom:20px;padding:15px;background:rgba(255,51,51,0.1);border-radius:8px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <div class="contact-container">
            <div class="contact-info">
                <div class="info-item">
                    <div class="info-icon">🔐</div>
                    <div class="info-text">
                        <h4>Admin Access</h4>
                        <p>Restricted area</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">👤</div>
                    <div class="info-text">
                        <h4>User Login</h4>
                        <p><a href="login.php" style="color: var(--accent-cyan);">Go to user login</a></p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">🏠</div>
                    <div class="info-text">
                        <h4>Home</h4>
                        <p><a href="index.php" style="color: var(--accent-cyan);">Back to homepage</a></p>
                    </div>
                </div>
            </div>

            <form class="contact-form" method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autocomplete="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <button type="submit" class="submit-btn">Login as Admin</button>
            </form>
        </div>
    </section>
</body>
</html>

