<?php
session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'add') {
        addToCart($_POST['productName'], $_POST['productPrice']);
    } elseif ($action == 'remove') {
        removeFromCart($_POST['productName']);
    }
}

function addToCart($productName, $productPrice) {   
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the item is already in the cart
    $itemIndex = findItemIndex($productName);
    
    if ($itemIndex !== false) {
        // Item exists, update the quantity
        $_SESSION['cart'][$itemIndex]['quantity'] += 1;
    } else {
        // Add the item to the cart with quantity 1
        $_SESSION['cart'][] = array('productName' => $productName, 'productPrice' => $productPrice, 'quantity' => 1);
    }
}

function findItemIndex($productName) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['productName'] === $productName) {
                return $index;
            }
        }
    }
    return false;
}

function removeFromCart($productName) {
    if (isset($_SESSION['cart'])) {
        // Find the item index in the cart
        $itemIndex = findItemIndex($productName);

        if ($itemIndex !== false) {
            // Decrease the quantity by 1, or remove entirely if quantity becomes zero
            if ($_SESSION['cart'][$itemIndex]['quantity'] > 1) {
                $_SESSION['cart'][$itemIndex]['quantity'] -= 1;
            } else {
                unset($_SESSION['cart'][$itemIndex]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reset array keys
            }
        }
    }
}
?>
