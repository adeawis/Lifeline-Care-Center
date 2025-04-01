<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header('Location: indexWelcome.php');
    exit();
}

if (isset($_GET['delete_success'])) {
    $delete_success_message = "Your account has been successfully deleted.";
}

$email = $_SESSION['email'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, phone FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    try {
        // Start a transaction
        $conn->begin_transaction();

        // Delete associated bookings
        $delete_bookings = $conn->prepare("DELETE FROM booking WHERE email = ?");
        $delete_bookings->bind_param("s", $email);
        $delete_bookings->execute();

        // Delete any associated caretaker assignments
        $delete_schedule = $conn->prepare("DELETE FROM schedule_admin WHERE client_name = (SELECT full_name FROM users WHERE email = ?)");
        $delete_schedule->bind_param("s", $email);
        $delete_schedule->execute();

        // Delete user account
        $delete_user = $conn->prepare("DELETE FROM users WHERE email = ?");
        $delete_user->bind_param("s", $email);
        $delete_user->execute();

        // Commit the transaction
        $conn->commit();

        // Destroy the session
        session_destroy();
        
        // Show success message and redirect to welcome page
        echo "<script>alert('Your account has been successfully deleted.'); window.location.href='index.html';</script>";
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        $error_message = "Error deleting account: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account - Lifeline Care Center</title>
    <link rel="shortcut icon" href="images/titleIcon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .delete-container {
            max-width: 500px;
            width: 100%;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .warning-icon {
            color: #D54A6A;
            font-size: 64px;
            margin-bottom: 20px;
        }
        .user-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: left;
        }
        .btn-delete {
            background-color: #D54A6A;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-delete:hover {
            background-color: #c03a5a;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-cancel:hover {
            background-color: #555f66;
            color: white;
        }
        .delete-message {
            margin: 20px 0;
            font-size: 18px;
            color: #333;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="delete-container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <i class="fas fa-exclamation-triangle warning-icon"></i>
        <h2>Delete Your Account</h2>

        <div class="user-info">
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        </div>

        <p class="delete-message">
            Are you absolutely sure you want to delete your account? This action is permanent and will remove all your data, including bookings and caretaker assignments.
        </p>

        <form method="POST" id="deleteForm">
            <button type="submit" name="confirm_delete" class="btn btn-delete">Yes, Permanently Delete My Account</button>
            <a href="profile.php" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>