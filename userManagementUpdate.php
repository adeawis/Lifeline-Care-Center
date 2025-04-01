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
        .admin-profile form {
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
            background-color: #007bff;
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
            background-color: #0056b3;
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
    <!-- Previous navigation and header content remains the same -->
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
            <li><i class="fa-solid fa-bell"></i>&nbsp; <span><a href="">Notifications & Alerts</a></span></li>
            <li class="active"><i class="fa-solid fa-screwdriver-wrench"></i>&nbsp; <span><a href="userManagement.php">User Management</a></span></li>
            <li><i class="fa-solid fa-question"></i>&nbsp; <span><a href="">Help</a></span></li>
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
        <div class="admin-profile">
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
                $name = $_POST['name'];
                $role = $_POST['role'];
                $status = $_POST['status'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $responsibilities = $_POST['responsibilities'];
                $last_login = date('Y-m-d H:i:s'); // Current timestamp for last login

                // File upload logic
                if (!empty($_FILES['profile_photo']['name'])) {
                    $targetDir = __DIR__ . "/uploads/";
                    
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    $fileExtension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
                    $newFilename = uniqid() . '.' . $fileExtension;
                    $targetFilePath = $targetDir . $newFilename;

                if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFilePath)) {
                        $stmt = $conn->prepare("UPDATE management SET name=?, role=?, status=?, profile_photo=?, email=?, phone=?, address=?, responsibilities=?, last_login=?, join_date=? WHERE username='admin'");
                        $stmt->bind_param("ssssssssss", $name, $role, $status, $newFilename, $email, $phone, $address, $responsibilities, $last_login, $_POST['join_date']);
                    } else {
                        echo "<p class='error-msg'>Error uploading file</p>";
                    }
                } else {
                    $stmt = $conn->prepare("UPDATE management SET name=?, role=?, status=?, email=?, phone=?, address=?, responsibilities=?, last_login=?, join_date=? WHERE username='admin'");
                    $stmt->bind_param("sssssssss", $name, $role, $status, $email, $phone, $address, $responsibilities, $last_login, $_POST['join_date']);
                }

                if ($stmt->execute()) {
                    echo "<p class='success-msg'>Profile updated successfully!</p>";
                } else {
                    echo "<p class='error-msg'>Error updating profile: " . $conn->error . "</p>";
                }
            }

            // Fetch admin details
            $sql = "SELECT * FROM management WHERE role = 'admin' LIMIT 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                echo "
                <form method='POST' enctype='multipart/form-data'>
                    <div class='profile-photo'>
                        <img src='uploads/" . htmlspecialchars($row['profile_photo']) . "' alt='Profile Photo'>
                        <input type='file' name='profile_photo'>
                    </div>
                    <div class='profile-details'>
                        <div>
                            <label for='name'>Name:</label>
                            <input type='text' id='name' name='name' value='" . htmlspecialchars($row['name']) . "' required>
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
                            <label for='role'>Role:</label>
                            <select id='role' name='role' required>
                                <option value='Admin' " . ($row['role'] == 'Admin' ? 'selected' : '') . ">Admin</option>
                                <option value='Manager' " . ($row['role'] == 'Manager' ? 'selected' : '') . ">Manager</option>
                            </select>
                        </div>

                        <div>
                            <label for='status'>Status:</label>
                            <select id='status' name='status' required>
                                <option value='Active' " . ($row['status'] == 'Active' ? 'selected' : '') . ">Active</option>
                                <option value='Inactive' " . ($row['status'] == 'Inactive' ? 'selected' : '') . ">Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label>Join Date:</label>
                            <input type='date' id='join_date' name='join_date' value='" . htmlspecialchars($row['join_date']) . "' required>
                        </div>

                        <div>
                            <label>Last Login:</label>
                            <input type='text' value='" . htmlspecialchars($row['last_login']) . "' readonly>
                        </div>

                        <div class='full-width'>
                            <label for='address'>Address:</label>
                            <textarea id='address' name='address' required>" . htmlspecialchars($row['address']) . "</textarea>
                        </div>

                        <div class='full-width'>
                            <label for='responsibilities'>Responsibilities:</label>
                            <textarea id='responsibilities' name='responsibilities' required>" . htmlspecialchars($row['responsibilities']) . "</textarea>
                        </div>

                        <div class='full-width'>
                            <button type='submit'>Update Profile</button>
                        </div>
                    </div>
                </form>";
            } else {
                echo "<p class='error-msg'>No admin details found!</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>