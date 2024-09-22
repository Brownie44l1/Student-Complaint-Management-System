<?php
session_start();
include './include/db_connection.php';

//if (!isset($_SESSION['fullname']) || !isset($_SESSION['username']) || !isset($_SESSION['reg_date'])) {
  //  header("Location: ../User/login.php");
    //exit();
//}

// Fetch total complaints
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM complaints");
$totalRow = $totalResult->fetch_assoc();
$total = $totalRow['total'];

// Fetch not processed yet complaints
$notProcessedResult = $conn->query("SELECT COUNT(*) AS not_processed FROM complaints WHERE status = 'not processed' AND forwarded_to IS NULL");
$notProcessedRow = $notProcessedResult->fetch_assoc();
$notProcessed = $notProcessedRow['not_processed'];

// Fetch in process complaints
$inProcessResult = $conn->query("SELECT COUNT(*) AS in_process FROM complaints WHERE status = 'in process'");
$inProcessRow = $inProcessResult->fetch_assoc();
$inProcess = $inProcessRow['in_process'];

// Fetch not forwarded pending complaints
$notForwardedPendingResult = $conn->query("SELECT COUNT(*) AS not_forwarded_pending FROM complaints WHERE status = 'Not Processed' AND forwarded_to IS NOT NULL");
$notForwardedPendingRow = $notForwardedPendingResult->fetch_assoc();
$notForwardedPending = $notForwardedPendingRow['not_forwarded_pending'];

// Fetch closed complaints
$closedResult = $conn->query("SELECT COUNT(*) AS closed FROM complaints WHERE status = 'closed'");
$closedRow = $closedResult->fetch_assoc();
$closed = $closedRow['closed'];

// Fetch subadmin data
$subadminResult = $conn->query("
    SELECT 
        s.fullname, 
        s.department, 
        COUNT(c.id) AS total,
        SUM(CASE WHEN c.status = 'Not Processed' THEN 1 ELSE 0 END) AS not_processed,
        SUM(CASE WHEN c.status = 'In Process' THEN 1 ELSE 0 END) AS in_process,
        SUM(CASE WHEN c.status = 'Closed' THEN 1 ELSE 0 END) AS closed
    FROM subadmin s
    LEFT JOIN complaints c ON c.forwarded_to = s.id
    GROUP BY s.id
");

// Initialize grand totals
$grandTotal = 0;
$grandNotProcessed = 0;
$grandInProcess = 0;
$grandClosed = 0;

// Calculate grand totals
while ($row = $subadminResult->fetch_assoc()) {
    $grandTotal += $row['total'];
    $grandNotProcessed += $row['not_processed'];
    $grandInProcess += $row['in_process'];
    $grandClosed += $row['closed'];
}

// Reset subadminResult pointer for later use in HTML
$subadminResult->data_seek(0);
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
                <i class="fa fa-tachometer" aria-hidden="true"></i>
                Dashboard
            </h5>
            <div class="card-wrapper">
                <div class="card blue">
                    <span>Total</span>
                    <p><?php echo $total; ?></p>
                </div>
                <div class="card orange">
                    <span>Not Processed Yet</span>
                    <p><?php echo $notProcessed; ?></p>
                </div>
                <div class="card green">
                    <span>In Process</span>
                    <p><?php echo $inProcess; ?></p>
                </div>
                <div class="card red">
                    <span>Forwarded Pending</span>
                    <p><?php echo $notForwardedPending; ?></p>
                </div>
                <div class="card blue">
                    <span>Closed</span>
                    <p><?php echo $closed; ?></p>
                </div>
            </div>
            <div class="sub-admin-data">
                <h4>
                    <i class="fa fa-users" aria-hidden="true"></i>
                    Subadmin Data
                </h4>
                <table>
                    <thead>
                        <tr>
                            <th>Subadmin Name --- Dept</th>
                            <th>Total</th>
                            <th style="color: red;">Not Processed Yet</th>
                            <th style="color: #d99a09;">In Process</th>
                            <th style="color: green;">Closed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $subadminResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['fullname'] . ' --- ' . $row['department']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td><?php echo $row['not_processed']; ?></td>
                            <td><?php echo $row['in_process']; ?></td>
                            <td><?php echo $row['closed']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr>
                            <td style="color: blue;">Grand Total</td>
                            <td><?php echo $grandTotal; ?></td>
                            <td><?php echo $grandNotProcessed; ?></td>
                            <td><?php echo $grandInProcess; ?></td>
                            <td><?php echo $grandClosed; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include './include/footer.php';?>
<script src="./javascript/dashboard.js"></script>
</body>
</html>