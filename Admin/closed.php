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
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    Closed
                </h5>
                <div class="content">
                <?php 
                    include './include/db_connection.php'; 

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    
                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    
                    // Pagination setup
                    $limit = 6; // Number of entries to show in a page.
                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                    $start = ($page - 1) * $limit;

                    // Fetch complaints with status 
                    $query = "SELECT id AS complaint_no, fullname AS complainant_name, reg_date, status 
                            FROM complaints 
                            WHERE status = 'closed'
                            LIMIT ?, ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ii", $start, $limit);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if (!$result) {
                        die("Query failed: " . $conn->error);
                    }

                    // Fetch total number of records for pagination
                    $total_query = "SELECT COUNT(*) as total FROM complaints WHERE status = 'closed'";
                    $total_result = $conn->query($total_query);
                    $total_row = $total_result->fetch_assoc();
                    $total = $total_row['total'];
                    $total_pages = ceil($total / $limit);

                    if ($result->num_rows > 0) {
                        echo "<table border='1'>
                        <tr>
                            <th>Complaint No</th>
                            <th>Complainant Name</th>
                            <th>Reg Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>";
                        
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['complaint_no'] . "</td>";
                            echo "<td>" . $row['complainant_name'] . "</td>";
                            echo "<td>" . $row['reg_date'] . "</td>";
                            echo "<td><button style='background-color: #4245f5; color: #fff; border: none; padding: 5px 12px; border-radius:5px;'>" . $row['status'] . "</button></td>";
                            echo "<td><a href='complaint-details.php?id=" . $row['complaint_no'] . "'>View Details</a></td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "<p style='font-size: 20px; font-weight: 500; margin-left: 20px;'>All done, nothing to see.</p>";
                    }

                    // Pagination
                    echo '<div class="pagination">';
                    if ($page > 1) {
                        echo '<a href="?page=' . ($page - 1) . '">Previous</a>';
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<a href="?page=' . $i . '" ' . ($i == $page ? 'class="active"' : '') . '>' . $i . '</a>';
                    }
                    if ($page < $total_pages) {
                        echo '<a href="?page=' . ($page + 1) . '">Next</a>';
                    }
                    echo '</div>';

                    $stmt->close();
                    $conn->close();
                ?>
                </div>
            </div>
        </div>
        <?php include './include/footer.php';?>
    </div>
    <script src="./javascript/dashboard.js"></script>
</body>
</html>
