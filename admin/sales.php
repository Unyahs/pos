<?php
    session_start(); //starts the session
    require '../config-admin/admin-authentication.php';

?>
<?php
    // Connect to your database
    require("../config/connection.php");
    date_default_timezone_set("Asia/Manila");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get today's date
    $today = date('M-d-Y');

    // Query to get transactions for cash today
    $cashQuery = "SELECT final_total_amount FROM `transactions` WHERE DATE_FORMAT(date_time, '%b-%d-%Y') = '$today' AND payment_type = 'Cash'";
    $cashResult = mysqli_query($conn, $cashQuery);

    // Query to get transactions for GCash today
    $gcashQuery = "SELECT final_total_amount FROM `transactions` WHERE DATE_FORMAT(date_time, '%b-%d-%Y') = '$today' AND payment_type = 'GCash'";
    $gcashResult = mysqli_query($conn, $gcashQuery);

    // Initialize variables for metrics
    $cashSales = 0;
    $gcashSales = 0;
    $totalCupsSold = 0;
    $itemCounts = array();

    // Calculate metrics from the transactions
    while ($row = mysqli_fetch_assoc($cashResult)) {
        // Total sales for cash today
        $cashSales += $row['final_total_amount'];
    }

    while ($row = mysqli_fetch_assoc($gcashResult)) {
        // Total sales for GCash today
        $gcashSales += $row['final_total_amount'];
    }

    // Query to get transactions for today
    $transactionQuery = "SELECT * FROM `transactions` WHERE DATE_FORMAT(date_time, '%b-%d-%Y') = '$today'";
    $transactionResult = mysqli_query($conn, $transactionQuery);

    // Calculate metrics from the transactions
    while ($row = mysqli_fetch_assoc($transactionResult)) {
        // Extract order details
        $orderDetails = json_decode($row["order_details"], true);

        foreach ($orderDetails as $item) {
            // Check if the item array has productName and productPrice keys
            if (isset($item['productName']) && isset($item['productPrice'])) {
                // Trim and standardize product name
                $productName = trim($item['productName']);
                
                // Exclude items with "Add On" in productName
                if (strpos($productName, 'Add On') === false) {
                    // Extract quantity from product name
                    $quantity = 1; // Default quantity if not specified
                    if (preg_match('/\sx(\d+)/', $productName, $matches)) {
                        $quantity = intval($matches[1]);
                    }

                    // Increment total cups sold
                    $totalCupsSold += $quantity;

                    // Extract base product name without quantity suffix
                    $baseProductName = preg_replace('/\sx\d+/', '', $productName);
                    $baseProductName = trim($baseProductName);

                    // Count the number of times each base product appears
                    if (array_key_exists($baseProductName, $itemCounts)) {
                        $itemCounts[$baseProductName] += $quantity;
                    } else {
                        $itemCounts[$baseProductName] = $quantity;
                    }
                }
            }
        }
    }

    // Sort itemCounts array to find the best seller
    arsort($itemCounts);
    $bestSeller = key($itemCounts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../styles/sales.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Zen+Kaku+Gothic+Antique%3A400%2C700"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Zen+Kaku+Gothic+New%3A400"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins%3A400"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400"/>
    <script src="https://kit.fontawesome.com/99316c4bd5.js" crossorigin="anonymous"></script>
    
</head>

<body>       
    <div class="wrapper">
        <div class="sidebar">
            <button id="closeButton">&times;</button>
            <h2>SEAT AND SIP CAFE</h2>
            <hr class="line">

            <nav>
                <ul>
                    <li><a href="dashboard.php" ><i class="fas fa-solid fa-calendar"></i>Dashboard</a></li>
                    <li><a href="users.php" ><i class="fas fa-solid fa-clipboard"></i>Users</a></li>
                    <li><a href="products.php"><i class="fas fa-solid fa-box"></i>Products</a></li>
                    <li><a href="transaction_history.php"><i class="fas fa-solid fa-receipt"></i>Transaction History</a></li>
                    <li><a href="discounts.php"><i class="fas fa-solid fa-file-pen"></i>Discounts</a></li>
                    <li><a href="sales.php" class="current"><i class="fas fa-solid fa-square-poll-vertical"></i>Sales Report</a></li>
                    <li><a href="../config/logout.php"><i class="fas fa-solid fa-door-open"></i>Log-Out</a></li>
                </ul>
            </nav>
            
        </div>
        
        <div class="main_content">
            <div class="header">
                <button id="hamburger"><i class="fa-solid fa-bars"></i></button>
                <div>Hello, <?php Print "$user"?>! <span id='date-time'></span></div>
            </div> 

            <div class="info">
                <div class="head">
                    <span class="heading">SALES REPORT For <?php echo date('M-d-Y'); ?></span>
                </div>

                
                <div class="body">
                    <div class="card">
                        <h3>Best Seller for Today:</h3>
                        <p><?php echo $bestSeller; ?></p>
                    </div>
                    <div class="card">
                        <h3>Total No. Of Cups Sold:</h3>
                        <p><?php echo $totalCupsSold; ?></p>
                    </div>
                    <div class="card">
                        <h3>Total Sales for Today (Cash):</h3>
                        <p>₱<?php echo number_format($cashSales, 2); ?></p>
                    </div>
                    <div class="card">
                        <h3>Total Sales for Today (Gcash):</h3>
                        <p>₱<?php echo number_format($gcashSales, 2); ?></p>
                    </div>
                </div>
            </div> 
            
        </div>
        
    </div>
    
    <!-- Date Script -->
    <script>
        var dt = new Date();
        var options = {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            timeZone: 'Asia/Manila', 
        hour12: true 
        
        };

        var formattedDate = dt.toLocaleString('en-PH', options);
        document.getElementById('date-time').innerHTML = formattedDate;
    </script>


    <!-- Hamburger Menu Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.getElementById("hamburger");
            const closeButton = document.getElementById("closeButton");
            const sidebar = document.querySelector(".sidebar");
           
            hamburger.addEventListener("click", function() {
                sidebar.classList.toggle("active");
            });

            // Close sidebar when close button is clicked
            closeButton.addEventListener("click", function() {
                sidebar.classList.remove("active");
            });

        });
    </script>

        
    
</body>
</html>