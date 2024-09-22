<?php
session_start();

include './include/db_connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the user details based on complaint_id
$query = "SELECT u.* FROM users_signup u JOIN complaints c ON u.id = c.user_id WHERE c.id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_name = htmlspecialchars($row['fullname']);
    $matric_no = htmlspecialchars($row['matric_no']);
    $user_email = htmlspecialchars($row['email']);
    $department = htmlspecialchars($row['department']);
    $reg_date = htmlspecialchars($row['reg_date']);
    // Add more fields as necessary
} else {
    echo "No user details found.";
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
    <title>View User Details</title>
    <link rel="stylesheet" href="./css/complaint-details.css">
</head>
<body>
    <div class="content">
        <table class="table">
            <tr>
                <th>Name</th>
                <td><?php echo $user_name; ?></td>
            </tr>
            <tr>
                <th>Matric No</th>
                <td><?php echo $matric_no; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $user_email; ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td><?php echo $department; ?></td>
            </tr>
            <tr>
                <th>Reg Date</th>
                <td><?php echo $reg_date; ?></td>
            </tr>
        </table>
        <button type="button" onclick="window.close();" class="button__">Close this window</button>
    </div>
</body>
</html>
