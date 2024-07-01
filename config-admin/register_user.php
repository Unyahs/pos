<?php
    require('connection.php');

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $account_type = mysqli_real_escape_string($conn, $_POST['account_type']);
        $bool = true;

        // Check if the username is already taken
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

        if (mysqli_num_rows($query) > 0) {
            $bool = false;
            echo '<script>alert("Username is not available!");</script>';
            echo '<script>window.location.assign("register.php");</script>';
        }

        if ($bool) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the values into the database
            $insert_query = "INSERT INTO users (username, password, account_type) VALUES ('$username', '$hashed_password', '$account_type')";

            if (mysqli_query($conn, $insert_query)) {
                echo '<script>alert("Successfully Registered");</script>';
                echo '<script>window.location.assign("register.php");</script>';
                header("location: ../admin/users.php");
            } else {
                echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
            }
        }
    }
    ?>