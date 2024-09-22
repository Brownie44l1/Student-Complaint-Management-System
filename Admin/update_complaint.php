<?php
session_start();

include './include/db_connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM complaints WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $status = htmlspecialchars($row['status']);
} else {
    echo "No complaint found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = htmlspecialchars($_POST['status']);
    $remark = htmlspecialchars($_POST['remark']);

    $update_query = "UPDATE complaints SET status = ?, remark = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $new_status, $remark, $complaint_id);
    $update_stmt->execute();
    
    echo "<script>
            alert('Complaint updated successfully');
            window.opener.location.reload();
            window.close();
          </script>";
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
    <title>Update Complaint</title>
    <link rel="stylesheet" href="./css/complaint-details.css">
</head>
<body>
    <div class="content">
        <form action="update_complaint.php?id=<?php echo $complaint_id; ?>" method="post">
            <table>
                <tr>
                    <th>Complaint Number</th>
                    <td><?php echo $complaint_id; ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <select name="status">
                            <option value="Not Process Yet" <?php if($status == 'not processed') echo 'selected'; ?>>Not Process Yet</option>
                            <option value="In Process" <?php if($status == 'In Process') echo 'selected'; ?>>In Process</option>
                            <option value="Closed" <?php if($status == 'Closed') echo 'selected'; ?>>Closed</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Remark</th>
                    <td><textarea name="remark" rows="6" cols="40"></textarea></td>
                </tr>
            </table>
            <input type="submit" value="Submit"><br>
            <button type="button" onclick="window.close();">Close this window</button>
        </form>
    </div>
</body>
</html>
