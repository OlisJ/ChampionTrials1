<?php
require('config.php'); // config.php now handles session_start

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get and clean inputs
    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    $lastname  = mysqli_real_escape_string($con, $_POST['lastname']);
    $email     = mysqli_real_escape_string($con, $_POST['email']);
    $password  = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // check password match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {

        // CHECK FOR DUPLICATE EMAIL
        $check_email = mysqli_query($con, "SELECT * FROM users WHERE email = '$email' LIMIT 1");

        if (mysqli_num_rows($check_email) > 0) {
            $error = "This email is already registered!";
        } 
        else {

            // secure password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // insert into DB
            $query = "INSERT INTO users (firstname, lastname, email, password)
                      VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

            if (mysqli_query($con, $query)) {
                header("Location: login.php?registered=1");
                exit();
            } 
            else {
                $error = "Error: Could not register user.";
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
    <title>Sign Up</title>
</head>
<body>
    
    <section class="contact-section" id="contact">
        <div class="section-header">
            <h2 class="section-title">Sign Up</h2>
            <p class="section-subtitle">Create your account to begin your journey.</p>
        </div>

        <!-- Error / success messages -->
        <div style="text-align:center;color:red;font-size:18px;">
            <?php if ($error) echo $error; ?>
        </div>
        <div style="text-align:center;color:lightgreen;font-size:18px;">
            <?php if ($success) echo $success; ?>
        </div>
        
        <div class="contact-container">

            <!-- LEFT SIDE INFO (KEPT EXACTLY LIKE YOU HAD IT) -->
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

                <a href="https://calendly.com" target="_blank" class="info-item">
                    <div class="info-icon">📅</div>
                    <div class="info-text">
                        <h4>Schedule Meeting</h4>
                        <p>Book a consultation</p>
                    </div>
                </a>

            </div>

            <!-- SIGNUP FORM (replaces contact form) -->
            <form class="contact-form" id="contactForm" action="" method="POST">
                
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="submit-btn">Create Account</button>

            </form>
        </div>
    </section>
</body>
</html>
