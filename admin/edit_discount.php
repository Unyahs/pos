<?php
    session_start(); //starts the session
    require '../config-admin/admin-authentication.php';

    if (isset($_GET['id'])) {
        $discount_id = mysqli_real_escape_string($conn, $_GET['id']);
    
        $sql = "SELECT *
                FROM discounts              
                WHERE discount_id ='$discount_id'";
    
        $result = mysqli_query($conn, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $discount_id = $row["discount_id"];
            $discount_code = $row["discount_code"];
            $discount_amount = $row["discount_amount"];
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../styles/edit_product.css">
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
                    <li><a href="dashboard.php"><i class="fas fa-solid fa-calendar"></i>Dashboard</a></li>
                    <li><a href="users.php" ><i class="fas fa-solid fa-clipboard"></i>Users</a></li>
                    <li><a href="products.php"><i class="fas fa-solid fa-box"></i>Products</a></li>
                    <li><a href="transaction_history.php"><i class="fas fa-solid fa-receipt"></i>Transaction History</a></li>
                    <li><a href="discounts.php" class="current"><i class="fas fa-solid fa-file-pen"></i>Discounts</a></li>
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
                    <a href="discounts.php" class="back"> <i class="fa-solid fa-arrow-left"></i> </a>         
                </div>

                <div class="body">
                    <div class="edit-title">
                        <h2>Edit Discount</h2>
                    </div>

                    <form  action="../config-admin/update_discount.php" method="post" autocomplete="off">

                        <input type="hidden" name="discount_id" id="discount_id" value="<?php echo $discount_id; ?>">

                        <div class="input-box">
                            <label for="discount_code">Discount Code:</label>
                            <input type="text" id="discount_code" name="discount_code" value="<?php echo $discount_code ;?>" required>
                        </div>

                        <div class="input-box">
                            <label for="discount_amount">discount_amount:</label>
                            <input type="number" id="discount_amount" name="discount_amount" value="<?php echo $discount_amount ;?>" required>
                        </div>

                        <input type="submit" value="Edit" class="btn">
                    </form>
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