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
    <link rel="stylesheet" href="../styles/products.css">
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
                    <span class="heading"> ON HAND PRODUCTS</span>

                    <button id="myBtn" class="add"> Add Product </button>

                    <input type="text" id="searchInput" placeholder="Search..." class="search">

                    <!-- Modal for ADD PRODUCTS -->
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-title">
                                <h2>Add New Product</h2>
                                <span class="close">&times;</span>
                            </div>
                            
                            <form action="../config/add product.php" method="post">
                                <div class="input-box">
                                    <label for="productName">Product Name:</label>
                                    <input type="text" id="productName" name="productName" required>
                                </div>

                                <div class="input-box">
                                    <label for="price">Price:</label>
                                    <input type="number" step="0.01" id="price" name="price" required>
                                </div>

                                <div class="input-box">
                                    <label for="categoryName">Category:</label>
                                    <select id="categoryName" name="categoryName" required>
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="Milkteas">Milkteas</option>
                                        <option value="Fruiteas">Fruiteas</option>
                                        <option value="Lemonades">Lemonades</option>
                                        <option value="Add Ons">Add Ons</option>
                                        <option value="Iced Coffee">Iced Coffee</option>
                                        <option value="Non-Coffee/Milk">Non-Coffee/Milk</option>
                                        <option value="Hot Drinks">Hot Drinks</option>
                                        <option value="Coffee/Milk Add Ons">Coffee/Milk Add Ons</option>
                                    </select>
                                </div>

                                <input type="submit" value="Add Product" class="btn">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="body">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th class="action">Edit</th>
                                <th class="action">Delete</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                require("../config/connection.php");
                                // Check connection
                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }
                                $query = mysqli_query($conn, "SELECT products.product_id, products.product_name, products.price, categories.category_name
                                    FROM products INNER JOIN categories ON products.category_id = categories.category_id ORDER BY categories.category_name, products.product_name");
                                while($row = mysqli_fetch_array($query)){
                                    //Display all the rows from query
                                    Print "<tr>";
                                        Print "<td data-cell='Product Name'> " . $row['product_name'] . " </td>";
                                        Print "<td data-cell='Price'> " . $row["price"] . " </td>";
                                        Print "<td data-cell='Category'> " . $row["category_name"] . " </td>";
                                        Print '<td class="action" data-cell="Edit"><a href="edit_product.php?id='.$row['product_id'].'"><i class="fa-regular fa-pen-to-square"></i></a></td>';
                                        Print '<td class="action" data-cell="Delete"><a href="../config/delete_product.php?id='.$row['product_id'].'" onclick="return confirmDelete();"><i class="fa-solid fa-trash"></i></a></td>';
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

    <!-- Add Modal Script -->
    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
        modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
        modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <!-- Delete Prompt Script-->
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this product?");
        }
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