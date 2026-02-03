<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Complaint Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        p {
            color: #666;
            margin-bottom: 2rem;
        }
        .links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        a {
            display: block;
            padding: 1rem 2rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 600;
        }
        a:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Complaint Management System</h1>
        <p>Select your portal to continue</p>
        <div class="links">
            <a href="/User/login.php">Student Portal</a>
            <a href="/Admin/dashboard.php">Admin Portal</a>
            <a href="/Subadmin/dashboard.php">Sub-Admin Portal</a>
        </div>
    </div>
</body>
</html>