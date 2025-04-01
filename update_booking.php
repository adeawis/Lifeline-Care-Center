<?php
// Database configuration
$host = "localhost";
$dbname = "lifeline_carecenter";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch booking data for update
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure the ID is an integer
    try {
        $query = "SELECT * FROM booking WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            die("Booking not found.");
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
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
        $updateQuery = "UPDATE caregiver 
                        SET first_name = :first_name, last_name = :last_name, email = :email, 
                            service_type = :service_type, start_date = :start_date, end_date = :end_date, 
                            gender = :gender
                        WHERE id = :id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'service_type' => $service_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'gender' => $gender,
            'id' => $id
        ]);

        $success_message = "Booking updated successfully!";
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking</title>
    <link rel="stylesheet" href="css/update_bookin.css">
</head>
<body>

    <h1>Update Booking</h1>

    <?php if (isset($success_message)) : ?>
        <p class="success-message"><?= $success_message ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= isset($booking['first_name']) ? htmlspecialchars($booking['first_name']) : '' ?>" required><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= isset($booking['last_name']) ? htmlspecialchars($booking['last_name']) : '' ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= isset($booking['email']) ? htmlspecialchars($booking['email']) : '' ?>" required><br>

        <label>Service Type:</label>
        <select name="service_type" required>
            <option value="Nurse" <?= isset($booking['service_type']) && $booking['service_type'] === 'Nurse' ? 'selected' : '' ?>>Nurse</option>
            <option value="Caregiver" <?= isset($booking['service_type']) && $booking['service_type'] === 'Caregiver' ? 'selected' : '' ?>>Caregiver</option>
            <option value="Daily Basis Worker" <?= isset($booking['service_type']) && $booking['service_type'] === 'Daily Basis Worker' ? 'selected' : '' ?>>Daily Basis Worker</option>
        </select><br>

        <label>Start Date:</label>
        <input type="date" name="start_date" value="<?= isset($booking['start_date']) ? htmlspecialchars($booking['start_date']) : '' ?>" required><br>

        <label>End Date:</label>
        <input type="date" name="end_date" value="<?= isset($booking['end_date']) ? htmlspecialchars($booking['end_date']) : '' ?>" required><br>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?= isset($booking['gender']) && $booking['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= isset($booking['gender']) && $booking['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
        </select><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
