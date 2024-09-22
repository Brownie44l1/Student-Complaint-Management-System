<?php

include './include/db_connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT id, fullname, category, complaint_type, natureOfComplaint, message, complaint_file, status, reg_date, remark
          FROM complaints 
          WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $fullname = htmlspecialchars($row['fullname']);
    $category = htmlspecialchars($row['category']);
    $complaint_type = htmlspecialchars($row['complaint_type']);
    $natureOfComplaint = htmlspecialchars($row['natureOfComplaint']);
    $message = htmlspecialchars($row['message']);
    $complaint_file = htmlspecialchars($row['complaint_file']);
    $status = htmlspecialchars($row['status']);
    $reg_date = htmlspecialchars($row['reg_date']);
    $remark = htmlspecialchars($row['remark']);
} else {
    echo "No complaint found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Manage Complaint</title>
    <link rel="stylesheet" href="./css/complaint-details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script>
        function openPopup(url) {
            var popupWindow = window.open(url, 'popupWindow', 'width=600,height=500,scrollbars=yes');
            popupWindow.focus();
        }
    </script>
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
                    <i class="fa fa-file-text" aria-hidden="true"></i> Manage Complaint
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
                    Not Processed Yet > Complaint Details
                </h5>
                <div class="content">
                    <table>
                        <tr>
                            <th>Complaint Number</th>
                            <td><?php echo $complaint_id; ?></td>
                            <th>Complainant Name</th>
                            <td><?php echo $fullname; ?></td>
                            <th>Reg Date</th>
                            <td><?php echo $reg_date; ?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo $category; ?></td>
                            <th>Complaint Type</th>
                            <td><?php echo $complaint_type; ?></td>
                            <th>Nature of Complaint</th>
                            <td><?php echo $natureOfComplaint; ?></td>
                        </tr>
                        <tr>
                            <th>Complaint Details</th>
                            <td colspan="5"><?php echo $message; ?></td>
                        </tr>
                        <tr>
                            <th>File(if any)</th>
                            <td colspan="5"><?php echo !empty($complaint_file) ? "<a href='$complaint_file'>View File</a>" : "No file attached"; ?></td>
                        </tr>
                        <tr>
                            <th>Final Status</th>
                            <td colspan="5" class="status"><?php echo $status; ?></td>
                        </tr>
                        <?php if (strcasecmp($status, 'Closed') === 0) { ?>
                            <tr>
                                <th>Remark</th>
                                <td colspan="5"><?php echo $remark; ?></td>
                            </tr>
                            <tr>
                                <th>Remark By</th>
                                <td colspan="5">Admin</td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <th>Action</th>
                                <td colspan="5">
                                    <a href="javascript:void(0);" onclick="openPopup('update_complaint.php?id=<?php echo $complaint_id; ?>')" style="margin-right: 10px;">Take Action</a>
                                    <a href="javascript:void(0);" onclick="openPopup('forward_complaint.php?id=<?php echo $complaint_id; ?>')" style="margin-right: 10px;">Forward To</a>
                                    <a href="javascript:void(0);" onclick="openPopup('view_user_details.php?id=<?php echo $complaint_id; ?>')" style="margin-right: 10px;">View User Details</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <?php include './include/footer.php';?>
    </div>
    <script src="./javascript/dashboard.js"></script>
</body>
</html>
