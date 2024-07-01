<?php
    session_start();

    include 'config/connection.php';

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check the username and password
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $entered_password = mysqli_real_escape_string($conn, $_POST['password']);

        $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);
            $stored_password = $row['password'];

            // Verify the entered password against the stored hash
            if (password_verify($entered_password, $stored_password)) {
                $_SESSION['user'] = $username;
                $account_type = $row['account_type'];

                // Redirect based on account type
                if ($account_type == 'admin') {
                    header("location: admin/dashboard.php"); // Redirect to admin page
                    $_SESSION['account_type'] = $account_type;
                } elseif ($account_type == 'staff') {
                    header("location: staff/dashboard.php"); // Redirect to staff page
                    $_SESSION['account_type'] = $account_type;
                } else {
                    header("location: index.php"); // Redirect to default page if account type is neither admin nor staff
                }

                exit();
            } else {
                echo '<script>alert("Incorrect Password!");</script>';
            }
        } else {
            echo '<script>alert("Incorrect username!");</script>';
        }
    }
?>

<!DOCTYPE html>
<html>
    
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Zen+Kaku+Gothic+Antique%3A400%2C700"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400%2C700"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins%3A400"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="main-card">
            <h2>SEAT AND SIP CAFE</h2>
            <hr class="line">


            <form action="index.php" method="POST" class="log">

                <div class="input-box">
                    <label for="username" >Enter Username: </label>
                    <input id="username" type="text" name="username" required="required" placeholder="Username"/>
                </div>

                <div class="input-box">
                    <label for="pass">Enter Password:</label>
                    <input id="pass" type="password" name="password" required="required" placeholder="Password"/>
                </div>
                
                <input type="submit" value="Log-in" name="login" class="btn">
            </form>
        </div>
    </div>
</body>

</html>    