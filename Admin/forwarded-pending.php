<?php
include './include/db_connection.php'; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch forwarded complaints
$query = "SELECT c.*, s.fullname AS subadmin_name 
          FROM complaints c 
          JOIN subadmin s ON c.forwarded_to = s.id 
          WHERE c.status = 'Not Processed' AND c.forwarded_to IS NOT NULL";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Manage Complaint</title>
    <link rel="stylesheet" href="./css/not-processed.css">
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
                    <i class="fa fa-hourglass-end" aria-hidden="true"></i>
                    Forwarded Pending
                </h5>
                <div class="content">
                    <?php if ($result->num_rows > 0) { ?>
                        <table>
                            <tr>
                                <th>Complaint Number</th>
                                <th>Category</th>
                                <th>Complaint Type</th>
                                <th>Subadmin</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['complaint_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['subadmin_name']); ?></td>
                                <td>
                                    <button style="background-color: #fa0000ec; color: #fff; border: none; padding: 5px 6px; border-radius: 5px;">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </button>
                                </td>
                                <td>
                                    <a href="complaint-details.php?id=<?php echo htmlspecialchars($row['id']); ?>">View Details</a>
                                </td>
                            </tr>

                            <?php endwhile; ?>
                        </table>
                    <?php } else { ?>
                        <p style='font-size: 20px; font-weight: 500; margin-left: 20px;'>All done, nothing to see.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php include './include/footer.php';?>
    </div>
    <script src="./javascript/dashboard.js"></script>
</body>
</html>

<?php
$conn->close();
?>
