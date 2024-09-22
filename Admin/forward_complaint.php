<?php
include './include/db_connection.php'; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($complaint_id === 0) {
    die("Invalid complaint ID.");
}

// Fetch subordinates
$subadmin_query = "SELECT id, fullname FROM subadmin";
$subadmin_result = $conn->query($subadmin_query);

$message = ''; // Initialize message variable

if (isset($_POST['submit'])) {
    $forward_to = intval($_POST['forward_to']);
    
    // Update the complaint to forward it to the selected subordinate
    $update_query = "UPDATE complaints SET forwarded_to = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $forward_to, $complaint_id);

    if ($stmt->execute()) {
        $message = "Complaint Forwarded successfully";
        $success = true;
    } else {
        $message = "Error forwarding complaint: " . $conn->error;
        $success = false;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forward Complaint</title>
    <link rel="stylesheet" href="./css/complaint-details.css">
    <script>
        // Function to show an alert if there's a success message
        function showAlert(message) {
            if (message) {
                alert(message);
                window.opener.location.reload(); // Reload the opener window
                window.close(); // Close the current window
            }
        }
    </script>
</head>
<body onload="showAlert('<?php echo htmlspecialchars($message); ?>')">
    <h3>Complaint Number# <?php echo htmlspecialchars($complaint_id); ?></h3>

    <form action="" method="post">
        <label for="forward_to">Forward To:</label>
        <select name="forward_to" id="forward_to" required>
            <option value="">Select SubAdmin/Subordinate</option>
            <?php while($subordinate = $subadmin_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($subordinate['id']); ?>">
                    <?php echo htmlspecialchars($subordinate['fullname']); ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <input type="submit" name="submit" value="Submit" class="blue"><br>
        <button type="button" onclick="window.close();">Close</button>
    </form>
</body>
</html>
