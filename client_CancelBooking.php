<?php
include 'db.php';

// Initialize message variables
$message = '';
$messageClass = '';

// Function to safely redirect
function redirectToManage() {
    header("Location: userProfile.php?tab=bookings");
    exit();
}

// Check if email is provided in URL
if (!isset($_GET['id'])) {
    redirectToManage();
}

$id = $_GET['id'];

// Fetch staff details for confirmation
$query = "SELECT first_name, last_name, service_type FROM booking WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    redirectToManage();
}

// Handle deletion confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
        try {
            // Delete query using prepared statement
            $delete_query = "DELETE FROM booking WHERE id = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("s", $id);
            
            if ($stmt->execute()) {
                // Set success message in session and redirect
                session_start();
                $_SESSION['delete_message'] = "Booking has been successfully removed.";
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
    <title>Cancel Booking - Lifeline Care Center</title>
    <link rel="shortcut icon" href="images/titleIcon.png">
    <link rel="stylesheet" href="css/adminMC.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        .cancel-container {
            max-width: 550px;
            margin: 80px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            text-align: center;
        }
        .warning-icon {
            color: #e74c3c;
            font-size: 60px;
            margin-bottom: 25px;
        }
        h2 {
            color: #333;
            font-size: 26px;
            margin-bottom: 20px;
        }
        .cancel-message {
            margin: 25px 0;
            font-size: 18px;
            color: #555;
            line-height: 1.6;
        }
        .booking-info {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            text-align: left;
        }
        .booking-info p {
            margin: 10px 0;
            color: #333;
            font-size: 16px;
        }
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        .confirm-btn, .cancel-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            min-width: 140px;
            transition: all 0.3s ease;
        }
        .confirm-btn {
            background: #e74c3c;
            color: white;
        }
        .confirm-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        .cancel-btn {
            background: #3498db;
            color: white;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .cancel-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .note {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="cancel-container">
        <i class="fas fa-calendar-times warning-icon"></i>
        <h2>Cancel Your Booking</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="booking-info">
            <p><strong>Appointment Details</strong></p>
            <p><i class="fas fa-user"></i> <strong>Name:</strong> <?php echo htmlspecialchars($booking['first_name']) . ' ' . htmlspecialchars($booking['last_name']); ?></p>
            <p><i class="fas fa-tag"></i> <strong>Booking ID:</strong> <?php echo htmlspecialchars($id); ?></p>
            <p><i class="fas fa-briefcase-medical"></i> <strong>Service:</strong> <?php echo htmlspecialchars($booking['service_type']); ?></p>
        </div>

        <p class="cancel-message">
            Are you sure you want to cancel this appointment? This action cannot be undone.
        </p>

        <form method="POST" class="button-group">
            <input type="hidden" name="confirm_delete" value="yes">
            <button type="submit" class="confirm-btn"><i class="fas fa-check"></i> Yes, Cancel</button>
            <a href="userProfile.php?tab=bookings" class="cancel-btn"><i class="fas fa-times"></i> Keep Booking</a>
        </form>
        
        <p class="note">If you need to reschedule instead of canceling, please contact our support team.</p>
    </div>
</body>
</html>