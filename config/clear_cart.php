<?php
session_start();

if (isset($_SESSION['cart'])) {
    // Clear the cart data in the session
    $_SESSION['cart'] = array();
    echo 'success';  // Send a success response if needed
} else {
    echo 'failure';  // Send a failure response if needed
}
?>
