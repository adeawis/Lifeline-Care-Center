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
    <!-- Previous head content remains the same -->
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
            color: white;
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
        .staff-profile form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
            margin-left: 48px;
        }
        .profile-photo img{
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin-bottom: 10px;
        }

        .profile-photo input {
            margin-top: 10px;
            font-size: 14px;
            display: block;
        }

        .profile-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            width: 100%;
            max-width: 800px;
        }

        .profile-details label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            display: block;
        }

        .profile-details input,
        .profile-details select,
        .profile-details textarea {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .profile-details textarea {
            height: 100px;
            resize: vertical;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        button[type="submit"] {
            background-color: #D54A6A;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: fit-content;
        }

        button[type="submit"]:hover {
            background-color: #AA3B55;
        }

        .success-msg, .error-msg {
            grid-column: 1 / -1;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <!-- sidebar -->
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


    <div class="content">
        <div class="staff-profile">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "lifeline_carecenter";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle update form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get form data
                $full_name = $_POST['full_name'];
                $age = $_POST['age'];
                $location = $_POST['location'];
                $phone = $_POST['phone'];
                $gender = $_POST['gender'];
                $position = $_POST['position'];
                $experience = $_POST['experience'];
                $password = $_POST['password'];
                $availability = $_POST['availability'];
                $email = $_POST['email'];

                // File upload logic
                if (!empty($_FILES['profile_photo']['name'])) {
                    $targetDir = __DIR__ . "uploads/";
                    
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    $fileExtension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
                    $newFilename = uniqid() . '.' . $fileExtension;
                    $targetFilePath = $targetDir . $newFilename;

                    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFilePath)) {
                        $stmt = $conn->prepare("UPDATE staffreg SET email = ?,age = ?,location = ?,phone = ?,gender = ?,position = ?,experience = ?,availability = ? , password = ?, profile_photo = ? WHERE full_name = ?");
                        $stmt->bind_param("sisssssssss", $email, $age, $location,$phone, $gender, $position, $experience, $availability, $password, $newFilename, $full_name);
                    } else {
                        echo "<p class='error-msg'>Error uploading file</p>";
                    }
                } else {
                    $stmt = $conn->prepare("UPDATE staffreg SET email = ?,age = ?,location = ?,phone = ?,gender = ?,position = ?,experience = ?,availability = ? , password = ? WHERE full_name = ?");
                    $stmt->bind_param("sissssssss", $email, $age, $location,$phone, $gender, $position, $experience, $availability, $password, $full_name);
                }

                if ($stmt->execute()) {
                    echo "<p class='success-msg'>Profile updated successfully!</p>";
                } else {
                    echo "<p class='error-msg'>Error updating profile: " . $conn->error . "</p>";
                }
            }

            // Fetch staff details
            $sql = "SELECT * FROM staffreg WHERE full_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['full_name']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                echo "
                <form method='POST' enctype='multipart/form-data'>
                    <div class='profile-details'>
                        <div>
                            <label for='name'>Name:</label>
                            <input type='text' id='name' name='full_name' value='" . htmlspecialchars($row['full_name']) . "' readonly>
                        </div>

                        <div>
                            <label for='email'>Email:</label>
                            <input type='email' id='email' name='email' value='" . htmlspecialchars($row['email']) . "' required>
                        </div>

                        <div>
                            <label for='phone'>Phone:</label>
                            <input type='tel' id='phone' name='phone' value='" . htmlspecialchars($row['phone']) . "' required>
                        </div>

                        <div>
                            <label for='phone'>Gender:</label>
                            <select id='gender' name='gender' required>
                            <option value='male' " . ($row['gender'] == 'male' ? 'selected' : '') . ">Male</option>
                            <option value='female' " . ($row['gender'] == 'female' ? 'selected' : '') . ">Female</option>
                            </select>
                        </div>

                        <div>
                            <label for='position'>Position:</label>
                            <select id='position' name='position' required>
                                <option value='nurses' " . ($row['position'] == 'nurses' ? 'selected' : '') . ">Nurse</option>
                                <option value='caregivers' " . ($row['position'] == 'caregivers' ? 'selected' : '') . ">Caregiver</option>
                                <option value='daily' " . ($row['position'] == 'daily' ? 'selected' : '') . ">Daily Basis Worker</option>
                            </select>
                        </div>

                        <div>
                            <label for='availability'>Availability:</label>
                            <select id='status' name='availability' required>
                                <option value='available' " . ($row['availability'] == 'available' ? 'selected' : '') . ">Available</option>
                                <option value='unavailable' " . ($row['availability'] == 'unavailable' ? 'selected' : '') . ">Unavailable</option>
                            </select>
                        </div>

                        <div>
                            <label>Location:</label>
                            <input type='text' id='join_date' name='location' value='" . htmlspecialchars($row['location']) . "' required>
                        </div>

                        <div>
                            <label>Age:</label>
                            <input type='text' name='age' value='" . htmlspecialchars($row['age']) . "' required>
                        </div>

                        <div>
                            <label>Experience:</label>
                            <input type='text' name='experience' value='" . htmlspecialchars($row['experience']) . "' required>
                        </div>

                        <div>
                            <label>Password:</label>
                            <input type='text' name='password' value='" . htmlspecialchars($row['password']) . "' required>
                        </div>

                        

                        <div class='full-width'>
                            <button type='submit'>Update Profile</button>
                        </div>
                    </div>
                </form>";
            } else {
                echo "<p class='error-msg'>No staff details found!</p>";
            }

            $conn->close();
?>
        </div>
    </div>
</body>
</html>