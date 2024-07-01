
<?php
    require('connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle form submission

        $product_id = $_POST['product_id'];
        $product_name = $_POST['productName'];
        $price = $_POST['price'];
        $category = $_POST['categoryName'];

        $sql = "UPDATE products
                SET product_name = '$product_name', 
                    price = '$price', 
                    category_id = (SELECT category_id FROM categories WHERE category_name = '$category')
                WHERE product_id = '$product_id'";

    

        if (mysqli_query($conn, $sql)) {
            echo "Record updated successfully";
            header("Location: ../admin/products.php"); // Redirect back to products page after successful update
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
    ?>
