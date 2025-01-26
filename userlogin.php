<?php   
require_once('db_config.php'); 
require_once('essentialuser.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #63b4ff, #9155ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }
        input {
            width: 100%;
            padding: 8px; /* Reduced padding for smaller boxes */
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        input:focus {
            border-color: #63b4ff;
        }
        button {
            width: 100%;
            background: #63b4ff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #5299d6;
        }
        .login-btn {
            background: #9155ff;
        }
        .login-btn:hover {
            background: #7640d6;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: green;
        }
        .error {
            color: red;
        }
        .footer-text {
            text-align: center;
            margin-top: 15px;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        <?php 
        // Display any error messages
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>" . htmlspecialchars($_GET['error']) . "</p>";
        }
        ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit" name="Login" class="btn">Login</button>
        </form>
        <div class="footer-text">
            <p>Don't have an account?</p>
            <button onclick="window.location.href='usersignup.php'" class="login-btn">Sign Up</button>
        </div>

        <?php
        // Handle form submission
        if (isset($_POST['Login'])) {
            $frm_data = filteration($_POST);
            $query = "SELECT * FROM users WHERE username = ? AND password = ?";
            $values = array($frm_data['username'], $frm_data['password']);
            $res = select($query, $values, 'ss');

            if ($res->num_rows == 1) {
                $row = mysqli_fetch_assoc($res);
                session_start();
                $_SESSION['userLogin'] = true;
                $_SESSION['user_id'] = $row['id'];
                redirect('profile.php');
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=Invalid Credentials");
                exit;
            }
        }
        ?>
    </div>
</body>
</html>
