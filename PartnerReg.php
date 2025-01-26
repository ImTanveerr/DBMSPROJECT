<?php
require('db_config.php');
require('essentialuser.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input data using the `filteration` function
    $data = filteration($_POST);

    $partnerName = $data['partner_name'];
    $email = $data['email'];
    $phone = $data['contact_phone'];
    $serviceType = $data['service_type'];
    $partner_pass = $data['partner_pass'];
    $confirmpartner_pass = $data['confirm_partner_pass'];

    // Validation
    if ($partner_pass !== $confirmpartner_pass) {
        $message = "partner_passs do not match.";
    } elseif (preg_match('/\s/', $partnerName)) {
        $message = "Partner name should not contain spaces.";
    } else {
        // Check for existing partner name
        $sql = "SELECT partner_id FROM partners WHERE partner_name = ?";
        $values = [$partnerName];
        $result = select($sql, $values, "s");

        if (mysqli_num_rows($result) > 0) {
            $message = "Partner name already exists.";
        } else {
            // Check for existing email
            $sql = "SELECT partner_id FROM partners WHERE email = ?";
            $values = [$email];
            $result = select($sql, $values, "s");

            if (mysqli_num_rows($result) > 0) {
                $message = "Account already exists.";
            } else {
                // Check for existing phone number
                $sql = "SELECT partner_id FROM partners WHERE contact_phone = ?";
                $values = [$phone];
                $result = select($sql, $values, "s");

                if (mysqli_num_rows($result) > 0) {
                    $message = "Phone number already in use.";
                } else {
                    // Insert the partner into the database with 'pending' status
                    $sql = "INSERT INTO partners (partner_name, email, contact_phone, service_type, partner_pass, status) 
                            VALUES (?, ?, ?, ?, ?, 'pending')";
                    $values = [$partnerName, $email, $phone, $serviceType, $partner_pass];
                    $datatypes = "sssss";

                    if (insert($sql, $values, $datatypes)) {
                        redirect('partner_profile.php');
                        $message = "Account created successfully!";
                        
                    } else {
                        $message = "An error occurred during registration. Please try again.";
                    }
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
    <title>Register</title>
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
        <div class="message-bar <?= strpos($message, 'exists') !== false || strpos($message, 'in use') !== false ? 'error' : 'success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Create a Partner Account</h2>
        <form method="POST" action="">
            <label for="partner_name">Partner Name</label>
            <input type="text" id="partner_name" name="partner_name" placeholder="Enter your partner name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="contact_phone">Phone Number</label>
            <input type="text" id="contact_phone" name="contact_phone" placeholder="Enter your phone number" required>

            <label for="service_type">Service Type</label>
            <input type="text" id="service_type" name="service_type" placeholder="Enter your service type" required>

            <label for="partner_pass">Enter your password</label>
            <input type="partner_pass" id="partner_pass" name="partner_pass" placeholder="Enter your password" required>

            <label for="confirm_partner_pass">Confirm your password</label>
            <input type="partner_pass" id="confirm_partner_pass" name="confirm_partner_pass" placeholder="Confirm your password" required>

            <button type="submit">Register</button>
        </form>
        <div class="footer-text">
            <p>Already have a partner account?</p>
            <button onclick="window.location.href='partnerlogin.php'" class="login-btn">Log In</button>
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
