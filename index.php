<?php
require('db_config.php');
require('essential.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(to right, #007bff, #6610f2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .login-container .form-group {
            margin-bottom: 15px;
        }

        .login-container .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .login-container .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .login-container .btn {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .login-container .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="Login" class="btn">Login</button>
        </form>

        <?php
        // Display form inputs
        if (isset($_POST['Login'])) {
            $frm_data = filteration($_POST);
            // Update query to match your column names
            $query = "SELECT * FROM admin WHERE ADMIN_NAME = ? AND ADMIN_PASS = ?";
            $values = array($frm_data['username'], $frm_data['password']);
            $res = select($query, $values, 'ss');

            if ($res->num_rows == 1) {
                $row = mysqli_fetch_assoc($res);
                session_start();
                $_SESSION['adminLogin'] = true;
                $_SESSION['admin_id'] = $row['ADMIN_ID'];
                redirect('dashboard.php');
            } else {
                alert('danger', 'Invalid Credentials');
            }
        }
        ?>
    </div>
</body>
</html>