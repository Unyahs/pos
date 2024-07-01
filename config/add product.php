<?php
require('connection.php');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["productName"];
    $price = $_POST["price"];
    $categoryName = $_POST["categoryName"];

    // Get the category ID based on the selected category name
    $sqlCategoryId = "SELECT category_id FROM categories WHERE category_name = '$categoryName'";
    $resultCategoryId = $conn->query($sqlCategoryId);

    if ($resultCategoryId->num_rows > 0) {
        $row = $resultCategoryId->fetch_assoc();
        $categoryID = $row["category_id"];

        // Insert new product into the database
        $sql = "INSERT INTO products (product_name, price, category_id)
                VALUES ('$productName', $price, $categoryID)";

        if ($conn->query($sql) == TRUE) {
            echo "New product added successfully.";
            header("location: ../staff/products.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: Category not found.";
    }
}

// Close the database connection
$conn->close();
?>