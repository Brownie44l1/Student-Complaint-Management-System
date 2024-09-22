<?php
include './include/db_connection.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set limit and get the current page number
$limit = 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Get date range from URL parameters
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Fetch complaints with subadmin details
$query = "SELECT c.id, c.category, c.complaint_type, c.status, c.reg_date, sa.fullname as subadmin_name 
          FROM complaints c 
          LEFT JOIN subadmin sa ON c.forwarded_to = sa.id 
          WHERE c.forwarded_to IS NOT NULL 
          LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Fetch total number of records for pagination
$total_query = "SELECT COUNT(*) as total FROM complaints WHERE forwarded_to IS NOT NULL";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total = $total_row['total'];
$total_pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Dashboard</title>
    <link rel="stylesheet" href="./css/report.css">
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
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    Sub Admin Report from <?php echo htmlspecialchars($from_date); ?> to <?php echo htmlspecialchars($to_date); ?>
                </h5>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>Complaint Number</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Reg Date</th>
                                <th>Subadmin Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reg_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subadmin_name']); ?></td>
                                    <td><a href="complaint-details.php?id=<?php echo $row['id']; ?>">View Details</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>&page=<?php echo $page - 1; ?>" class="prev">
                                <i class="fa fa-arrow-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>&page=<?php echo $i; ?>" <?php if($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="?from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>&page=<?php echo $page + 1; ?>" class="next">
                                Next <i class="fa fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
