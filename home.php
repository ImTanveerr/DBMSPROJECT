<?php
// Start the session at the top of the file
session_start();

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
    <title>Home - User Panel</title>
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
            background-color: rgb(16, 7, 42);
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        /* Main content styles */
        .main-content {
            padding: 40px 20px;
            text-align: center;
        }

        .main-content h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .main-content p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .services-section {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
        }

        .service-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
        }

        .service-box h3 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .service-box p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .service-box a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        /* Profile Icon styles */
        .topbar .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar .profile i {
            color: rgb(255, 60, 0);
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

            .services-section {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Topbar (Navigation) -->
    <div class="topbar">
        <!-- Logo -->
        <a href="home.php" class="logo">User Panel</a>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="services.php">Services</a>
            <a href="my_bookings.php">My Cart</a>
            <a href="booking_history.php">Bookings History</a>
            <a href="logoutuser.php">Logout</a>
        </div>

        <!-- Profile Icon -->
        <div class="profile">
            <a href="profile.php" title="Profile">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome to Your User Dashboard</h1>
        <p>Explore and book the best venues and services for your events. You can view available services, manage your bookings, and track your booking history.</p>

        <!-- Services Section -->
        <div class="services-section">
            <div class="service-box">
                <h3>Event Venues</h3>
                <p>Browse and book event venues like resorts, hotels, and more.</p>
                <a href="services.php">Explore Venues</a>
            </div>
            <div class="service-box">
                <h3>Event Services</h3>
                <p>Choose from a variety of event services such as catering, photography, and decoration.</p>
                <a href="services.php">Explore Services</a>
            </div>
            <div class="service-box">
                <h3>My Bookings</h3>
                <p>View and manage all your event bookings and track their status.</p>
                <a href="my_bookings.php">View My Bookings</a>
            </div>
        </div>
    </div>

</body>
</html>
