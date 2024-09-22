<?php
session_start();
//if (!isset($_SESSION['fullname']) || !isset($_SESSION['matric_no']) || !isset($_SESSION['reg_date'])) {
  //  header("Location: login.php");
    //exit();
//}
$fullname = $_SESSION['fullname'];

$matric_no = $_SESSION['matric_no'];

include './include/db_connection.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and department from the form
    $email = $_POST['email'];
    $department = $_POST['department'];
    $matric_no = $_SESSION['matric_no']; // assuming matric_no is stored in session during login

    // Prepare the update query
    $sql = "UPDATE users_signup SET email = ?, department = ? WHERE matric_no = ?";
    
    // Create a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("sss", $email, $department, $matric_no);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Success message
            echo "<script>alert('Profile updated successfully');</script>";
        } else {
            // Error message
            echo "<script>alert('Error updating profile: " . $stmt->error . "');</script>";
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
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
    <title>SCMS | Profile</title>
    <link rel="stylesheet" href="./css/profile_style.css">
    <link rel="stylesheet" href="./css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <!--Side Bar-->
        <div class="sidebar">
        <img src="./assets/images/person_icon.png" alt="User Avatar">
            <p><?php echo htmlspecialchars($fullname); ?></p>
            <ul>
                <li><i class="fa fa-tachometer" aria-hidden="true"></i><a href="dashboard.php">Dashboard</a></li>
                <li><i class="fa fa-cogs" aria-hidden="true"></i><a href="profile.php">Account Setting</a></li>
                <li><i class="fa fa-file-text" aria-hidden="true"></i><a href="lodge-complaint.php">Lodge Complaint</a></li>
                <li><i class="fa fa-history" aria-hidden="true"></i><a href="complaint-history.php">Complaint History</a></li>
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
                        <img src="./assets/images/complaint-icon.png" width="60px" height="50px" alt="Complaint-icon">
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
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    Account Setting > Profile
                </h5>
                <div class="content">
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">FullName</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="matric_no">Matric No</label>
                                <input type="text" id="matric_no" name="matric_no" value="<?php echo htmlspecialchars($matric_no); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
                            </div>
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select id="department" name="department" required>
                                    <option value="">Choose Your Department</option>
                                    <option value="Computer Science">Computer Science</option>
                                    <option value="Statistics">Statistics</option>
                                    <option value="Electrical Engineering">Electrical Engineering</option>
                                    <option value="Computer Engineering">Computer Engineering</option>
                                    <option value="Civil Engineering">Civil Engineering</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="reg-date">Reg Date</label>
                                <input type="text" id="reg-date" name="reg-date" placeholder="09/2024" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group user-photo">
                            <label for="user-photo">User Photo</label>
                            <img src="./assets/images/person_icon.png" alt="User Photo">
                            <button type="button">Change Photo</button>
                        </div>
                        <button type="submit" class="submit-btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <?php include './include/footer.php'; ?>
    </div>
<script src="./javascript/dashboard_script.js"></script>
</body>
</html>
