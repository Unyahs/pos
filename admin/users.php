<?php
    session_start(); //starts the session
    require '../config-admin/admin-authentication.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../styles/users.css">
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
                    <li><a href="users.php" class="current"><i class="fas fa-solid fa-clipboard"></i>Users</a></li>
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
                <div class="head">
                    <span class="heading"> STAFF MEMBERS</span>

                    <button id="myBtn" class="add"> Add Staff </button>

                    <input type="text" id="searchInput" placeholder="Search..." class="search">

                    <!-- Modal for ADD PRODUCTS -->
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-title">
                                <h2>Add New User</h2>
                                <span class="close">&times;</span>
                            </div>
                            
                            <form action="../config-admin/register_user.php" method="post">
                                <div class="input-box">
                                    <label for="username">Username:</label>
                                    <input type="text" id="username" name="username" required="required">
                                </div>

                                <div class="input-box">
                                    <label for="pass">Password:</label>
                                    <input type="password" id="pass" name="password" required="required">
                                </div>

                                <div class="input-box">
                                    <label for="account_type">Account Type:</label>
                                    <select id="account_type" name="account_type" required>
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="admin">admin</option>
                                        <option value="staff">staff</option>
                                    </select>
                                </div>

                                <input type="submit" value="Add New User" class="btn">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="body">
                    <table>
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Username</th>
                                <th>Password</th>
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
                                $query = mysqli_query($conn, "SELECT * FROM users WHERE account_type ='staff'");
                                while($row = mysqli_fetch_array($query)){
                                    //Display all the rows from query
                                    Print "<tr>";
                                        Print "<td data-cell='Staff ID'> " . $row['user_id'] . " </td>";
                                        Print "<td data-cell='Username'> " . $row["username"] . " </td>";
                                        Print "<td data-cell='Password'> " . substr($row["password"], 0, 20) . "...</td>";
                                        Print '<td class="action" data-cell="Delete"><a href="../config-admin/delete_user.php?id='.$row['user_id'].'" onclick="return confirmDelete();"><i class="fa-solid fa-trash"></i></a></td>';
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
            return confirm("Are you sure you want to delete this user?");
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