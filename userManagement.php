<?php include 'db.php';?>



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
    <link rel="stylesheet" href="css/adminPanel.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--==Font-Awesome-for-icons=====-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Link Swiper's CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />
    

    <style>
.admin-profile {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 30px;
    padding: 30px;
}

.profile-photo img {
    width: 80px;  /* Reduced from 120px */
    height: 80px; /* Reduced from 120px */
    border-radius: 50%;
    border: 2px solid #007bff;
}

/* Also adjust the margin to balance the layout */
.profile-photo {
    margin-right: 25px; /* Reduced from 40px to maintain proportions */
    position: relative;
}

.status-dot {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 12px;
    height: 12px;
    background: #22c55e;
    border: 2px solid white;
    border-radius: 50%;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 15px;
}

.info-item i {
    color: #db2777;
    font-size: 20px;
}

.info-item div {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item strong {
    color: #374151;
    font-size: 14px;
}

.info-item p {
    color: #6b7280;
    margin: 0;
}

.responsibilities-section {
    margin-top: 30px;
    border-top: 1px solid #e5e7eb;
    padding-top: 20px;
}

.responsibilities-section h3 {
    color: #374151;
    font-size: 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.responsibilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.responsibility-box {
    background: #f9fafb;
    padding: 15px;
    border-radius: 6px;
}

.responsibility-box h4 {
    color: #374151;
    margin-bottom: 10px;
}

.responsibility-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.responsibility-box li {
    color: #6b7280;
    margin-bottom: 5px;
    padding-left: 15px;
    position: relative;
}

.responsibility-box li:before {
    content: "•";
    color: #db2777;
    position: absolute;
    left: 0;
}

.update-btn {
    display: inline-block;
    background: #db2777;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    margin-top: 20px;
    transition: background-color 0.2s;
}

.update-btn:hover {
    background: #be185d;
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
            <li><i class="fa-solid fa-credit-card"></i>&nbsp; <span><a href="managePayment.php">Payment & Billing</a></span></li>
            <li><i class="fa-solid fa-bell"></i>&nbsp; <span><a href="adminNotify_alerts.php">Notifications & Alerts</a></span></li>
            <li class="active"><i class="fa-solid fa-screwdriver-wrench"></i>&nbsp; <span><a href="userManagement.php">User Management</a></span></li>
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
                    
                    <img src="images/notifications.png" alt="notify bell">
                    <div class="img-case" id="userImage">
                        <img src="images/user.png" alt="admin profile-pic">
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="popup-logout" id="logoutPopup">
            <p>Are you sure you want to log out?</p>
            <button class="btn confirm-logout">Logout</button>
            <button class="btn cancel-logout">Cancel</button>
        </div>

        <div class="content">
    <?php
    // Fetch admin details
    $sql = "SELECT * FROM management WHERE role = 'admin' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <div class="admin-profile">
            <div class="admin-container"> 
            <div class="profile-photo">
                    <img src="<?php echo $row['profile_photo'] ? 'uploads/' . $row['profile_photo'] : 'images/user.png'; ?>" alt="Profile Photo">
                    <span class="status-dot"></span>
                </div>

                
                <div class="profile-details">
                    <h2><?php echo $row['name']; ?></h2>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <p><?php echo $row['email'] ?? ''; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Phone</strong>
                                <p><?php echo $row['phone'] ; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-location-dot"></i>
                            <div>
                                <strong>Address</strong>
                                <p><?php echo $row['address'] ; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-shield"></i>
                            <div>
                                <strong>Role</strong>
                                <p><?php echo ucfirst($row['role']); ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <strong>Username</strong>
                                <p><?php echo $row['username']; ?></p>
                            </div>
                        </div>

                        <div class="info-item">
                        <i class="fa-solid fa-lock"></i>
                            <div>
                                <strong>Password</strong>
                                <p><?php echo $row['password']; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <strong>Join Date</strong>
                                <p><?php echo $row['join_date']; ?></p>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <strong>Last Login</strong>
                                <p><?php echo $row['last_login'] ?? ''; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="responsibilities-section">
                        <h3><i class="fas fa-tasks"></i> Responsibilities</h3>
                        <div class="responsibilities-grid">
                            <div class="responsibility-box">
                                <h4>Primary Duties</h4>
                                <ul>
                                    <li>User Management & Access Control</li>
                                    <li>System Configuration</li>
                                    <li>Security Monitoring</li>
                                </ul>
                            </div>
                            <div class="responsibility-box">
                                <h4>Secondary Duties</h4>
                                <ul>
                                    <li>Staff Training</li>
                                    <li>Report Generation</li>
                                    <li>Emergency Response</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="update-btn-container">
                        <a href="userManagementUpdate.php" class="update-btn">Update Profile</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<p>No admin details found!</p>";
    }
    ?>
</div>



    <script>
        // logout popup js code

        const userImage = document.getElementById('userImage');
        const logoutPopup = document.getElementById('logoutPopup');

        userImage.addEventListener('click', () => {
        logoutPopup.style.display = logoutPopup.style.display === 'block' ? 'none' : 'block';
    });

        document.querySelector('.cancel-logout').addEventListener('click', () => {
        logoutPopup.style.display = 'none';
    });

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

setInterval(() => {
        fetch('fetch_notifications.php')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notification-list');
                list.innerHTML = '';
                data.forEach(notification => {
                    const li = document.createElement('li');
                    li.textContent = notification.message;
                    list.appendChild(li);
                });
            });
    }, 5000); // Fetch every 5 seconds

    </script>
    
</body>
</html>