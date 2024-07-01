<?php
    session_start(); //starts the session
    require '../config/staff-authentication.php';

    if (isset($_GET['id'])) {
        $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    
        $sql = "SELECT products.product_id, products.product_name, products.price, categories.category_name
                FROM products
                INNER JOIN categories ON products.category_id = categories.category_id
                WHERE product_id ='$product_id'";
    
        $result = mysqli_query($conn, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $product_id = $row["product_id"];
            $product_name = $row["product_name"];
            $price = $row["price"];
            $category = $row["category_name"];
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
                    <li><a href="dashboard.php" ><i class="fas fa-solid fa-calendar"></i>Dashboard</a></li>
                    <li><a href="makeorder.php" ><i class="fas fa-solid fa-clipboard"></i>Make Order</a></li>
                    <li><a href="products.php" class="current"><i class="fas fa-solid fa-box"></i>Products</a></li>
                    <li><a href="transaction_history.php"><i class="fas fa-solid fa-receipt"></i>Transaction History</a></li>
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
                    <a href="products.php" class="back"> <i class="fa-solid fa-arrow-left"></i> </a>         
                </div>

                <div class="body">
                    <div class="edit-title">
                        <h2>Edit Product</h2>
                    </div>

                    <form  action="../config/update_product.php" method="post" autocomplete="off">

                        <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>">

                        <div class="input-box">
                            <label for="productName">Product Name:</label>
                            <input type="text" id="productName" name="productName" value="<?php echo $product_name ;?>" required>
                        </div>

                        <div class="input-box">
                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" value="<?php echo $price ;?>" required>
                        </div>

                        <div class="input-box">
                            <label for="categoryName">Category:</label>
                            <select id="categoryName" name="categoryName" value="<?php echo $category ;?>" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="Milkteas" <?php echo ($category == 'Milkteas') ? 'selected' : ''; ?> >Milkteas</option>
                                <option value="Fruiteas" <?php echo ($category == 'Fruiteas') ? 'selected' : ''; ?> >Fruiteas</option>
                                <option value="Lemonades" <?php echo ($category == 'Lemonades') ? 'selected' : ''; ?> >Lemonades</option>
                                <option value="Add Ons" <?php echo ($category == 'Add Ons') ? 'selected' : ''; ?> >Add Ons</option>
                                <option value="Iced Coffee" <?php echo ($category == 'Iced Coffee') ? 'selected' : ''; ?> >Iced Coffee</option>
                                <option value="Non-Coffee/Milk" <?php echo ($category == 'Non-Coffe/Milk') ? 'selected' : ''; ?> >Non-Coffee/Milk</option>
                                <option value="Hot Drinks" <?php echo ($category == 'Hot Drinks') ? 'selected' : ''; ?> >Hot Drinks</option>
                                <option value="Coffee/Milk Add Ons"<?php echo ($category == 'Coffee/Milk Add Ons') ? 'selected' : ''; ?> >Coffee/Milk Add Ons</option>
                            </select>
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