<?php
include 'db.php';

// Initialize message variables
$message = '';
$messageClass = '';

// Function to safely redirect
function redirectToManage() {
    header("Location: manageClient.php");
    exit();
}

// Check if email is provided in URL
if (!isset($_GET['id'])) {
    redirectToManage();
}

$email = $_GET['id'];

// Fetch client details for confirmation
$query = "SELECT full_name, phone FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_assoc();

if (!$users) {
    redirectToManage();
}

// Handle deletion confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
        try {
            // Delete query using prepared statement
            $delete_query = "DELETE FROM users WHERE email = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("s", $email);
            
            if ($stmt->execute()) {
                // Set success message in session and redirect
                session_start();
                $_SESSION['delete_message'] = "Client has been successfully removed.";
                $_SESSION['delete_status'] = "success";
                redirectToManage();
            } else {
                throw new Exception("Error deleting record");
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $messageClass = "error";
        }
    } else {
        redirectToManage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Client - Lifeline Care Center</title>
    <link rel="shortcut icon" href="images/titleIcon.png">
    <link rel="stylesheet" href="css/adminMC.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        .delete-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .warning-icon {
            color: #dc3545;
            font-size: 48px;
            margin-bottom: 20px;
        }
        .delete-message {
            margin: 20px 0;
            font-size: 18px;
            color: #333;
            line-height: 1.5;
        }
        .staff-info {
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .confirm-btn, .cancel-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            min-width: 120px;
        }
        .confirm-btn {
            background: #dc3545;
            color: white;
        }
        .confirm-btn:hover {
            background: #c82333;
        }
        .cancel-btn {
            background: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .cancel-btn:hover {
            background: #5a6268;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="delete-container">
        <i class="fas fa-exclamation-triangle warning-icon"></i>
        <h2>Delete Client Member</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="staff-info">
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($users['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($users['phone']); ?></p>
        </div>

        <p class="delete-message">
            Are you sure you want to delete this client? This action cannot be undone.
        </p>

        <form method="POST" class="button-group">
            <input type="hidden" name="confirm_delete" value="yes">
            <button type="submit" class="confirm-btn">Yes, Delete</button>
            <a href="manageClient.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>