<?php
include './include/db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the deletion query
    $sql = "DELETE FROM subadmin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Subadmin deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>