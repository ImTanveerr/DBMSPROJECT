<?php
require('db_config.php');
require('essentialuser.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input data using the `filteration` function
    $data = filteration($_POST);

    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $confirmPassword = $data['confirm_password'];

    // Validation
    if ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (preg_match('/\s/', $username)) {
        $message = "Username should not contain spaces.";
    } else {
        // Check for existing username
        $sql = "SELECT id FROM users WHERE username = ?";
        $values = [$username];
        $result = select($sql, $values, "s");

        if (mysqli_num_rows($result) > 0) {
            $message = "Username not available.";
        } else {
            // Check for existing email
            $sql = "SELECT id FROM users WHERE email = ?";
            $values = [$email];
            $result = select($sql, $values, "s");

            if (mysqli_num_rows($result) > 0) {
                $message = "Account already exists.";
            } else {
                // Insert the user into the database with 'banned' status
                $sql = "INSERT INTO users (first_name, last_name, username, email, password, status) 
                        VALUES (?, ?, ?, ?, ?, 'pending')";
                $values = [$firstName, $lastName, $username, $email, $password];
                $datatypes = "sssss";

                if (insert($sql, $values, $datatypes)) {
                    $message = "Account created successfully!";
                } else {
                    $message = "An error occurred during registration. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        .row {
            display: flex;
            gap: 25px;
        }
        .row input {
            flex: 1;
        }
        input {
            width: 100%;
            padding: 8px;
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
        .footer-text {
            text-align: center;
            margin-top: 15px;
        }
        .message-bar {
            background-color: #f44336;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: none;
        }
        .message-bar.success {
            background-color: #4CAF50;
        }
        .message-bar.error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <!-- Popup Message Bar -->
    <?php if (isset($message)): ?>
        <div class="message-bar <?= strpos($message, 'not available') !== false || strpos($message, 'exists') !== false ? 'error' : 'success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Create an Account</h2>
        <form method="POST" action="">
            <div class="row">
                <div>
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
                </div>
                <div>
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
                </div>
            </div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required pattern="\S+">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <div class="row">
                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <div class="footer-text">
            <p>Already have an account?</p>
            <button onclick="window.location.href='userlogin.php'" class="login-btn">Log In</button>
        </div>
    </div>

    <script>
        // Display message bar when a message is present
        <?php if (isset($message)): ?>
            document.querySelector('.message-bar').style.display = 'block';
            setTimeout(function() {
                document.querySelector('.message-bar').style.display = 'none';
            }, 5000); // Hide after 5 seconds
        <?php endif; ?>
    </script>
</body>
</html>
