<?php
session_start();
if (!isset($_SESSION['full_name'])) {
    header('Location: staffLogin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff | Lifeline Care Center</title>
  <link rel="shortcut icon" href="images/titleIcon.png">

   <!-- fonts connecter-->
   <style>
      @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
      </style>

      <!-- fontawesome icons connecter-->
  <script src="https://kit.fontawesome.com/70d0595457.js" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        :root {
            --primary-color: #DB4C77;
            --sidebar-width: 300px;
        }

        body {
            background: #f0f2f5;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            min-height: 100vh;
            padding: 20px;
            color: white;
            position: fixed;
        }
        a{
            text-decoration: none;
            color: white;
        }
        .logo {
            font-size: 24px;
            margin-bottom: 40px;
            color: #72E8D2;
            font-weight: 700;
        }

        .nav-item {
            padding: 18px 15px;
            margin: 5px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .notification-icon {
            position: relative;
            cursor: pointer;
            font-size: 26px;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Profile Section */
        .profile-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: #f0f0f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: var(--primary-color);
        }

        .badge {
            background: #e3f2fd;
            color: var(--primary-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin: 5px;
            display: inline-block;
        }

        /* Schedule Table */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        /* Reviews */
        .review-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .stars {
            color: gold;
            margin: 5px 0;
        }

        /* Notifications Panel */
        .notifications-panel {
            position: absolute;
            top: 60px;
            right: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 300px;
            display: none;
            z-index: 1000;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .show {
            display: block;
        }
        #user-container {
    position: relative;
    display: inline-block;
}

#user-icon {
    width: 50px;
    height: 50px;
    cursor: pointer;
}

#logout-popup {
    position: absolute;
    top: 60px;
    right: 0;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 10px;
    z-index: 1000;
    text-align: center;
}

#logout-popup.hidden {
    display: none;
}

#logout-popup button {
    margin: 5px;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

#logout-popup button:hover {
    background-color: #ddd;
}

.container {
    max-width: 80%;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

h1 {
    text-align: center;
    color: #D54A6A;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    background: #e2e2e2;
    margin: 5px 0;
    padding: 10px;
    border-radius: 5px;
}

.schedule-item {
    background: #f0f0f0;
    margin: 10px 0;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #D54A6A;
}
.no-schedule {
    text-align: center;
    color: #666;
    padding: 20px;
}
    </style>
</head>
<body>
     <!-- Sidebar -->
     <div class="sidebar">
        <div class="logo">Lifeline Care Center</div>
        <div class="nav-item"><i class="fas fa-user"></i><a href="staffProfile.php">My Profile</a></div>
        <div class="nav-item"><i class="fas fa-calendar"></i><a href="staff_scheduleView.php">Schedule</a></div>
        <div class="nav-item"><i class="fas fa-star"></i><a href="staffReviews.php">Reviews</a></div>
        <div class="nav-item"><i class="fas fa-credit-card"></i><a href="staffPayment.php">Payments</a></div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h2>Welcome Back, 
            <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </h2>
            <div style="display: flex; gap: 20px; align-items: center;">
                <div class="notification-icon" id="notificationIcon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <i class="fas fa-user-circle" style="font-size: 26px; cursor: pointer;" id="usericon"></i>
                <div id="logout-popup" class="hidden">
        <p>Are you sure you want to log out?</p>
        <button id="logout-confirm">Log Out</button>
        <button id="logout-cancel">Cancel</button>
    </div>
    </div>
    </div>
    <div class="container">
        <h1>My Schedules</h1>
        <div id="schedule"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_schedule.php')
                .then(response => response.json())
                .then(data => {
                    const scheduleDiv = document.getElementById('schedule');
                    if (data.length > 0) {
                        let scheduleHTML = '<div class="schedule-list">';
                        data.forEach(schedule => {
                            scheduleHTML += `
                                <div class="schedule-item">
                                    <h3>Schedule ID: ${schedule.schedule_id}</h3>
                                    <p>Service Type: ${schedule.service_type}</p>
                                    <p>Client Name: ${schedule.client_name}</p>
                                    <p>Start Date: ${schedule.start_date}</p>
                                    <p>End Date: ${schedule.end_date}</p>
                                </div>`;
                        });
                        scheduleHTML += '</div>';
                        scheduleDiv.innerHTML = scheduleHTML;
                    } else {
                        scheduleDiv.innerHTML = '<div class="no-schedule">No scheduled appointments found.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching schedule:', error);
                    document.getElementById('schedule').innerHTML = '<div class="no-schedule">Error loading schedule. Please try again later.</div>';
                });
        });

        document.addEventListener("DOMContentLoaded", () => {
    const userIcon = document.getElementById("usericon");
    const logoutPopup = document.getElementById("logout-popup");
    const logoutConfirm = document.getElementById("logout-confirm");
    const logoutCancel = document.getElementById("logout-cancel");

    // Toggle popup visibility when the user icon is clicked
    userIcon.addEventListener("click", () => {
        logoutPopup.classList.toggle("hidden");
    });

    // Hide popup when "Cancel" is clicked
    logoutCancel.addEventListener("click", () => {
        logoutPopup.classList.add("hidden");
    });

    // Redirect to logout script when "Log Out" is clicked
    logoutConfirm.addEventListener("click", () => {
        window.location.href = "staffLogin.html"; // Replace with your logout script path
    });

    // Close popup when clicking outside of it
    window.addEventListener("click", (event) => {
        if (!document.getElementById("user-container").contains(event.target)) {
            logoutPopup.classList.add("hidden");
        }
    });
});
    </script>
</body>
</html>