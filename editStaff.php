<?php
include 'db.php';

// Initialize error/success message variables
$message = '';
$messageClass = '';

// Check if email is provided in URL
if (isset($_GET['id'])) {
    $email = $_GET['id'];
    
    // Fetch existing staff data
    $query = "SELECT * FROM staffreg WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
    
    if (!$staff) {
        header("Location: manageCaretakers.php");
        exit();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $experience = $_POST['experience'];
    $availability = $_POST['availability'];
    $email = $_POST['email'];

    try {
        // Update query using prepared statement
        $update_query = "UPDATE staffreg SET 
            full_name = ?,
            age = ?,
            location = ?,
            phone = ?,
            gender = ?,
            position = ?,
            experience = ?,
            availability = ?
            WHERE email = ?";
            
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sisssssss", 
            $full_name, 
            $age, 
            $location,
            $phone, 
            $gender, 
            $position, 
            $experience, 
            $availability,
            $email
        );

        if ($stmt->execute()) {
            $message = "Staff information updated successfully!";
            $messageClass = "success";
            // Refresh staff data
            $result = $conn->query("SELECT * FROM staffreg WHERE email = '$email'");
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
            margin: 16px auto;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .edit-form {
            display: grid;
            gap: 16px;
        }
        .form-group {
            display: grid;
            gap: 6px;
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
            margin-top: 15px;
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
            padding: 6px;
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
        <h2>Edit Staff Information</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form class="edit-form" method="POST">
            <input type="hidden" name="email" value="<?php echo $staff['email']; ?>">
            
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $staff['full_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?php echo $staff['age']; ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="location" value="<?php echo $staff['location']; ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo $staff['phone']; ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="male" <?php echo ($staff['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($staff['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <select id="position" name="position" required>
                    <option value="nurses" <?php echo ($staff['position'] == 'nurses') ? 'selected' : ''; ?>>Nurses</option>
                    <option value="caregivers" <?php echo ($staff['position'] == 'caregivers') ? 'selected' : ''; ?>>Caregivers</option>
                    <option value="daily" <?php echo ($staff['position'] == 'daily') ? 'selected' : ''; ?>>Daily Basis</option>
                </select>
            </div>

            <div class="form-group">
                <label for="experience">Experience (years)</label>
                <input type="text" id="experience" name="experience" value="<?php echo $staff['experience']; ?>" required>
            </div>

            <div class="form-group">
                <label for="availability">Availability</label>
                <select id="availability" name="availability" required>
                    <option value="available" <?php echo ($staff['availability'] == 'available') ? 'selected' : ''; ?>>Available</option>
                    <option value="unavailable" <?php echo ($staff['availability'] == 'unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="save-btn">Save Changes</button>
                <a href="manageCaretakers.php" class="cancel-btn" style="text-decoration: none; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>