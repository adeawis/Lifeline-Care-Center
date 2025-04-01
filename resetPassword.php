<?php
session_start();
date_default_timezone_set('Asia/Colombo'); // Set timezone for Sri Lanka
require 'db.php';


if (!isset($_GET['token'])) {
    header("Location: forgotPassword.php");
    exit();
}

$token = $_GET['token'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Verify token and update password
        $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Update password and clear reset token
            $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE email = ?");
            $updateStmt->bind_param("ss", $password, $user['email']);
            
            if ($updateStmt->execute()) {
            $_SESSION['message'] = "Password has been reset successfully. You can now login with your new password.";
            header("Location: forgotPassword.php");
            exit();
            } else {
            $_SESSION['error'] = "Error updating password.";
            }
            $updateStmt->close();
        } else {
            $_SESSION['error'] = "Invalid reset token.";
        }
        $stmt->close();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Lifeline Care Center</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            color: #D54A6A;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #666;
            margin: 0;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .button {
            background: #D54A6A;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        .button:hover {
            background: #C4415F;
        }
        .close-btn{
            position: absolute;
            font-weight: 600;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: white;
            color: black;
            text-align: center;
            line-height: 19px;
            border-radius: 15px;
            cursor: pointer;
            text-decoration: none;
        }
        .close-btn:hover{
            background: #f0f0f0;
        }
        .message {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .error {
            background: #fee2e2;
            color: #ef4444;
        }
        .success {
            background: #dcfce7;
            color: #16a34a;
        }
        .back-link {
            text-align: center;
            margin-top: 1rem;
        }
        .back-link a {
            color: #D54A6A;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        <a href="sign-in.html" class="close-btn">&times;</a>
            <h1>Reset Password</h1>
            <p>Enter your new password</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter new password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required 
                       placeholder="Confirm new password">
            </div>
            <button type="submit" class="button">Update Password</button>
        </form>
    </div>
</body>
</html>