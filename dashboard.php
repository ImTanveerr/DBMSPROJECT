<?php
require('db_config.php'); // Include your database configuration file

// Fetch the total number of users
$userQuery = $con->query("SELECT COUNT(*) AS total_users FROM users");
$userData = $userQuery->fetch_assoc();
$totalUsers = $userData['total_users'];

// Fetch the total number of bookings
$bookingQuery = $con->query("SELECT COUNT(*) AS total_bookings FROM bookings");
$bookingData = $bookingQuery->fetch_assoc();
$totalBookings = $bookingData['total_bookings'];

// Fetch the total revenue
$revenueQuery = $con->query("SELECT SUM(total_cost) AS total_revenue FROM bookings");
$revenueData = $revenueQuery->fetch_assoc();
$totalRevenue = $revenueData['total_revenue'];

// Fetch the total number of active partners
$partnerQuery = $con->query("SELECT COUNT(*) AS active_partners FROM partners WHERE status = 'active'");
$partnerData = $partnerQuery->fetch_assoc();
$activePartners = $partnerData['active_partners'];

// Fetch new user registrations, partner additions, bookings, and revenue for the past 6 months
$newUsersData = [];
$newPartnersData = [];
$newBookingsData = [];
$monthlyRevenueData = [];
$months = [];
$currentMonth = date("Y-m");

for ($i = 5; $i >= 0; $i--) {
    $month = date("Y-m", strtotime("$currentMonth - $i months"));
    $months[] = date("M", strtotime($month)); // Store month names

    // Fetch new users
    $userQuery = $con->prepare("SELECT COUNT(*) AS users FROM users WHERE DATE_FORMAT(reg_date, '%Y-%m') = ?");
    $userQuery->bind_param("s", $month);
    $userQuery->execute();
    $userResult = $userQuery->get_result();
    $userData = $userResult->fetch_assoc();
    $newUsersData[] = $userData['users'];
    $userQuery->close();

    // Fetch new partners
    $partnerQuery = $con->prepare("SELECT COUNT(*) AS partners FROM partners WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
    $partnerQuery->bind_param("s", $month);
    $partnerQuery->execute();
    $partnerResult = $partnerQuery->get_result();
    $partnerData = $partnerResult->fetch_assoc();
    $newPartnersData[] = $partnerData['partners'];
    $partnerQuery->close();

    // Fetch new bookings
    $bookingQuery = $con->prepare("SELECT COUNT(*) AS bookings FROM bookings WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
    $bookingQuery->bind_param("s", $month);
    $bookingQuery->execute();
    $bookingResult = $bookingQuery->get_result();
    $bookingData = $bookingResult->fetch_assoc();
    $newBookingsData[] = $bookingData['bookings'];
    $bookingQuery->close();

    // Fetch monthly revenue
    $revenueQuery = $con->prepare("SELECT SUM(total_cost) AS revenue FROM bookings WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
    $revenueQuery->bind_param("s", $month);
    $revenueQuery->execute();
    $revenueResult = $revenueQuery->get_result();
    $revenueData = $revenueResult->fetch_assoc();
    $monthlyRevenueData[] = $revenueData['revenue'] ?? 0;
    $revenueQuery->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Content styles */
        .content {
            padding: 20px;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 36px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 24px;
            color: #333;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }

        /* Graph Section */
        .chart-container {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 300px; /* Reduced graph height */
            margin-bottom: 20px;
        }

        .chart-container h3 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #007bff;
        }

        canvas {
            height: 200px !important;
        }

        /* Flexbox for side-by-side graphs */
        .charts-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .chart-container {
            flex: 1;
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div class="content">
        <!-- Cards Section -->
        <div class="card-container">
            <div class="card">
                <i class="fas fa-users"></i>
                <h3><?php echo $totalUsers; ?></h3>
                <p>Total Users</p>
            </div>
            <div class="card">
                <i class="fas fa-bookmark"></i>
                <h3><?php echo $totalBookings; ?></h3>
                <p>Total Bookings</p>
            </div>
            <div class="card">
                <i class="fas fa-dollar-sign"></i>
                <h3>৳<?php echo number_format($totalRevenue, 2); ?></h3>
                <p>Total Revenue</p>
            </div>
            <div class="card">
                <i class="fas fa-handshake"></i>
                <h3><?php echo $activePartners; ?></h3>
                <p>Active Partners</p>
            </div>
        </div>

        <!-- Graphs Section: Side by Side -->
        <div class="charts-row">
            <!-- New Users Graph -->
            <div class="chart-container">
                <h3>New Users Growth (Last 6 Months)</h3>
                <canvas id="newUsersChart"></canvas>
            </div>

            <!-- New Partners Graph -->
            <div class="chart-container">
                <h3>New Partners Growth (Last 6 Months)</h3>
                <canvas id="newPartnersChart"></canvas>
            </div>
        </div>

        <!-- Graph Section: New Bookings and Monthly Revenue -->
        <div class="charts-row">
            <!-- New Bookings Graph -->
            <div class="chart-container">
                <h3>New Bookings Growth (Last 6 Months)</h3>
                <canvas id="newBookingsChart"></canvas>
            </div>

            <!-- Monthly Revenue Bar Graph -->
            <div class="chart-container">
                <h3>Monthly Revenue (Last 6 Months)</h3>
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Pass PHP data to JavaScript
        const months = <?php echo json_encode($months); ?>;
        const newUsersData = <?php echo json_encode($newUsersData); ?>;
        const newPartnersData = <?php echo json_encode($newPartnersData); ?>;
        const newBookingsData = <?php echo json_encode($newBookingsData); ?>;
        const monthlyRevenueData = <?php echo json_encode($monthlyRevenueData); ?>;

        // Chart for New Users
        const newUsersCtx = document.getElementById('newUsersChart').getContext('2d');
        new Chart(newUsersCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Users',
                    data: newUsersData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#007bff',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Months' } },
                    y: { title: { display: true, text: 'Count' }, beginAtZero: true }
                }
            }
        });

        // Chart for New Partners
        const newPartnersCtx = document.getElementById('newPartnersChart').getContext('2d');
        new Chart(newPartnersCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Partners',
                    data: newPartnersData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#28a745',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Months' } },
                    y: { title: { display: true, text: 'Count' }, beginAtZero: true }
                }
            }
        });

        // Chart for New Bookings
        const newBookingsCtx = document.getElementById('newBookingsChart').getContext('2d');
        new Chart(newBookingsCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Bookings',
                    data: newBookingsData,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffc107',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Months' } },
                    y: { title: { display: true, text: 'Count' }, beginAtZero: true }
                }
            }
        });

        // Chart for Monthly Revenue (Bar chart)
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Revenue (৳)',
                    data: monthlyRevenueData,
                    backgroundColor: '#28a745',
                    borderColor: '#28a745',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Months' } },
                    y: { title: { display: true, text: 'Revenue (৳)' }, beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
