<?php
session_start();

include 'db.php'; // Add this line

if (!isset($_SESSION['email'])) {
    header('Location: indexWelcome.php');
    exit();
}


// Fetch user details
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        
        $update_stmt = $conn->prepare("UPDATE users SET full_name = ?, password = ?, phone = ? WHERE email = ?");
        $update_stmt->bind_param("ssss", $full_name, $password, $phone, $email);
        
        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            echo "<script>
                setTimeout(function() {
                    document.querySelector('.alert-success').style.display = 'none';
                }, 3000); // Message will disappear after 3 seconds
            </script>";
            // Refresh user data
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $error_message = "Error updating profile!";
            echo "<script>
                setTimeout(function() {
                    document.querySelector('.alert-success').style.display = 'none';
                }, 3000); // Message will disappear after 3 seconds
            </script>";
        }
    }
}


// Fetch booking details for this user
$booking_stmt = $conn->prepare("SELECT b.*, s.staff_name 
                               FROM booking b 
                               LEFT JOIN schedule_admin s ON b.email = s.client_name 
                               WHERE b.email = ?
                               ORDER BY b.start_date DESC");
$booking_stmt->bind_param("s", $email);
$booking_stmt->execute();
$bookings = $booking_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Lifeline Care Center</title>
    <link rel="shortcut icon" href="images/titleIcon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your existing CSS here -->
     <style>
        /* General body and container styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    position: relative;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Header styling */
h4 {
    font-size: 1.5rem;
    color: #D54A6A;
    margin-bottom: 20px;
}

/* Card styling */
.card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #fff;
}

.card-body {
    padding: 20px;
}

.card-body.media {
    display: flex;
    align-items: center;
}

.card-body .ui-w-80 {
    width: 80px;
    height: 86px;
    border-radius: 50%;
    border: 2px solid #ccc;
    object-fit: cover;
}

