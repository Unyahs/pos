<?php
// get_discount.php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $discountCode = $_GET['code'];

    require('connection.php');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = mysqli_prepare($conn, "SELECT discount_amount FROM discounts WHERE discount_code = ?");
    if (!$query) {
        die("Query preparation failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($query, "s", $discountCode);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $discountAmount);

    

    if (mysqli_stmt_fetch($query)) {
        echo json_encode(['success' => true, 'discount' => $discountAmount]);
    } else {
        echo json_encode(['success' => false]);
    }

    mysqli_stmt_close($query);

    mysqli_close($conn);

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false]);
}
?>
