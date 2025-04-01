<?php
include 'db.php';

// Initialize error/success message variables
$message = '';
$messageClass = '';

// Check if email is provided in URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch existing staff data
    $query = "SELECT * FROM booking WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    
    if (!$booking) {
        header("Location: userProfile.php");
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $service_type = $_POST['service_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $gender = $_POST['gender'];

    try {
        // Update query using prepared statement
        $update_query = "UPDATE booking SET 
            first_name = ?,
            last_name = ?,
            email = ?,
            service_type = ?,
            start_date = ?,
            end_date = ?,
            gender = ?
            WHERE id = ?";
            
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssssi", 
            $first_name, 
            $last_name, 
            $email, 
            $service_type, 
            $start_date, 
            $end_date, 
            $gender,
            $id
        );

        if ($stmt->execute()) {
            $message = "Booking information updated successfully!";
            $messageClass = "success";
            // Refresh staff data
            $result = $conn->query("SELECT * FROM booking WHERE id = '$id'");
            $staff = $result->fetch_assoc();
        } else {
            throw new Exception("Error updating record");
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff Information - Lifeline Care Center</title>
    <link rel="shortcut icon" href="images/titleIcon.png">
    <link rel="stylesheet" href="css/adminMC.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body{
            background-color: #f5f5f5;
        }
        
        .edit-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .edit-form {
            display: grid;
            gap: 20px;
        }
        .form-group {
            display: grid;
            gap: 8px;
        }
        .form-group label {
            font-weight: 500;
            color: #333;
        }
        .form-group input, .form-group select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .save-btn, .cancel-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .save-btn {
            background: #F66B9C;
            color: white;
        }
        .save-btn:hover {
            background: #e05a89;
        }
        .cancel-btn {
            background: #6c757d;
            color: white;
        }
        .cancel-btn:hover {
            background: #5a6268;
            color: white;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Booking Information</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form class="edit-form" method="POST">
        <div class="form-group">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= isset($booking['first_name']) ? htmlspecialchars($booking['first_name']) : '' ?>" required>
        </div>

        <div class="form-group">
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= isset($booking['last_name']) ? htmlspecialchars($booking['last_name']) : '' ?>" required>
        </div>

        <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= isset($booking['email']) ? htmlspecialchars($booking['email']) : '' ?>" required>
        </div>

        <div class="form-group">
        <label>Service Type:</label>
        <select name="service_type" required>
            <option value="nurse" <?= isset($booking['service_type']) && $booking['service_type'] === 'nurse' ? 'selected' : '' ?>>Nurse</option>
            <option value="caregiver" <?= isset($booking['service_type']) && $booking['service_type'] === 'caregiver' ? 'selected' : '' ?>>Caregiver</option>
            <option value="daily_basis_worker" <?= isset($booking['service_type']) && $booking['service_type'] === 'daily_basis_worker' ? 'selected' : '' ?>>Daily Basis Worker</option>
        </select>
        </div>

        <div class="form-group">
        <label>Start Date:</label>
        <input type="date" name="start_date" value="<?= isset($booking['start_date']) ? htmlspecialchars($booking['start_date']) : '' ?>" required>
        </div>

        <div class="form-group">
        <label>End Date:</label>
        <input type="date" name="end_date" value="<?= isset($booking['end_date']) ? htmlspecialchars($booking['end_date']) : '' ?>" required>
        </div>

        <div class="form-group">
        <label>Gender:</label>
        <select name="gender" required>
            <option value="male" <?= isset($booking['gender']) && $booking['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= isset($booking['gender']) && $booking['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
        </select>
        </div>

            <div class="button-group">
                <button type="submit" class="save-btn">Save Changes</button>
                <a href="userProfile.php?tab=bookings" class="cancel-btn" style="text-decoration: none; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>