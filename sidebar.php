<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Add Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f4f6f9;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            font-size: 16px;
            transition: background 0.3s ease, color 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link i {
            margin-right: 15px;
            font-size: 20px;
        }

        .sidebar .nav-link:hover {
            background: linear-gradient(90deg, #007bff, #6610f2);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(90deg, #007bff, #6610f2);
            color: #f8f9fa;
            font-weight: bold;
        }

        .sidebar .nav-link.logout {
            background: #e74c3c;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .sidebar .nav-link.logout:hover {
            background: #c0392b;
            transform: translateY(-3px);
        }

        /* Sidebar logo */
        .sidebar .logo {
            text-align: center;
            padding: 20px;
            background: #1a252f;
            font-size: 22px;
            font-weight: bold;
            color: #f4f6f9;
            letter-spacing: 1px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar .logo img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        /* Main content styles */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .content p {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="eventlogo.png" alt="Logo">
            <div style="margin-top: 10px; font-size: 18px; font-weight: bold;">
                
            </div>
        </div>
        <ul>
        <li>
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a class="nav-link" href="manage_partners.php">
                    <i class="fas fa-handshake"></i> Manage Partners
                </a>
            </li>
            <li>
                <a class="nav-link" href="manage_services.php">
                    <i class="fas fa-cogs"></i> Manage Services
                </a>
            </li>
            <li>
                <a class="nav-link" href="manage_users.php">
                    <i class="fas fa-users"></i> Manage Users
                </a>
            </li>
            <li>
                <a class="nav-link" href="manage_bookings.php">
                    <i class="fas fa-bookmark"></i> Manage Bookings
                </a>
            </li>
           
            <li>
                <a class="nav-link logout" href="index.php?logout=true">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

</body>
</html>