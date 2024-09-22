<?php
session_start();
include './include/db_connection.php'; 

// Ensure the subadmin is logged in
if (!isset($_SESSION['subadmin_username'])) {
    die("Subadmin not logged in");
}

$subadmin_username = $_SESSION['subadmin_username'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subadmin ID based on username
$subadmin_query = "SELECT id FROM subadmin WHERE username = ?";
$subadmin_stmt = $conn->prepare($subadmin_query);

if ($subadmin_stmt) {
    $subadmin_stmt->bind_param("s", $subadmin_username);
    $subadmin_stmt->execute();
    $subadmin_stmt->bind_result($subadmin_id);
    $subadmin_stmt->fetch();
    $subadmin_stmt->close();
    
    if (!isset($subadmin_id)) {
        die("Subadmin ID not found for username: " . $subadmin_username);
    }

    // Fetch complaint counts for the logged-in subadmin
    $total_query = "SELECT COUNT(*) AS total FROM complaints WHERE forwarded_to = ?";
    $not_processed_query = "SELECT COUNT(*) AS not_processed FROM complaints WHERE forwarded_to = ? AND status = 'not processed'";
    $in_process_query = "SELECT COUNT(*) AS in_process FROM complaints WHERE forwarded_to = ? AND status = 'in process'";
    $closed_query = "SELECT COUNT(*) AS closed FROM complaints WHERE forwarded_to = ? AND status = 'closed'";

    // Total complaints
    $stmt = $conn->prepare($total_query);
    $stmt->bind_param("i", $subadmin_id);
    $stmt->execute();
    $stmt->bind_result($total_complaints);
    $stmt->fetch();
    $stmt->close();

    // Not processed complaints
    $stmt = $conn->prepare($not_processed_query);
    $stmt->bind_param("i", $subadmin_id);
    $stmt->execute();
    $stmt->bind_result($not_processed_complaints);
    $stmt->fetch();
    $stmt->close();

    // In process complaints
    $stmt = $conn->prepare($in_process_query);
    $stmt->bind_param("i", $subadmin_id);
    $stmt->execute();
    $stmt->bind_result($in_process_complaints);
    $stmt->fetch();
    $stmt->close();

    // Closed complaints
    $stmt = $conn->prepare($closed_query);
    $stmt->bind_param("i", $subadmin_id);
    $stmt->execute();
    $stmt->bind_result($closed_complaints);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Subadmin query preparation failed: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard.css">
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
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Manage Complaint
                </li>
                <li>
                    <i class="fa fa-inbox" aria-hidden="true"></i><a href="not-processed.php">Not Processed Yet</a>
                </li>
                <li>
                    <i class="fa fa-hourglass-half" aria-hidden="true"></i><a href="in-process.php">In Process Complaint</a>
                </li>
                <li>
                    <i class="fa fa-check-circle" aria-hidden="true"></i><a href="closed.php">Closed Complaint</a>
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
                <i class="fa fa-tachometer" aria-hidden="true"></i>
                Dashboard
            </h5>
            <div class="card-wrapper">
                <div class="card blue">
                    <span>Total</span>
                    <p><?php echo $total_complaints; ?></p>
                </div>
                <div class="card orange">
                    <span>Not Processed Yet</span>
                    <p><?php echo $not_processed_complaints; ?></p>
                </div>
                <div class="card green">
                    <span>In process</span>
                    <p><?php echo $in_process_complaints; ?></p>
                </div>
                <div class="card red">
                    <span>Closed</span>
                    <p><?php echo $closed_complaints; ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php include './include/footer.php';?>
</div>
<script src="./javascript/dashboard.js"></script>
</body>
</html>