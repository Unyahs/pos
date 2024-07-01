
<?php
    require('connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle form submission

        $discount_id = $_POST['discount_id'];
        $discount_code = $_POST['discount_code'];
        $discount_amount = $_POST['discount_amount'];

        $sql = "UPDATE discounts
                SET discount_code = '$discount_code', 
                    discount_amount = '$discount_amount'
                WHERE discount_id = '$discount_id'";

    

        if (mysqli_query($conn, $sql)) {
            echo "Record updated successfully";
            header("Location: ../admin/discounts.php"); 
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
    ?>
