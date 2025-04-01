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

// Fetch scheduling details
try {
    $sql = "SELECT * FROM scheduling";
    $stmt = $pdo->query($sql);
    $schedulingData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching scheduling data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduling Details</title>
    <style>

    h1{
        color: #D54A6A;
        font-size: 30px;
    }

       /* General table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 19px;
    font-family: Arial, sans-serif;
    text-align: left;
}

/* Table header styling */
thead tr {
    background-color: #f4f4f4;
    border-bottom: 2px solid #ccc;
}

thead th {
    padding: 15px;
    text-align: left;
    background: #D54A6A;
    color: #fff;
}

/* Table body styling */
tbody tr {
    border-bottom: 1px solid #ddd;
    transition: background-color 0.3s;
}

tbody tr:hover {
    background-color: #f9f9f9;
}

tbody td {
    padding: 15px;
    font: 1.2rem sans-serif;
}

/* No data row styling */
tbody tr td[colspan="6"] {
    text-align: center;
    color: #999;
    font-style: italic;
}

/* Responsive styling */
@media screen and (max-width: 600px) {
    table {
        font-size: 18px;
    }

    thead {
        display: none;
    }

    tbody tr {
        display: block;
        margin-bottom: 15px;
    }

    tbody td {
        display: block;
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: calc(50% - 20px);
        text-align: left;
        font-weight: bold;
    }
}

    </style>
</head>
<body>

<h1>Scheduling Details</h1>

<table>
    <thead>
        <tr>
            <th>Service ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($schedulingData)): ?>
            <?php foreach ($schedulingData as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['serviceId']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No scheduling details found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
