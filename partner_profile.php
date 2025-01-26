<?php
require('db_config.php');
require('essential.php');

// Ensure the partner is logged in
// partnerLogin();

// Fetch partner information
$partner_id = $_SESSION['partner_id'];
$stmt = $con->prepare("SELECT partner_name, email, contact_phone, service_type, description, created_at FROM partners WHERE partner_id = ?");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$partner = $result->fetch_assoc();
$stmt->close();

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $partner_name = filter_input(INPUT_POST, 'partner_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contact_phone = filter_input(INPUT_POST, 'contact_phone', FILTER_SANITIZE_STRING);
    $service_type = filter_input(INPUT_POST, 'service_type', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    $update_stmt = $con->prepare("UPDATE partners SET partner_name = ?, email = ?, contact_phone = ?, service_type = ?, description = ? WHERE partner_id = ?");
    $update_stmt->bind_param("sssssi", $partner_name, $email, $contact_phone, $service_type, $description, $partner_id);
    if ($update_stmt->execute()) {
        $success_message = "Profile updated successfully!";
        // Refresh data
        $stmt = $con->prepare("SELECT partner_name, email, contact_phone, service_type, description, created_at FROM partners WHERE partner_id = ?");
        $stmt->bind_param("i", $partner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $partner = $result->fetch_assoc();
        $stmt->close();
    } else {
        $error_message = "Failed to update profile. Please try again.";
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $password_stmt = $con->prepare("SELECT partner_pass FROM partners WHERE partner_id = ?");
    $password_stmt->bind_param("i", $partner_id);
    $password_stmt->execute();
    $password_result = $password_stmt->get_result();
    $partner_data = $password_result->fetch_assoc();

    if (password_verify($current_password, $partner_data['partner_pass'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_pass_stmt = $con->prepare("UPDATE partners SET partner_pass = ? WHERE partner_id = ?");
            $update_pass_stmt->bind_param("si", $hashed_password, $partner_id);
            if ($update_pass_stmt->execute()) {
                $success_message = "Password changed successfully!";
            } else {
                $error_message = "Failed to change password. Please try again.";
            }
        } else {
            $error_message = "New passwords do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-header img {
            border-radius: 50%;
            margin-right: 20px;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<?php include('partner_sidebar.php'); ?>
    <div class="container mt-5">
        <div class="profile-header">
            <img src="profileavarter.png" alt="Partner Avatar" class="img-fluid" width="100">
            <div>
                <h1><?= htmlspecialchars($partner['partner_name']) ?></h1>
                <p>Partner since: <?= htmlspecialchars(date('F Y', strtotime($partner['created_at']))) ?></p>
            </div>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="partner_name">Name</label>
                <input type="text" id="partner_name" name="partner_name" class="form-control" value="<?= htmlspecialchars($partner['partner_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($partner['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_phone">Contact Phone</label>
                <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?= htmlspecialchars($partner['contact_phone']) ?>" required>
            </div>
            <div class="form-group">
                <label for="service_type">Service Type</label>
                <input type="text" id="service_type" name="service_type" class="form-control" value="<?= htmlspecialchars($partner['service_type']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?= htmlspecialchars($partner['description']) ?></textarea>
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
        </form>

        <form method="POST">
            <h3>Change Password</h3>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>