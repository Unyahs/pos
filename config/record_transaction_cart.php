<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}

$user = $_SESSION['user'];

date_default_timezone_set('Asia/Manila');

// Retrieve transaction data from the request
$username = $_SESSION['user'];
$orderDetails = json_decode($_POST['orderDetails'], true);
$discountCode = $_POST['discountCode'];
$discountAmount = $_POST['discountAmount'];
$paymentType = $_POST['paymentType'];
$amountReceived = $_POST['amountReceived'];
$changeAmount = $_POST['changeAmount'];
$date_time = date("Y-m-d H:i:s"); // Get the current date and time in the required format

// Calculate finalTotalAmount based on the orderDetails and discount
$finalTotalAmount = calculateFinalTotalAmount($orderDetails, $discountAmount);

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "pos";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO transactions (username, order_details, date_time, discount_code, discount_amount, final_total_amount, payment_type, amount_received, change_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$orderDetails = json_encode($orderDetails);
$stmt->bind_param("ssssdssds", $username, $orderDetails, $date_time, $discountCode, $discountAmount, $finalTotalAmount, $paymentType, $amountReceived, $changeAmount);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "failure";
    error_log("Error executing SQL: " . $stmt->error);
}

$stmt->close();
$conn->close();

// Function to calculate finalTotalAmount based on orderDetails and discount
function calculateFinalTotalAmount($orderDetails, $discountAmount) {
    $totalAmount = 0;

    foreach ($orderDetails as $item) {
        $totalAmount += $item['productPrice'];
    }

    // Apply discount
    $discountPercentage = (float) str_replace('%', '', $discountAmount);
    $discountedAmount = $totalAmount * ($discountPercentage / 100);
    $finalTotalAmount = $totalAmount - $discountedAmount;

    return $finalTotalAmount;
}
?>
