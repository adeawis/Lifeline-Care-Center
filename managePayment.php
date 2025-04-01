<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--==title========================-->
    <title>Admin | Lifeline Care Center</title>
    <!--==Fav-icon=====================-->
    <link rel="shortcut icon" href="images/titleIcon.png">
    <!--==CSS==========================-->
    <link rel="stylesheet" href="css/adminManageStaff.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--==Font-Awesome-for-icons=====-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Link Swiper's CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />
    <style>
        .invoice-btn {
    background-color: #D54A6A;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
}

.invoice-btn:hover {
    background-color: #A53954 ;
}

.disabled-btn {
    background-color: #888888 !important;
    cursor: not-allowed !important;
}
    </style>
</head>
<body>
    <div class="side-menu">
        <div class="company-name">
            <h1>Lifeline Care Center</h1>
        </div>
        <ul>
            <li><i class="fa-solid fa-chart-line"></i>&nbsp; <span><a href="adminHome.php">Dashboard</a></span></li>
            <li><i class="fa-solid fa-id-card-clip"></i>&nbsp; <span><a href="manageCaretakers.php">Manage Caretakers</a></span></li>
            <li><i class="fa-solid fa-user-gear"></i>&nbsp; <span><a href="manageClient.php">Manage Clients</a></span></li>
            <li><i class="fa-solid fa-clipboard"></i>&nbsp; <span><a href="manageBooking.php">Bookings</a></span></li>
            <li class="active"><i class="fa-solid fa-credit-card"></i>&nbsp; <span><a href="managePayment.php">Payment & Billing</a></span></li>
            <li><i class="fa-solid fa-bell"></i>&nbsp; <span><a href="adminNotify_alerts.php">Notifications & Alerts</a></span></li>
            <li><i class="fa-solid fa-screwdriver-wrench"></i>&nbsp; <span><a href="userManagement.php">User Management</a></span></li>
            <li><i class="fa-solid fa-question"></i>&nbsp; <span><a href="adminHelp.php">Help</a></span></li>
        </ul>
    </div>
    <div class="container-panel">
        <div class="headerAdmin">
            <div class="nav">
                <div class="search">
                    <input type="text" placeholder="Search here">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="user">
                    <img src="images/notifications.png" alt="">
                    <div class="img-case" id="userImage">
                        <img src="images/user.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
        Notifications
        </div>
        <div class="notification-list">
        <div class="notification-item unread">
            <span class="notification-dot"></span>
            <div>New message received</div>
        </div>
        <div class="notification-item unread">
            <span class="notification-dot"></span>
            <div>Your post was liked</div>
        </div>
        <div class="notification-item">
            <div>Welcome to the dashboard!</div>
        </div>
    </div>
    </div>
    <div class="popup-logout" id="logoutPopup">
            <p>Are you sure you want to log out?</p>
            <button class="btn confirm-logout">Logout</button>
            <button class="btn cancel-logout">Cancel</button>
        </div>
        <div class="content">
                <div class="content-3">
                    <div class="staff-details">
                        <div class="title">
                            <h2>Payment Details</h2>
                            
                            
                        </div>
                        <table>
                            <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Actions</th>
                            </tr>

                            <?php
                            // Query to fetch data from the database
                            $query = "SELECT * FROM booking";
                            $result = $conn->query($query);

                            if ($result) {
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                        echo "<td>
                                              <button type='button' class='invoice-btn ' data-client-id='" . urlencode($row["id"]) . "'>Invoice</button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No records found.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Error: " . $conn->error . "</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <script>
        // Get the elements
        const userImage = document.getElementById('userImage');
        const logoutPopup = document.getElementById('logoutPopup');
        const deleteBtns = document.querySelectorAll('.delete-btn');

        // Toggle popup visibility
        userImage.addEventListener('click', () => {
            logoutPopup.style.display = logoutPopup.style.display === 'block' ? 'none' : 'block';
        });

        // Hide popup when clicking cancel button
        document.querySelector('.cancel-logout').addEventListener('click', () => {
            logoutPopup.style.display = 'none';
        });

        // Add functionality for the logout button
        document.querySelector('.confirm-logout').addEventListener('click', () => {
            alert('Logged out successfully!');
            window.location.href = 'adminLogin.html';
        });


        document.addEventListener('DOMContentLoaded', function() {
            const notificationImg = document.querySelector('.user img[src*="notifications"]');
            const panel = document.getElementById('notificationPanel');

            notificationImg.addEventListener('click', function(e) {
                e.preventDefault();
                panel.classList.toggle('show');

                const unreadItems = document.querySelectorAll('.notification-item.unread');
                unreadItems.forEach(item => {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.notification-dot');
                    if (dot) {
                        dot.remove();
                    }
                });
            });

            document.addEventListener('click', function(event) {
                if (!notificationImg.contains(event.target) && !panel.contains(event.target)) {
                    panel.classList.remove('show');
                }
            });
        });

        // Add event listeners to all invoice buttons

        document.querySelectorAll('.invoice-btn').forEach(button => {
        button.addEventListener('click', function(e) {
        e.preventDefault();
        const clientId = this.dataset.clientId;
        const button = this;
        
        // First check if invoice already exists
        fetch('checkInvoiceStatus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `client_id=${clientId}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.exists) {
                // Invoice already exists
                alert('Invoice has already been generated for this client.');
                // Optionally disable the button or change its appearance
                button.disabled = true;
                button.textContent = 'Already Invoiced';
                button.classList.add('disabled-btn');
            } else {
                // Invoice doesn't exist, proceed with generation
                fetch('invoiceGenerate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `generate_invoice=1&client_id=${clientId}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response:', data);
                    if(data.status === 'success') {
                        alert(data.message);
                        button.disabled = true;
                        button.textContent = 'Invoiced';
                        button.classList.add('disabled-btn');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while generating the invoice');
                });
            }
        })
        .catch(error => {
            console.error('Error checking invoice:', error);
            alert('An error occurred while checking existing invoices');
        });
    });
});

    </script>
</body>
</html>
