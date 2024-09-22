<?php
session_start();

//if (!isset($_SESSION['user_id'])) {
  //  header("Location: login.php");
    //exit();
//}

$fullname = $_SESSION['fullname'];
$user_id = $_SESSION['user_id'];

include './include/db_connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $complaint_type = isset($_POST['complaint_type']) ? $_POST['complaint_type'] : '';
    $natureOfComplaint = isset($_POST['natureOfComplaint']) ? $_POST['natureOfComplaint'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    $complaint_file = '';
    $upload_dir = 'uploads/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($_FILES['complaint_file']) && $_FILES['complaint_file']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['complaint_file']['tmp_name'];
        $file_name = basename($_FILES['complaint_file']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($tmp_name, $file_path)) {
            $complaint_file = $file_path;
        } else {
            echo "<script>alert('Failed to upload file.');</script>";
            exit();
        }
    }

    $sql = "INSERT INTO complaints (category, complaint_type, natureOfComplaint, complaint_file, message, fullname, status, reg_date, user_id) 
            VALUES (?, ?, ?, ?, ?, ?, 'not processed', NOW(), ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssi", $category, $complaint_type, $natureOfComplaint, $complaint_file, $message, $fullname, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Complaint lodged successfully');</script>";
        } else {
            echo "<script>alert('Error lodging complaint: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Lodge Complaint</title>
    <link rel="stylesheet" href="./css/dashboard_style.css">
    <link rel="stylesheet" href="./css/lodge-complaint_style.css">
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
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    Lodge Complaint
                </h5>
                <div class="content">
                    <form id="complaint-form" action="" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Academic Complaint">Academic Complaint</option>
                                    <option value="Non-Academic Complaint">Non-Academic Complaint</option>
                                    <option value="External Complaint">External Complaint</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="complaint_type">Complaint Type</label>
                                <select id="complaint_type" name="complaint_type" required>
                                    <option value="">Select Complaint Type</option>
                                    <option value="Grade based Complaint">Grade based Complaint</option>
                                    <option value="Academic Misconduct">Academic Misconduct</option>
                                    <option value="Formal Grievance">Formal Grievance</option>
                                    <option value="Inadequate Teaching">Inadequate Teaching</option>
                                    <option value="Discrimination">Discrimination</option>
                                    <option value="Sexual Harassment">Sexual Harassment</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="natureOfComplaint">Nature of Complaint</label>
                                <input type="text" id="natureOfComplaint" name="natureOfComplaint" required>
                            </div>
                            <div class="form-group">
                                <label for="complaint_file">Complaint Related Doc (if any)</label>
                                <input type="file" id="complaint_file" name="complaint_file" accept="image/jpeg,image/png">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Complaint Details (max 500 words)</label>
                            <textarea id="message" name="message" rows="6" cols="53" required></textarea>
                        </div>
                        <button class="btn_submit" id="btn_submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <?php include './include/footer.php'?>
    </div>
<script src="./javascript/dashboard_script.js"></script>
</body>
</html>