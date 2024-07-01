<?php
    session_start(); //starts the session
    require '../config/staff-authentication.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../styles/transaction_history.css">
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
                    <li><a href="makeorder.php" ><i class="fas fa-solid fa-clipboard"></i>Make Order</a></li>
                    <li><a href="products.php" ><i class="fas fa-solid fa-box"></i>Products</a></li>
                    <li><a href="transaction_history.php" class="current"><i class="fas fa-solid fa-receipt"></i>Transaction History</a></li>
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
                    <span class="heading">TRANSACTION HISTORY</span>

                    <input type="text" id="searchInput" placeholder="Search..." class="search">
                </div>

                <div class="body">
                    <table>
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Cashier</th>
                            <th>Order Details</th>
                            <th>Date & Time</th>
                            <th>Discount Code</th>
                            <th>Discount</th>
                            <th>Total Amount</th>
                            <th>Payment Type</th>
                            <th>Amount Received</th>
                            <th>Change</th>
                            <th class="action">View Details</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                require("../config/connection.php");

                                // Check connection
                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }

                                $currentDate = date('Y-m-d');

                                $query = mysqli_query($conn, "SELECT * FROM `transactions` WHERE DATE(`date_time`) = '$currentDate' ORDER BY `date_time` DESC"); //SQL Query

                                while($row = mysqli_fetch_array($query)){

                                    //Display all the rows from query
                                    Print "<tr>";
                                        Print "<td data-cell='ID'> " . $row['id'] . " </td>";
                                        Print "<td data-cell='Username'> " . $row["username"] . " </td>";
                                        
                                        // Parse JSON data to get product details
                                        $orderDetails = json_decode($row["order_details"], true);
                                        $productDetails = array();
                                        foreach ($orderDetails as $item) {
                                            $productDetails[] = $item['productName'] . ': ₱' . $item['productPrice'];
                                        }
                                        $productDetailsString = implode(", ", $productDetails);
                                        $productDetailsString = strlen($productDetailsString) > 45 ? substr($productDetailsString, 0, 45) . "..." : $productDetailsString;

                                        Print "<td data-cell='Order Details' data-details='" . htmlspecialchars(json_encode($orderDetails)) . "'" . $productDetailsString . "'> " . $productDetailsString . " </td>";
                                        Print "<td data-cell='Date & Time' class='mid-td'> " . date('M-d-Y g:i A', strtotime($row["date_time"])) . " </td>";
                                        Print "<td data-cell='Discount Code' class='mid-td'> " . ($row["discount_code"] ? $row["discount_code"] : "-----") . " </td>";
                                        
                                        // Check if discount amount is zero or empty
                                        $discountAmount = $row["discount_amount"] == 0 ? "-----" : rtrim(rtrim($row["discount_amount"], '0'), '.') . "%";

                                        Print "<td data-cell='Discount' class='mid-td'> " . $discountAmount . " </td>";
                                        Print "<td data-cell='Total Amount' class='numbers'> ₱" . number_format($row["final_total_amount"], 2) . " </td>";
                                        Print "<td data-cell='Payment Type' class='mid-td'> " . $row["payment_type"] . " </td>";
                                        Print "<td data-cell='Amount Recieved' class='numbers'> ₱" . number_format($row["amount_received"], 2) . " </td>";
                                        Print "<td data-cell='Change' class='numbers'> ₱" . number_format($row["change_amount"], 2) . " </td>";
                                        Print '<td data-cell="View Details" class="action"> <a href="transaction_detail.php?id='.$row['id'].'"><i class="fa-solid fa-circle-info"></i></a></td>';
                                    Print "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
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

        
    <!-- Search Sript-->
    <script>
    // Function to filter the table based on the search input
    function filterTable() {
        // Declare variables
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector(".body table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows
        for (i = 0; i < tr.length; i++) {
            // Skip filtering for the header row (first row)
            if (i === 0) continue;
            
            var display = false; // Flag to determine if the row should be displayed
            td = tr[i].getElementsByTagName("td");

            // Loop through all columns in the row
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        display = true;
                        break; // If any column matches, no need to check other columns
                    }
                }
            }

            // Set display style based on the flag
            tr[i].style.display = display ? "" : "none";
        }
    }

    // Attach an event listener to the search input to trigger the filter function
    document.getElementById("searchInput").addEventListener("keyup", filterTable);
    </script>

</body>
</html>