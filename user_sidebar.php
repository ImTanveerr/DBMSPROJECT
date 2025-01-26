<?php
// Start the session at the top of the file

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: userlogin.php');
    exit(); // Stop further execution of the script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel Topbar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General styles */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Topbar styles */
        .topbar {
            background-color:rgba(12, 7, 1, 0.97);
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(174, 250, 9, 0.1);
        }

        .topbar .logo {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #f4f6f9;
            text-decoration: none;
        }

        .topbar .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .topbar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .topbar .nav-links a:hover {
            color: #007bff;
        }

        .topbar .nav-links a.active {
            font-weight: bold;
            color: #007bff;
        }

        /* Profile and Cart styles */
        .topbar .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar .profile i {
            color:rgb(255, 60, 0);
            font-size: 25px;
        }

        @media (max-width: 768px) {
            .topbar {
                flex-wrap: wrap;
                text-align: center;
            }

            .topbar .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 10px;
                gap: 10px;
            }

            .topbar .profile {
                margin-top: 10px;
            }
            
        }
    </style>
</head>
<body>
    <div class="topbar">
        <!-- Logo -->
        <a href="Home.php" class="logo">
            <img src="logo_with_title.png" alt="Event Logo" style="height: 40px;">
        </a>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="Home.php" class="active">Home</a>
            <a href="services.php">Services</a>
            <a href="my_bookings.php">My Cart</a>
            <a href="booking_history.php">Bookings History</a>
            <!-- Logout link with confirmation -->
            <a href="logoutuser.php" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
        </div>

        <!-- Profile Icon -->
        <div class="profile">
            <a href="profile.php" title="Profile">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </div>
</body>
</html>
