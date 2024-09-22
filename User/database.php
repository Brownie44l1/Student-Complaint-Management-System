<?php
include './include/db_connection.php';
// Check if form data is set
if (isset($_POST['fullname']) && isset($_POST['matric_no']) && isset($_POST['password'])) {
    $fullname = $_POST['fullname'];
    $matric_no = $_POST['matric_no'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $sql = "INSERT INTO users_signup (fullname, matric_no, password, reg_date) VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $fullname, $matric_no, $hashed_password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Full Name, Matric No, and Password are required";
}

$conn->close();
?>
