<?php
require('connection.php');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $discount_code = $_POST["discount_code"];
    $discount_amount = $_POST["discount_amount"];

    // Insert new product into the database
    $sql = "INSERT INTO discounts (discount_code, discount_amount)
            VALUES ('$discount_code', $discount_amount)";

    if ($conn->query($sql) == TRUE) {
        echo "New product added successfully.";
        header("location: ../admin/discounts.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: Category not found.";
}


// Close the database connection
$conn->close();
?>