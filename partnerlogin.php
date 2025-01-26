<?php   
require_once('db_config.php'); 
require_once('essential.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #63b4ff, #9155ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .login-container label {
            text-align: left;
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-container input:focus {
            border-color: #63b4ff;
            outline: none;
        }
        .login-container button {
            width: 100%;
            background: #63b4ff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #5299d6;
        }
        .footer-text {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }
        .footer-text a {
            color: #63b4ff;
            text-decoration: none;
            font-weight: bold;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Partner Login</h2>
        <?php 
        // Display error messages if any
        if (isset($_GET['error'])) {
            echo "<div class='error-message'>" . htmlspecialchars($_GET['error']) . "</div>";
        }
        ?>
        <form method="POST" action="">
            <label for="partner_name">Partner Name</label>
            <input type="text" id="partner_name" name="username" placeholder="Enter your partner name" required>
            
            <label for="partner_partner_pass">password</label>
            <input type="partner_pass" id="partner_partner_pass" name="partner_pass" placeholder="Enter your password" required>
            
            <button type="submit" name="Login">Login</button>
        </form>

        <?php
        // Handle login submission
        if (isset($_POST['Login'])) {
            $frm_data = filteration($_POST);
            $query = "SELECT * FROM partners WHERE partner_name = ? AND partner_pass = ?";
            $values = [$frm_data['username'], $frm_data['partner_pass']];
            $res = select($query, $values, 'ss');

            if ($res->num_rows === 1) {
                $row = mysqli_fetch_assoc($res);
                session_start();
                $_SESSION['partnerLogin'] = true;
                $_SESSION['partner_id'] = $row['partner_id'];
                header('Location: partner_dashboard.php');
                exit;
            } else {
                echo "<div class='error-message'>Invalid Credentials</div>";
            }
        }
        ?>

        <div class="footer-text">
            <p>Want to become a partner? <a href="partnerreg.php">Register</a></p>
        </div>
    </div>
</body>
</html>