/* Tab navigation */
.list-group-item {
    border: none;
    padding: 15px 20px;
    font-size: 1rem;
    color: #333;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.list-group-item:hover,
.list-group-item.active {
    background-color: #D54A6A;
    color: #fff;
}

/* Form inputs */
.form-control {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    font-size: 0.95rem;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    border-color: #D54A6A;
    box-shadow: none;
}

/* Labels */
.form-label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

/* Buttons */
.btn {
    font-size: 0.9rem;
    padding: 8px 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.btn-outline-primary {
    color: #D54A6A;
    border: 1px solid #D54A6A;
    background-color: transparent;
}

.btn-outline-primary:hover {
    background-color: #D54A6A;
    color: #fff;
}

.btn-default {
    background-color: #f8f9fa;
    color: #333;
    border: 1px solid #ccc;
}

.btn-default:hover {
    background-color: #e0e0e0;
    color: #333;
}

.btn-primary {
    background-color: #D54A6A;
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background-color: #D54A6A;
}

.link-edit, .link-cancel {
    color: #D54A6A;
    text-decoration: none;
    margin-right: 7px;
}

/* Additional info text */
.text-light {
    color: #6c757d !important;
}

/* Miscellaneous */
.text-right {
    text-align: right;
}

.mt-1 {
    margin-top: 5px;
}

.mt-3 {
    margin-top: 15px;
}

.mb-4 {
    margin-bottom: 20px;
}

.border-light {
    border-color: #e0e0e0 !important;
}

.row-bordered {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.row-border-light .col-md-3 {
    border-right: 1px solid #e0e0e0;
}
.close-btn{
            position: absolute;
            font-weight: 800;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: white;
            color: black;
            text-align: center;
            line-height: 19px;
            border-radius: 15px;
            cursor: pointer;
            text-decoration: none;
        }
        .close-btn:hover{
            background: #f0f0f0;
        }
        .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 60%;
        text-align: center;
        position: relative;
    }
     </style>
</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <a href="indexWelcome.php" class="close-btn">&times;</a>
        <h4 class="font-weight-bold py-3 mb-4">User Profile</h4>

        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">My Profile</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Caretaker Info</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#check-booking-details">Check Bookings</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade active show" id="account-general">
                            <form method="POST" action="">
                                <div class="card-body media align-items-center">
                                    
                                    <div class="media-body ml-4">
                                        
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="text" class="form-control mb-1" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="text" class="form-control" name="password" value="<?php echo htmlspecialchars($user['password']); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit" name="update_profile" class="btn btn-primary">Save changes</button>
                                        <button type="button" name="delete_profile" class="btn btn-danger" onclick="redirectToDeletePage()">Delete Account</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Caretaker Info Tab -->
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <h5>Current Caretaker Assignment</h5>
                                    <?php
                                        $caretaker_stmt = $conn->prepare("
                                        SELECT sa.*, sr.position, sr.phone 
                                        FROM schedule_admin sa
                                        LEFT JOIN staffreg sr ON sa.staff_name = sr.full_name 
                                        LEFT JOIN users u ON sa.client_name = u.full_name
                                        WHERE u.email = ?
                                    ");
                                    
                                    $caretaker_stmt->bind_param("s", $_SESSION['email']);
                                    $caretaker_stmt->execute();
                                    $caretaker_result = $caretaker_stmt->get_result();
                                    
                                    if ($caretaker_result->num_rows > 0) {
                                        while ($caretaker = $caretaker_result->fetch_assoc()) {
                                            ?>
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($caretaker['staff_name']); ?></p>
                                                    <p><strong>Position:</strong> <?php echo htmlspecialchars($caretaker['position']); ?></p>
                                                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($caretaker['phone']); ?></p>
                                                    <p><strong>Service Type:</strong> <?php echo htmlspecialchars($caretaker['service_type']); ?></p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "<p>No active caretaker assigned.</p>";
                                    }
                                    ?>
                                </div>
                                <div class="form-group mt-4">
    <h5>Rate Your Caretaker</h5>
    <?php if ($caretaker_result->num_rows > 0): ?>
        <?php 
        $caretaker_result->data_seek(0);
        while ($caretaker = $caretaker_result->fetch_assoc()): 
        ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h6><?php echo htmlspecialchars($caretaker['staff_name']); ?></h6>
                    <form action="submit_review.php" method="POST">
                        <input type="hidden" name="caretaker_name" value="<?php echo htmlspecialchars($caretaker['staff_name']); ?>">
                        <input type="hidden" name="client_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        <input type="hidden" name="client_email" value="<?php echo htmlspecialchars($user['email']); ?>">
                        
                        <div class="form-group">
                            <label>Rating</label>
                            <select name="rating" class="form-control" required>
                                <option value="">Select Rating</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Very Good</option>
                                <option value="3">3 - Good</option>
                                <option value="2">2 - Fair</option>
                                <option value="1">1 - Poor</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Your Review</label>
                            <textarea name="review_text" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
<div class="form-group">
    <label class="form-label">Get Booking Report</label>
    <button onclick="openReportPopup('<?php echo urlencode($email); ?>')" class="btn btn-primary">View Report</button>
</div>

<!-- Modal for Report -->
<div id="reportModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeReportPopup()">&times;</span>
        <h2>Booking Report</h2>
        <iframe id="reportFrame" src="" style="width: 100%; height: 500px; border: none;"></iframe>
    </div>
</div>
                            </div>
                        </div>

                        <!-- Bookings Tab -->
                        <div class="tab-pane fade" id="check-booking-details">
                            <div class="card-body pb-2">
                                <h5>Your Bookings</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Service Type</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Amount Paid</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($booking = $bookings->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($booking['service_type']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                                                    <td>Rs. <?php echo htmlspecialchars($booking['amount']); ?></td>
                                                    <td>
                                                        <?php 
                                                        $today = new DateTime();
                                                        $end = new DateTime($booking['end_date']);
                                                        echo ($end < $today) ? 'Completed' : 'Active';
                                                        ?>
                                                    </td>
                                                    <td>
                                                    <a href="editBookings.php?id=<?php echo urlencode($booking['id']); ?>" class="link-edit">Edit</a>
                                                    <a href="client_CancelBooking.php?id=<?php echo urlencode($booking['id']); ?>" class="link-cancel">Cancel</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
    // Get the tab parameter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get("tab");

    if (tab === "bookings") {
        var tabElement = new bootstrap.Tab(document.querySelector('a[href="#check-booking-details"]'));
        tabElement.show(); // Activate the tab
    }
});

function openReportPopup(email) {
        document.getElementById("reportFrame").src = "viewUserInvoice.php?email=" + email;
        document.getElementById("reportModal").style.display = "flex";
    }

    function closeReportPopup() {
        document.getElementById("reportModal").style.display = "none";
        document.getElementById("reportFrame").src = ""; // Clear iframe to stop loading
    }

    function redirectToDeletePage() {
        // Redirect to the delete account page
        window.location.href = 'userDelete_Account.php';
    }
</script>

</body>
</html>