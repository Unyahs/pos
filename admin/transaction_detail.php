<?php 
    session_start(); //starts the session
    require '../config-admin/admin-authentication.php';
    require("../config/connection.php");

    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
    
        $sql = "SELECT * FROM `transactions` WHERE `id` = '$id'";
    
        $result = mysqli_query($conn, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $id = $row['id'];
            $username = $row['username'];
            $order_details = $row['order_details'];

            date_default_timezone_set('Asia/Manila');
            $date_time = $row['date_time'];
            $date_time = date('M-d-Y g:i A', strtotime($row['date_time']));

            $discount_code = $row['discount_code'];
            $discount_amount = $row['discount_amount'];

            // Check if discount amount is zero or empty
            $discountAmount = $row["discount_amount"] == 0 ? "-----" : rtrim(rtrim($row["discount_amount"], '0'), '.') . "%";

            // Check if discount code exists, otherwise set to -----
            $discountCode = $row["discount_code"] ? $row["discount_code"] : "";

            // Check if discount amount is zero, otherwise trim .00 and convert to %
            $discountAmount = $row["discount_amount"] == 0 ? "-----" : rtrim(rtrim($row["discount_amount"], '0'), '.') . "%";

            $final_total_amount = $row['final_total_amount'];
            $amount_received = $row['amount_received'];
            $change_amount = $row['change_amount'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../styles/transaction_details.css">
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
                    <li><a href="transaction_history.php" class="current"><i class="fas fa-solid fa-receipt"></i>Transaction History</a></li>
                    <li><a href="discounts.php"><i class="fas fa-solid fa-file-pen"></i>Discounts</a></li>
                    <li><a href="sales.php"><i class="fas fa-solid fa-square-poll-vertical"></i>Sales Report</a></li>
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
                    <a href="transaction_history.php" class="back"> <i class="fa-solid fa-arrow-left"></i> </a>    


                    <!-- Print button -->
                    <button id="printButton" class="print"> <i class="fa-solid fa-print"></i> Print Receipt</button>     
                </div>

                <div class="body">
                    <div class="receipt">
                        <div class="logo">SEAT & SIP CAFE</div>

                        <div class="receipt-head">Windfarm Tanay, Pilila, Rizal</div>

                        <div class="title receipt-head">----------RECEIPT----------</div>

                        <div class="date receipt-head"><?php echo $date_time; ?></div>

                        <div class="item">
                            <div>Order ID:</div>
                            <div class="amount"><?php echo $id; ?></div>
                        </div>

                        <div class="item">
                            <div>Cashier:</div>
                            <div class="amount"><?php echo $username; ?></div>
                        </div>
                        <div class="divider"></div>

                        <!-- Product details -->
                        <?php
                        $orderDetails = json_decode($order_details, true);
                        if ($order_details !== null) {
                            foreach ($orderDetails as $item) {
                                echo '<div class="item">';
                                echo '<div>' . htmlspecialchars($item['productName']) . ' </div>';
                                echo '<div class="amount">₱' . number_format($item['productPrice'], 2) . '</div>';
                                echo '</div>';
                            }
                        }
                        ?>

                        <div class="receipt-footer">
                            <div class="divider"></div>

                            <?php
                            ?>
                            <!-- Discount and total -->
                            <div class="item">
                                <div>Discount: <?php echo $discountCode; ?></div>
                                <div class="amount"><?php echo $discountAmount; ?></div>
                            </div>
                            <div class="item total">
                                <div>Total Amount:</div>
                                <div class="amount">₱<?php echo number_format($final_total_amount, 2); ?></div>
                            </div>
                            <div class="divider"></div>
                            <!-- Amount received and change -->
                            <div class="item">
                                <div>Amount Received:</div>
                                <div class="amount">₱<?php echo number_format($amount_received, 2); ?></div>
                            </div>
                            <div class="item total">
                                <div>Change:</div>
                                <div class="amount">₱<?php echo number_format($change_amount, 2); ?></div>
                            </div>
                        </div>                    
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

    <!-- Print Receipt Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const printButton = document.getElementById("printButton");

            printButton.addEventListener("click", function() {
                // Hide non-receipt elements
                document.querySelector(".sidebar").style.display = "none";
                document.querySelector(".header").style.display = "none";

                // Print only the receipt part
                window.print();

                // Show hidden elements after printing
                document.querySelector(".sidebar").style.display = "block";
                document.querySelector(".header").style.display = "block";
            });
        });
    </script>
    
</body>
</html>