<?php
    session_start(); //starts the session
    require '../config-admin/admin-authentication.php';
?>

<?php
    require("../config/connection.php");

    // Function to truncate past transactions
    function truncatePastTransactions($conn) {
        // Get today's date
        $todayDate = date('Y-m-d');

        // Truncate transactions before today's date
        $sql = "DELETE FROM `transactions` WHERE DATE(`date_time`) < '$todayDate'";
        if (mysqli_query($conn, $sql)) {

        } else {
            echo "Error truncating past transactions: " . mysqli_error($conn);
        }
    }

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Truncate past transactions
    truncatePastTransactions($conn);

    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
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
                    <li><a href="dashboard.php" class="current"><i class="fas fa-solid fa-calendar"></i>Dashboard</a></li>
                    <li><a href="users.php" ><i class="fas fa-solid fa-clipboard"></i>Users</a></li>
                    <li><a href="products.php"><i class="fas fa-solid fa-box"></i>Products</a></li>
                    <li><a href="transaction_history.php"><i class="fas fa-solid fa-receipt"></i>Transaction History</a></li>
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
                <div class="category-btns">
                    <a href="dashboard.php" class="card"> <div>Dashboard</div> </a>
                    <a href="users.php" class="card"> <div>Users</div> </a>
                    <a href="products.php" class="card"> <div>Products</div> </a>
                    <a href="transaction_history.php" class="card"> <div>Transaction History</div> </a>
                    <a href="discounts.php" class="card"> <div>Discounts</div> </a>
                    <a href="sales.php" class="card"> <div>Sales Report</div> </a>
                    <a href="logout.php" class="card"> <div>Log-Out</div> </a>
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