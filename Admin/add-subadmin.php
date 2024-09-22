<?php
session_start();
include './include/db_connection.php';

//if (!isset($_SESSION['fullname']) || !isset($_SESSION['username']) || !isset($_SESSION['reg_date'])) {
  //  header("Location: ../User/login.php");
    //exit();
//}
$fullname = $_SESSION['fullname'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fullname = trim($_POST['fullname']);
    $new_username = trim($_POST['username']);
    $new_department = trim($_POST['department']);
    $new_password = $_POST['password'];

    if (empty($new_fullname) || empty($new_username) || empty($new_department) || empty($new_password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT username FROM subadmin WHERE username = ?");
        $stmt->bind_param("s", $new_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Username already exists. Please choose another one.');</script>";
        } else {
            // Proceed with inserting the new subadmin
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $reg_date = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("INSERT INTO subadmin (fullname, username, department, password, reg_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $new_fullname, $new_username, $new_department, $hashed_password, $reg_date);

            if ($stmt->execute()) {
                echo "<script>alert('Subadmin added successfully');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
        }
        
        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | SubAdmin</title>
    <link rel="stylesheet" href="./css/add-subadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <!--Side Bar-->
        <div class="sidebar">
            <ul>
                <li>
                    <i class="fa fa-tachometer" aria-hidden="true"></i><a href="dashboard.php">Dashboard</a>
                </li>
                <li>
                    <i class="fa fa-users" aria-hidden="true"></i> Sub Admin
                </li>
                <li>
                    <i class="fa fa-user-plus" aria-hidden="true"></i><a href="add-subadmin.php">Add Subadmin</a>
                </li>
                    <li><i class="fa fa-cogs" aria-hidden="true"></i><a href="manage-subadmin.php">Manage Subadmin</a></li>
                <li>
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Manage Complaint
                </li>
                <li>
                    <i class="fa fa-inbox" aria-hidden="true"></i><a href="not-processed.php">Not Processed Yet</a>
                </li>
                <li>
                    <i class="fa fa-hourglass-half" aria-hidden="true"></i><a href="in-process.php">In Process Complaint</a>
                </li>
                <li>
                    <i class="fa fa-hourglass-end" aria-hidden="true"></i><a href="forwarded-pending.php">Forwarded Pending</a>
                </li>
                <li>
                    <i class="fa fa-check-circle" aria-hidden="true"></i><a href="closed.php">Closed Complaint</a></li>
                <li>
                    <i class="fa fa-file-text" aria-hidden="true"></i> Report
                </li>
                <li>
                    <i class="fa fa-calendar" aria-hidden="true"></i><a href="report.php">B/W Date Report</a>
                </li>
                <li>
                    <i class="fa fa-calendar" aria-hidden="true"></i><a href="subadmin-report.php">SubAdmin Report</a>
                </li>
            </ul>
        </div>
        <!-----------Content------------>
        <div class="main-container">
            <!-- Navigation Bar-->
            <div class="div-navbar">
                <div class="hamburger">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                <div class="nav-title">
                    <h4>
                        <img src="./assets/images/Complaint-icon.png" width="60px" height="50px" alt="Complaint-icon">
                        Student Complaint Management System
                    </h4>
                </div>
                <div class="logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                </div>
            </div>
            <!-- Main Content -->
            <div class="main-content">
                <h5>
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    Add SubAdmin
                </h5>
                <div class="content">
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">FullName</label>
                                <input type="text" id="fullname" name="fullname">
                            </div>
                            <div class="form-group">
                                <label for="username">User Name</label>
                                <input type="text" id="username" name="username">
                            </div>
                            <div class="form-group">
                                <label for="department">Department</label>
                                <input type="text" id="department" name="department">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" id="password" name="password">
                            </div>
                            <button type="submit" class="submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include './include/footer.php'?>
    </div>
    <script src="./javascript/dashboard.js"></script>
</body>
</html>