<?php
session_start();
require 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        
        // Prepare statement to check if email exists in users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Generate a secure random token
            $token = bin2hex(random_bytes(20));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store reset token in users table
            $updateStmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $token, $email);
            
            if ($updateStmt->execute()) {
                // In a production environment, you would send this via email
                $_SESSION['message'] = "Password reset instructions have been sent to your email.";
                $_SESSION['reset_email'] = $email;
                
                // Redirect to reset password page
                header("Location: resetPassword.php?token=" . urlencode($token));
                exit();
            } else {
                $_SESSION['error'] = "Error generating reset token.";
            }
            $updateStmt->close();
        } else {
            $_SESSION['error'] = "No account found with this email address.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Lifeline Care Center</title>
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
            <h1>Forgot Password</h1>
            <p>Enter your email to reset your password</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success">
                <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required 
                       placeholder="Enter your registered email">
            </div>
            <button type="submit" class="button">Reset Password</button>
        </form>

        <div class="back-link">
            <a href="sign-in.html">Back to Login</a>
        </div>
    </div>
</body>
</html>