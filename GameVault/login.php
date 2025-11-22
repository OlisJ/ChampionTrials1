<?php
require('config.php');

$error = "";
$success = "";

// Check if user just registered
if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    $success = "Registration successful! Please login.";
}

// If already logged in, redirect to index
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Check if user exists
        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Check if required fields exist
            if (isset($user['user_id']) && isset($user['password']) && isset($user['email'])) {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    $user_id = (int)$user['user_id'];
                    
                    if ($user_id > 0) {
                        // Set session variables
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_name'] = trim((isset($user['firstname']) ? $user['firstname'] : '') . ' ' . (isset($user['lastname']) ? $user['lastname'] : ''));
                        
                        // Regenerate session ID for security
                        session_regenerate_id(true);
                        
                        // Redirect to index
                        header("Location: index.php");
                        exit();
                    } else {
                        $error = "Invalid user account!";
                    }
                } else {
                    $error = "Invalid email or password!";
                }
            } else {
                $error = "Database error: Missing user data!";
            }
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login - The GameVault</title>
</head>
<body>
    
    <section class="contact-section" id="contact">
        <div class="section-header">
            <h2 class="section-title">Login</h2>
            <p class="section-subtitle">Sign in to access your game library.</p>
        </div>

        <!-- Error / success messages -->
        <?php if ($error): ?>
        <div style="text-align:center;color:red;font-size:18px;margin-bottom:20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div style="text-align:center;color:lightgreen;font-size:18px;margin-bottom:20px;">
            <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>
        
        <div class="contact-container">

            <!-- LEFT SIDE INFO -->
            <div class="contact-info">

                <a href="https://maps.google.com/?q=Prishtina+Pjeter+Bogdani" target="_blank" class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-text">
                        <h4>Location</h4>
                        <p>Prishtina, Pjetër Bogdani</p>
                    </div>
                </a>

                <a href="mailto:hello@thegamevault.com" class="info-item">
                    <div class="info-icon">📧</div>
                    <div class="info-text">
                        <h4>Email</h4>
                        <p>hello@thegamevault.com</p>
                    </div>
                </a>

                <a href="tel:+11231234567" class="info-item">
                    <div class="info-icon">📱</div>
                    <div class="info-text">
                        <h4>Phone</h4>
                        <p>+1 (123) 123-4567</p>
                    </div>
                </a>

                <a href="signup.php" class="info-item">
                    <div class="info-icon">📝</div>
                    <div class="info-text">
                        <h4>New User?</h4>
                        <p>Create an account</p>
                    </div>
                </a>

            </div>

            <!-- LOGIN FORM -->
            <form class="contact-form" id="loginForm" action="" method="POST">
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <button type="submit" class="submit-btn">Login</button>

                <div style="text-align: center; margin-top: 20px;">
                    <p style="color: var(--text-secondary);">Don't have an account? <a href="signup.php" style="color: var(--accent-cyan); text-decoration: none;">Sign Up</a></p>
                </div>

            </form>
        </div>
    </section>
</body>
</html>

