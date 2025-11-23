<?php
require('config.php');

// Check admin login
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] <= 0) {
    header("Location: admin_login.php");
    exit();
}

$error = "";
$success = "";

// Handle delete user
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = (int)$_GET['delete'];
    $delete_query = "DELETE FROM users WHERE user_id = $user_id";
    if (mysqli_query($con, $delete_query)) {
        $success = "User deleted successfully!";
    } else {
        $error = "Error: " . mysqli_error($con);
    }
}

// Handle delete game request
if (isset($_GET['delete_request']) && is_numeric($_GET['delete_request'])) {
    $request_id = (int)$_GET['delete_request'];
    $delete_query = "DELETE FROM game_requests WHERE request_id = $request_id";
    if (mysqli_query($con, $delete_query)) {
        $success = "Game request deleted successfully!";
    } else {
        $error = "Error: " . mysqli_error($con);
    }
}

// Handle update game request status
if (isset($_GET['update_status']) && is_numeric($_GET['update_status']) && isset($_GET['status'])) {
    $request_id = (int)$_GET['update_status'];
    $status = mysqli_real_escape_string($con, $_GET['status']);
    $update_query = "UPDATE game_requests SET status = '$status' WHERE request_id = $request_id";
    if (mysqli_query($con, $update_query)) {
        $success = "Request status updated successfully!";
    } else {
        $error = "Error: " . mysqli_error($con);
    }
}

// Handle add user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $firstname = mysqli_real_escape_string($con, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($con, trim($_POST['lastname']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check duplicate email
        $check = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already exists!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed')";
            if (mysqli_query($con, $insert)) {
                $success = "User added successfully!";
            } else {
                $error = "Error: " . mysqli_error($con);
            }
        }
    }
}

// Get all users
$users = [];
$query = "SELECT user_id, firstname, lastname, email FROM users ORDER BY user_id DESC";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Get all game requests
$game_requests = [];
$requests_query = "SELECT gr.request_id, gr.game_name, gr.status, gr.created_at, u.firstname, u.lastname, u.email 
                   FROM game_requests gr 
                   LEFT JOIN users u ON gr.user_id = u.user_id 
                   ORDER BY gr.created_at DESC";
$requests_result = mysqli_query($con, $requests_query);
if ($requests_result) {
    while ($row = mysqli_fetch_assoc($requests_result)) {
        $game_requests[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The GameVault</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 150px 20px 50px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .admin-title {
            font-size: 48px;
            color: var(--accent-cyan);
            text-shadow: 0 0 20px var(--accent-cyan);
        }
        .admin-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue));
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(153, 69, 255, 0.5);
        }
        .admin-btn-danger {
            background: linear-gradient(135deg, var(--accent-red), #cc0000);
        }
        .admin-section {
            background: var(--carbon-medium);
            border: 1px solid var(--metal-dark);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }
        .section-title {
            font-size: 28px;
            color: var(--text-primary);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--accent-purple);
            padding-bottom: 10px;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .message-error {
            background: rgba(255, 51, 51, 0.2);
            color: var(--accent-red);
            border: 1px solid var(--accent-red);
        }
        .message-success {
            background: rgba(0, 255, 136, 0.2);
            color: var(--accent-green);
            border: 1px solid var(--accent-green);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .form-group-full {
            grid-column: 1 / -1;
        }
        .form-group label {
            display: block;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            background: var(--carbon-dark);
            border: 1px solid var(--metal-dark);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--accent-purple);
            box-shadow: 0 0 10px rgba(153, 69, 255, 0.3);
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .users-table th,
        .users-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--metal-dark);
        }
        .users-table th {
            background: var(--carbon-dark);
            color: var(--accent-cyan);
            font-weight: bold;
        }
        .users-table td {
            color: var(--text-secondary);
        }
        .users-table tr:hover {
            background: var(--carbon-light);
        }
        .delete-btn {
            padding: 8px 16px;
            background: var(--accent-red);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .delete-btn:hover {
            background: #cc0000;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <header class="header" id="header">
        <nav class="nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <div class="logo-prism">
                        <div class="prism-shape"></div>
                    </div>
                </div>
                <span class="logo-text">
                    <span class="prism">Game</span>
                    <span class="flux">Vault</span>
                </span>
            </a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="admin_dashboard.php" class="nav-link active">Admin</a></li>
                <li><a href="admin_logout.php" class="nav-link">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="admin-wrapper">
        <div class="admin-header">
            <h1 class="admin-title">Admin Dashboard</h1>
            <div>
                <a href="index.php" class="admin-btn">Home</a>
                <a href="admin_logout.php" class="admin-btn admin-btn-danger">Logout</a>
            </div>
        </div>

        <?php if ($error): ?>
        <div class="message message-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="message message-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="admin-section">
            <h2 class="section-title">Add New User</h2>
            <form method="POST" action="">
                <input type="hidden" name="add_user" value="1">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                    <div class="form-group form-group-full">
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
                    <div class="form-group form-group-full">
                        <button type="submit" class="admin-btn">Add User</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="admin-section">
            <h2 class="section-title">All Users (<?php echo count($users); ?>)</h2>
            <?php if (count($users) > 0): ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $user['user_id']; ?>" 
                               class="delete-btn" 
                               onclick="return confirm('Delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="color: var(--text-secondary); text-align: center; padding: 20px;">No users found.</p>
            <?php endif; ?>
        </div>

        <div class="admin-section">
            <h2 class="section-title">Game Requests (<?php echo count($game_requests); ?>)</h2>
            <?php if (count($game_requests) > 0): ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Game Name</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($game_requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['request_id']); ?></td>
                        <td><?php echo htmlspecialchars($request['game_name']); ?></td>
                        <td><?php echo htmlspecialchars(($request['firstname'] ?? 'Unknown') . ' ' . ($request['lastname'] ?? '') . ' (' . ($request['email'] ?? 'N/A') . ')'); ?></td>
                        <td>
                            <span style="padding: 4px 10px; border-radius: 5px; font-size: 12px; font-weight: bold;
                                <?php 
                                if ($request['status'] == 'approved') {
                                    echo 'background: rgba(0, 255, 136, 0.2); color: var(--accent-green);';
                                } elseif ($request['status'] == 'rejected') {
                                    echo 'background: rgba(255, 51, 51, 0.2); color: var(--accent-red);';
                                } else {
                                    echo 'background: rgba(255, 255, 255, 0.1); color: var(--text-secondary);';
                                }
                                ?>">
                                <?php echo strtoupper(htmlspecialchars($request['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($request['created_at'])); ?></td>
                        <td>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <?php if ($request['status'] == 'pending'): ?>
                                <a href="?update_status=<?php echo $request['request_id']; ?>&status=approved" 
                                   style="padding: 6px 12px; background: var(--accent-green); color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block;">Approve</a>
                                <a href="?update_status=<?php echo $request['request_id']; ?>&status=rejected" 
                                   style="padding: 6px 12px; background: var(--accent-red); color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block;">Reject</a>
                                <?php endif; ?>
                                <a href="?delete_request=<?php echo $request['request_id']; ?>" 
                                   class="delete-btn" 
                                   onclick="return confirm('Delete this request?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="color: var(--text-secondary); text-align: center; padding: 20px;">No game requests found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="main.js"></script>
</body>
</html>

