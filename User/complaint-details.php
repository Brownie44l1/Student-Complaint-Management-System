<?php
session_start();

// Check if the user is logged in
//if (!isset($_SESSION['fullname']) || !isset($_SESSION['matric_no']) || !isset($_SESSION['reg_date'])) {
  //  header("Location: login.php");
    //exit();
//}
$fullname = $_SESSION['fullname'];
include './include/db_connection.php';

$complaint_id = $_GET['complaint_id'] ?? null;

if ($complaint_id) {
    $stmt = $conn->prepare("SELECT id, fullname, reg_date, category, complaint_type, natureOfComplaint, message, complaint_file, status, remark FROM complaints WHERE id = ?");
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $stmt->bind_result($id, $fullname, $reg_date, $category, $complaint_type, $natureOfComplaint, $message, $complaint_file, $status, $remark);
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
    <link rel="stylesheet" href="./css/complaint-details_style.css">
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
                    <i class="fa fa-history" aria-hidden="true"></i>
                    Complaint Details
                </h5>
                <div class="content">
                    <table>
                        <tr>
                            <th>Complaint Number</th>
                            <td><?php echo htmlspecialchars($id); ?></td>
                            <th>Complainant Name</th>
                            <td><?php echo htmlspecialchars($fullname); ?></td>
                            <th>Reg Date</th>
                            <td><?php echo htmlspecialchars($reg_date); ?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo htmlspecialchars($category); ?></td>
                            <th>Complaint Type</th>
                            <td><?php echo htmlspecialchars($complaint_type); ?></td>
                            <th>Nature of Complaint</th>
                            <td><?php echo htmlspecialchars($natureOfComplaint); ?></td>
                        </tr>
                        <tr>
                            <th>Complaint Details</th>
                            <td colspan="5"><?php echo htmlspecialchars($message); ?></td>
                        </tr>
                        <tr>
                            <th>File(if any)</th>
                            <td colspan="5">
                                <?php if ($complaint_file): ?>
                                    <a href="<?php echo htmlspecialchars($complaint_file); ?>">View File</a>
                                <?php else: ?>
                                    No file uploaded
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Final Status</th>
                            <td colspan="5" class="status <?php echo 'status-' . str_replace(' ', '-', strtolower($status)); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </td>
                        </tr>
                        <?php if (strtolower($status) === 'closed'): ?>
                            <tr>
                                <th>Remark</th>
                                <td colspan="5"><?php echo htmlspecialchars($remark); ?></td>
                            </tr>
                            <tr>
                                <th>Remark By</th>
                                <td colspan="5">Admin</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <?php include './include/footer.php';?>
    </div>
<script src="dashboard_script.js"></script>
</body>
</html>