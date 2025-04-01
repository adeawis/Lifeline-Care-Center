<?php
// view_invoices.php
session_start();
include 'db.php';

// Make sure user is logged in
if(!isset($_SESSION['email'])) {
    header('Location: indexWelcome.php');
    exit();
}

$email = $_SESSION['email'];

// Fetch all invoices for the logged-in client
$query = "SELECT * FROM invoices WHERE email = ? ORDER BY invoice_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Invoices</title>
    <style>
        .invoice-container {
            max-width: 1130px;
            margin: 0 auto;
            padding: 20px;
            font-family: "Poppins", sans-serif;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .invoice-table th {
            background-color: #D54A6A;
            color: white;
        }
        .invoice-table tr:hover {
            background-color: #f5f5f5;
        }
        .status-unpaid {
            color: #dc3545;
        }
        .status-paid {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <h1>My Invoices</h1>
        
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice Date</th>
                    <th>Service Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('Y-m-d', strtotime($row['invoice_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                    <td><?php echo number_format($row['amount'], 2); ?></td>
                    <td class="status-<?php echo strtolower($row['status']); ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </td>
                    <td>
                        <a href="download_invoice.php?id=<?php echo $row['id']; ?>" 
                           class="btn btn-primary">Download</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>