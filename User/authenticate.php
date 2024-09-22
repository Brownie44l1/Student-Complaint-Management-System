<?php
session_start();
include './include/db_connection.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $login_as_admin = isset($_POST['login_as_admin']);
    $is_subadmin = isset($_POST['is_subadmin']) ? $_POST['is_subadmin'] === 'yes' : false;

    if (!empty($username) && !empty($password)) {
        if ($login_as_admin) {
            if ($is_subadmin) {
                $sql = "SELECT username, fullname, password FROM subadmin WHERE username = ?";
            } else {
                $sql = "SELECT username, fullname, password FROM admin_signup WHERE username = ?";
            }
        } else {
            $sql = "SELECT id, matric_no, fullname, password FROM users_signup WHERE matric_no = ?";
        }

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            
            if ($login_as_admin) {
                $stmt->bind_result($retrieved_username, $fullname, $hashed_password);
            } else {
                $stmt->bind_result($id, $retrieved_username, $fullname, $hashed_password);
            }

            if ($stmt->fetch()) {
                if (password_verify($password, $hashed_password)) {
                    session_regenerate_id(true); // Regenerate session ID on login
                    if ($login_as_admin) {
                        if ($is_subadmin) {
                            $_SESSION['subadmin_username'] = $retrieved_username;
                            $_SESSION['fullname'] = $fullname;
                            $_SESSION['subadmin_logged_in'] = true;
                            header("Location: ../Subadmin/dashboard.php");
                            exit();
                        } else {
                            $_SESSION['admin_username'] = $retrieved_username;
                            $_SESSION['fullname'] = $fullname;
                            $_SESSION['admin_logged_in'] = true;
                            header("Location: ../Admin/dashboard.php");
                            exit();
                        }
                    } else {
                        $_SESSION['user_id'] = $id;
                        $_SESSION['matric_no'] = $retrieved_username;
                        $_SESSION['fullname'] = $fullname;
                        $_SESSION['user_logged_in'] = true;
                        header("Location: dashboard.php");
                        exit();
                    }
                } else {
                    echo "<script>alert('Invalid username or password');</script>";
                }
            } else {
                echo "<script>alert('Invalid username or password');</script>";
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "<script>alert('Username and Password are required');</script>";
    }
} else {
    echo "<script>alert('Username and Password are required');</script>";
}

$conn->close();
?>
