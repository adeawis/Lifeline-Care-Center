<?php
// Database connection parameters
$host = 'localhost'; // Replace with your database host
$dbname = 'lifeline_carecenter'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceId = $_POST['serviceId'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $client_name = $_POST['client_name'];
    $tele = $_POST['tele'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Validate that End Date is not earlier than Start Date
    if (strtotime($endDate) < strtotime($startDate)) {
        die("Error: End Date cannot be earlier than Start Date.");
    }

    // Insert data into the database
    $sql = "INSERT INTO scheduling (serviceId, name, type, client_name, tele, start_date, end_date) 
            VALUES (:serviceId, :name, :type, :client_name, :tele, :startDate, :endDate)";
    
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':serviceId', $serviceId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':client_name', $client_name);
    $stmt->bindParam(':tele', $tele);
    $stmt->bindParam(':startDate', $startDate);
    $stmt->bindParam(':endDate', $endDate);

    if ($stmt->execute()) {
        echo "<script>alert('Service scheduled successfully!'); window.location.href = 'schedule.php';</script>";
    } else {
        echo "Failed to schedule the service.";
    }
}
?>
