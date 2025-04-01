<?php
session_start();
include 'db.php';

// Get user details from email
if(isset($_GET['email'])) {
    $email = $_GET['email'];
    
    // Get booking details
    $booking_query = "SELECT b.*, s.staff_name, s.service_type 
                     FROM booking b 
                     LEFT JOIN schedule_admin s ON b.email = s.client_name 
                     WHERE b.email = ?";
    
    $stmt = $conn->prepare($booking_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    // Get caretaker details
    if($booking) {
        $staff_query = "SELECT * FROM staffreg WHERE full_name = ?";
        $stmt = $conn->prepare($staff_query);
        $stmt->bind_param("s", $booking['staff_name']);
        $stmt->execute();
        $staff_result = $stmt->get_result();
        $staff = $staff_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Report - Lifeline Care Center</title>
    <link rel="stylesheet" href="css/report-styles.css">
</head>
<body>
    <div class="container">
        <div class="report-container">
            <div class="header">
                <h1>Lifeline Care Center</h1>
                <h2>Service Report</h2>
            </div>

            <?php if(isset($booking) && isset($staff)): ?>
            <div class="report-content">
                <div class="section client-details">
                    <h3>Client Details</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="label">Name:</span>
                            <span class="value"><?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Email:</span>
                            <span class="value"><?php echo $booking['email']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="section service-details">
                    <h3>Service Details</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="label">Service Type:</span>
                            <span class="value"><?php echo $booking['service_type']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Start Date:</span>
                            <span class="value"><?php echo date('d M Y', strtotime($booking['start_date'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">End Date:</span>
                            <span class="value"><?php echo date('d M Y', strtotime($booking['end_date'])); ?></span>
                        </div>
                    </div>
                </div>

                <div class="section caretaker-details">
                    <h3>Assigned Caretaker Details</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="label">Name:</span>
                            <span class="value"><?php echo $staff['full_name']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Position:</span>
                            <span class="value"><?php echo ucfirst($staff['position']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Contact:</span>
                            <span class="value"><?php echo $staff['phone']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="section payment-details">
                    <h3>Payment Details</h3>
                    <div class="details-grid">
                        <?php
                        $days = (strtotime($booking['end_date']) - strtotime($booking['start_date'])) / (60 * 60 * 24);
                        $daily_rate = getDailyRate($staff['position']);
                        $total_amount = $daily_rate * $days;
                        $advance_paid = $booking['amount'];
                        $balance = $total_amount - $advance_paid;
                        ?>
                        <div class="detail-item">
                            <span class="label">Daily Rate:</span>
                            <span class="value">Rs. <?php echo number_format($daily_rate, 2); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Number of Days:</span>
                            <span class="value"><?php echo $days; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Total Amount:</span>
                            <span class="value">Rs. <?php echo number_format($total_amount, 2); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Advance Paid:</span>
                            <span class="value">Rs. <?php echo number_format($advance_paid, 2); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Balance:</span>
                            <span class="value">Rs. <?php echo number_format($balance, 2); ?></span>
                        </div>
                    </div>
                </div>

                <div class="section terms-conditions">
                    <h3>Terms & Conditions</h3>
                    <ol>
                        <li>The balance payment must be made directly to the company before the service end date.</li>
                        <li>Any additional days will be charged at the same daily rate.</li>
                        <li>The caretaker's schedule cannot be modified without prior company approval.</li>
                        <li>Any complaints regarding the service must be reported to the company immediately.</li>
                        <li>The company reserves the right to change the assigned caretaker if necessary.</li>
                        <li>Cancellation policy applies as per the service agreement.</li>
                    </ol>
                </div>

                <div class="section signature-section">
                    <div class="signature">
                        <div class="sign-line"></div>
                        <p>Client Signature</p>
                    </div>
                    <div class="signature">
                        <div class="sign-line"></div>
                        <p>Company Representative</p>
                    </div>
                </div>

                <div class="actions">
                    <button onclick="window.print()" class="print-btn">Print Report</button>
                </div>
            </div>
            <?php else: ?>
            <div class="error-message">
                <p>No booking information found for this client.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/report.js"></script>
</body>
</html>

<?php
function getDailyRate($position) {
    switch(strtolower($position)) {
        case 'nurses':
            return 2500;
        case 'caregivers':
            return 2000;
        case 'daily':
            return 1500;
        default:
            return 1000;
    }
}
?>
