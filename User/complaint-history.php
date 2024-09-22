<?php
session_start();

// Check if the user is logged in
//if (!isset($_SESSION['fullname']) || !isset($_SESSION['matric_no']) || !isset($_SESSION['reg_date'])) {
  //  header("Location: login.php");
    //exit();
//}

// Display the logged-in user's full name
$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard_style.css">
    <link rel="stylesheet" href="./css/complaint-history_style.css">
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
                    Complaint History
                </h5>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>Complaint Number</th>
                                <th>Reg Date</th>
                                <th>Last Update Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include './include/db_connection.php';
                            $userId = $_SESSION['user_id'];

                            // Fetch complaints lodged by the user
                            $sql = "SELECT id, reg_date, status FROM complaints WHERE user_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $statusClass = '';
                                    switch ($row["status"]) {
                                        case 'not processed':
                                            $statusClass = 'status-not-processed';
                                            break;
                                        case 'In Process':
                                            $statusClass = 'status-in-process';
                                            break;
                                        case 'Closed':
                                            $statusClass = 'status-closed';
                                            break;
                                    }

                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . $row["reg_date"] . "</td>";
                                    echo "<td class='last-update'></td>";
                                    echo "<td><span class='status " . $statusClass . "'>" . $row["status"] . "</span></td>";
                                    echo "<td><a href='complaint-details.php?complaint_id=" . $row["id"] . "' class='btn view-details " . $statusClass . "'>View Details</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No records found</td></tr>";
                            }

                            $stmt->close();
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include './include/footer.php';?>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lastUpdateCells = document.querySelectorAll('.last-update');
        const lastUpdateDate = new Date().toLocaleString();

        lastUpdateCells.forEach(cell => {
            cell.textContent = lastUpdateDate;
        });
    });
</script>
<script src="./javascript/dashboard_script.js"></script>
</body>
</html>