<?php
session_start();

// Check if the user is logged in
//if (!isset($_SESSION['fullname']) || !isset($_SESSION['matric_no']) || !isset($_SESSION['reg_date'])) {
  //  header("Location: login.php");
    //exit();
//}
$fullname = $_SESSION['fullname'];

include './include/db_connection.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Fetch the count of complaints not processed yet
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM complaints WHERE user_id = ? AND status = 'not processed'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($notProcessedCount);
    $stmt->fetch();
    $stmt->close();

    // Fetch the count of complaints in process
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM complaints WHERE user_id = ? AND status = 'In Process'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($inProcessCount);
    $stmt->fetch();
    $stmt->close();

    // Fetch the count of complaints closed
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM complaints WHERE user_id = ? AND status = 'Closed'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($closedCount);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Dashboard</title>
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
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                    Dashboard
                </h5>
                <div class="card-wrapper">
                    <div class="card blue">
                        <i class="fa fa-file-text" aria-hidden="true"></i>
                        <p><?php echo $notProcessedCount; ?></p>
                        <span>Complaints not Processed yet</span>
                    </div>
                    <div class="card orange">
                        <i class="fa fa-file-text" aria-hidden="true"></i>
                        <p><?php echo $inProcessCount; ?></p>
                        <span>Complaints Status in Process</span>
                    </div>
                    <div class="card green">
                        <i class="fa fa-file-text" aria-hidden="true"></i>
                        <p><?php echo $closedCount; ?></p>
                        <span>Complaints that has been Closed</span>
                    </div>
                </div>
            </div>
        </div>
        <?php include './include/footer.php'?>
    </div>
<script src="./javascript/dashboard_script.js"></script>
</body>
</html>
