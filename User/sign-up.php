<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCMS | Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="./css/sign-up_style.css">
</head>
<body>
<div class="container">   
    <form action="database.php" method="POST">
        <div class="img">
            <img src="./assets/images/complaint-icon.png" width="100px" height="90px" alt="Complaint-icon">
        </div>
        <h2>Student Complaint Management System</h2>
        <p>A place to lodge your complaint anonymously.</p><br>
        <div class="input-container">
        <div class="input-field">
                <label for="text">Full Name*</label><br>
                <input type="text" placeholder="Full Name" class="fullname" name="fullname" required><br>
            </div>
            <div class="input-field">
                <label for="text">Matric No*</label><br>
                <input type="text" placeholder="Matric No" class="matric" name="matric_no" required><br>
            </div>
            <div class="input-field">
                <label for="password">Password*</label><br>
                <div class="password-container">
                    <input type="password" placeholder="Password" class="Password" id="password" name="password" required>
                    <div id="togglePassword" class="toggle-password">
                        <i class="fas fa-eye" id="eye"></i>
                        <i class="fas fa-eye-slash" id="slash"></i>
                    </div>
                </div><br>
            </div>
        </div>
        <button>Sign Up</button>
        <div class="register">
            <span class="span">Already have an account? <a href="login.php">Log In</a></span>
        </div>
    </form>
</div>
<script src="./javascript/script.js"></script>   
</body>
</html>
