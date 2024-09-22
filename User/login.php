<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="./css/login_style.css">
    <script>
        function handleAdminLogin(event) {
            event.preventDefault(); // Prevent the form from submitting immediately

            const adminCheckbox = document.getElementById('login_as_admin');

            if (adminCheckbox.checked) {
                const isSubadmin = confirm("If you are a subadmin, click OK. If not, click Cancel.");
                document.getElementById('is_subadmin').value = isSubadmin ? 'yes' : 'no';
            } else {
                document.getElementById('is_subadmin').value = 'no';
            }

            document.getElementById('loginForm').submit(); // Submit the form after handling the alert
        }
    </script>
</head>
<body>
    <div class="container">   
        <form action="authenticate.php" method="POST" id="loginForm">
            <div class="img">
                <img src="./assets/images/complaint-icon.png" width="100px" height="90px" alt="Complaint-icon">
            </div>
            <h2>Student Complaint Management System</h2>
            <p>A place to lodge your complaint anonymously.</p><br>
            <div class="input-container">
                <div class="input-field">
                    <label for="text">User ID*</label><br>
                    <input type="text" name="username" placeholder="User ID" class="User_ID" required><br>
                </div>
                <div class="input-field">
                    <label for="password">Password*</label><br>
                    <div class="password-container">
                        <input type="password" name="password" placeholder="Password" class="Password" id="password" required>
                        <div id="togglePassword" class="toggle-password">
                            <i class="fas fa-eye" id="eye"></i>
                            <i class="fas fa-eye-slash" id="slash"></i>
                        </div>
                    </div><br>
                </div>
            </div>
            <div class="remember-forgot">
                <div class="remember">
                    <input type="checkbox" name="login_as_admin" id="login_as_admin">
                    <label>Login as Admin</label>
                </div>
                <div class="forgot">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>
            <input type="hidden" id="is_subadmin" name="is_subadmin" value="no">
            <button onclick="handleAdminLogin(event)">Login</button>
            <div class="register">
                <span class="span">Don't have an account? <a href="sign-up.php">Sign Up</a></span>
            </div>
        </form>
    </div>
    <script src="./javascript/script.js"></script>   
    <!--
        Username: admin1
        Password: adminnn123

        Username: daniel_awolowo
        Password: test_password123

        Username: CS1234567890
        Password: test_password123
    -->
</body>
</html>
