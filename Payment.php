<?php
require('db_config.php');
require_once('essentialuser.php');
userlogin();

if (!isset($_GET['service_id']) || !isset($_GET['total_cost'])) {
    echo "Service ID or total cost not provided.";
    exit;
}

$service_id = $_GET['service_id'];
$total_cost = $_GET['total_cost'];

// Fetch service details
$stmt = $con->prepare("SELECT service_name, cost FROM ManageServices WHERE id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute();
$stmt->bind_result($service_name, $service_cost);
$stmt->fetch();
$stmt->close();

if (empty($service_name)) {
    echo "Service not found.";
    exit;
}

// SSLCommerz Payment Integration
$post_data = array();
$post_data['store_id'] = "event67833bf9a1b77";  
$post_data['store_passwd'] = "event67833bf9a1b77@ssl";  
$post_data['total_amount'] = $total_cost; // Use the total cost received from my_bookings
$post_data['currency'] = "BDT";
$post_data['tran_id'] = "SSLCZ_TEST_" . uniqid();  
$post_data['success_url'] = "http://localhost/eventconnect/success.php?service_id=" . $service_id;
$post_data['fail_url'] = "http://localhost/eventconnect/fail.php?service_id=" . $service_id;
$post_data['cancel_url'] = "http://localhost/eventconnect/cancel.php";

// Customer Information
$post_data['cus_name'] = "Test Customer";
$post_data['cus_email'] = "test@test.com";
$post_data['cus_add1'] = "Dhaka";
$post_data['cus_add2'] = "Dhaka";
$post_data['cus_city'] = "Dhaka";
$post_data['cus_state'] = "Dhaka";
$post_data['cus_postcode'] = "1000";
$post_data['cus_country'] = "Bangladesh";
$post_data['cus_phone'] = "01711111111";

// Cart Details
$post_data['cart'] = json_encode(array(
    array("product" => $service_name, "amount" => $total_cost)
));

// Send Request to SSLCOMMERZ
$direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url);
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);  

$content = curl_exec($handle);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code == 200 && !curl_errno($handle)) {
    curl_close($handle);
    $sslcommerzResponse = $content;
} else {
    curl_close($handle);
    echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
    exit;
}

// Parse JSON Response
$sslcz = json_decode($sslcommerzResponse, true);

if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
    // Redirect to the Gateway
    echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
    exit;
} else {
    echo "JSON Data parsing error!";
}
?>
